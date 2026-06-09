<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\JobPosition;
use App\Models\User;
use App\Services\HRM\JobPositionVisitRecorder;
use Database\Seeders\ApplicationSourceSeeder;
use Database\Seeders\RecruitmentStatusSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardVisitsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(ApplicationSourceSeeder::class);
        $this->seed(RecruitmentStatusSeeder::class);
    }

    public function test_public_job_page_records_a_visit_for_guests(): void
    {
        $job = $this->createPublishedJob('Visit Test Job');

        $this->get(route('careers.jobs.show', $job))->assertOk();

        $this->assertSame(1, visits($job, JobPositionVisitRecorder::TAG)->count());
    }

    public function test_refresh_does_not_increment_visit_within_same_ip_window(): void
    {
        $job = $this->createPublishedJob('Deduped Visit Job');

        $this->get(route('careers.jobs.show', $job))->assertOk();
        $this->get(route('careers.jobs.show', $job))->assertOk();

        $this->assertSame(1, visits($job, JobPositionVisitRecorder::TAG)->count());
    }

    public function test_authenticated_admin_visit_is_not_recorded(): void
    {
        $job = $this->createPublishedJob('Admin Visit Job');
        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('careers.jobs.show', $job))
            ->assertOk();

        $this->assertSame(0, visits($job, JobPositionVisitRecorder::TAG)->count());
    }

    public function test_dashboard_shows_visit_analytics_table(): void
    {
        $job = $this->createPublishedJob('Dashboard Analytics Job');
        visits($job, JobPositionVisitRecorder::TAG)->increment(5);

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('آمار بازدید موقعیت‌های شغلی')
            ->assertSee('Dashboard Analytics Job')
            ->assertSee('پربازدیدترین موقعیت‌ها');
    }

    public function test_conversion_rate_is_zero_when_no_views(): void
    {
        $recorder = app(JobPositionVisitRecorder::class);

        $this->assertSame(0.0, $recorder->conversionRate(3, 0));
    }

    public function test_job_positions_index_shows_visit_columns(): void
    {
        $job = $this->createPublishedJob('Index Visit Job');
        visits($job, JobPositionVisitRecorder::TAG)->increment(2);

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('hrm.job-positions.index'))
            ->assertOk()
            ->assertSee('بازدید کل')
            ->assertSee('نرخ تبدیل')
            ->assertSee('Index Visit Job');
    }

    private function createPublishedJob(string $title): JobPosition
    {
        $department = Department::query()->create([
            'name' => 'Engineering',
            'slug' => 'engineering-'.uniqid(),
            'is_active' => true,
            'is_public' => true,
        ]);

        return JobPosition::query()->create([
            'department_id' => $department->id,
            'title' => $title,
            'slug' => str()->slug($title).'-'.uniqid(),
            'employment_type' => 'full_time',
            'work_mode' => 'onsite',
            'status' => 'published',
            'is_public' => true,
            'location' => 'تهران',
        ]);
    }
}
