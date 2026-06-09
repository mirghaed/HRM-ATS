<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Department;
use App\Models\JobPosition;
use App\Models\RecruitmentStatus;
use App\Models\User;
use App\Services\Captcha\NumericCaptchaService;
use Database\Seeders\ApplicationSourceSeeder;
use Database\Seeders\RecruitmentStatusSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CareersApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(ApplicationSourceSeeder::class);
        $this->seed(RecruitmentStatusSeeder::class);

        Storage::fake('private');
        Queue::fake();
    }

    public function test_general_application_is_created_and_visible_in_admin_panel(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->withSession([
            NumericCaptchaService::SESSION_KEY => '12345',
        ])->post('/careers/general-application', [
            'full_name' => 'Flow Candidate',
            'mobile' => '09120000123',
            'email' => 'flow@example.com',
            'captcha' => '12345',
            'resume' => UploadedFile::fake()->create('resume.pdf', 200, 'application/pdf'),
        ]);

        $response->assertCreated()->assertJson([
            'status' => 'ok',
        ]);

        $application = Application::query()->with('candidate')->first();
        $this->assertNotNull($application);
        $this->assertSame('Flow Candidate', $application->candidate?->full_name);
        $this->assertMatchesRegularExpression('/^\d{3}$/', (string) $application->tracking_code);
        $response->assertJsonPath('tracking_code', $application->tracking_code);

        $this->assertDatabaseHas('candidate_files', [
            'application_id' => $application->id,
            'type' => 'resume',
        ]);

        $this->assertDatabaseHas('application_status_histories', [
            'application_id' => $application->id,
            'to_status_id' => $application->current_status_id,
        ]);

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();
        $this->actingAs($admin)
            ->get('/applications')
            ->assertOk()
            ->assertSee('Flow Candidate');
    }

    public function test_job_apply_and_status_transition_flow_works_end_to_end(): void
    {
        $department = Department::query()->create([
            'name' => 'Engineering',
            'slug' => 'engineering',
            'is_active' => true,
            'is_public' => true,
        ]);

        $job = JobPosition::query()->create([
            'department_id' => $department->id,
            'title' => 'Laravel Developer',
            'slug' => 'laravel-dev',
            'employment_type' => 'full_time',
            'work_mode' => 'onsite',
            'status' => 'published',
            'is_public' => true,
            'location' => 'تهران',
        ]);

        $applyResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->withSession([
            NumericCaptchaService::SESSION_KEY => '67890',
        ])->post("/careers/jobs/{$job->slug}/apply", [
            'full_name' => 'Status Candidate',
            'mobile' => '09120000456',
            'email' => 'status@example.com',
            'expected_salary' => 35000000,
            'captcha' => '67890',
            'resume' => UploadedFile::fake()->create('resume.pdf', 150, 'application/pdf'),
        ]);

        $applyResponse->assertCreated()->assertJson([
            'status' => 'ok',
        ]);

        $application = Application::query()
            ->where('job_position_id', $job->id)
            ->firstOrFail();

        $this->assertMatchesRegularExpression('/^\d{3}$/', (string) $application->tracking_code);
        $applyResponse->assertJsonPath('tracking_code', $application->tracking_code);

        $screening = RecruitmentStatus::query()->where('key', 'screening')->firstOrFail();
        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)
            ->post("/applications/{$application->id}/change-status", [
                'status_id' => $screening->id,
                'note' => 'Initial screening started',
            ])
            ->assertRedirect();

        $application->refresh();
        $this->assertSame($screening->id, $application->current_status_id);

        $this->assertSame(2, ApplicationStatusHistory::query()
            ->where('application_id', $application->id)
            ->count());
    }

    public function test_jobs_index_lists_all_published_positions(): void
    {
        $department = Department::query()->create([
            'name' => 'Engineering',
            'slug' => 'engineering',
            'is_active' => true,
            'is_public' => true,
        ]);

        JobPosition::query()->create([
            'department_id' => $department->id,
            'title' => 'Backend Developer',
            'slug' => 'backend-dev',
            'employment_type' => 'full_time',
            'work_mode' => 'onsite',
            'status' => 'published',
            'is_public' => true,
        ]);

        $this->get('/careers/jobs')
            ->assertOk()
            ->assertSee('همه فرصت‌های شغلی باز')
            ->assertSee('Backend Developer');
    }

    public function test_landing_page_shows_view_all_link_when_more_than_nine_jobs(): void
    {
        $department = Department::query()->create([
            'name' => 'Engineering',
            'slug' => 'engineering',
            'is_active' => true,
            'is_public' => true,
        ]);

        for ($i = 1; $i <= 10; $i++) {
            JobPosition::query()->create([
                'department_id' => $department->id,
                'title' => "Job Position {$i}",
                'slug' => "job-position-{$i}",
                'employment_type' => 'full_time',
                'work_mode' => 'onsite',
                'status' => 'published',
                'is_public' => true,
            ]);
        }

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('مشاهده همه 10 فرصت شغلی');

        $this->assertSame(9, substr_count($response->getContent(), 'class="ys-job-card"'));
    }
}
