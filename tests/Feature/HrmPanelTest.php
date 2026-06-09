<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\CandidateFile;
use App\Models\Department;
use App\Models\HrmSetting;
use App\Models\RecruitmentStatus;
use App\Models\User;
use Database\Seeders\ApplicationSourceSeeder;
use Database\Seeders\RecruitmentStatusSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HrmPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(ApplicationSourceSeeder::class);
        $this->seed(RecruitmentStatusSeeder::class);
    }

    public function test_admin_can_open_all_main_hrm_pages(): void
    {
        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $pages = [
            '/dashboard',
            '/departments',
            '/job-positions',
            '/application-sources',
            '/applications',
            '/candidates',
            '/interviews',
            '/sms-templates',
            '/sms-logs',
            '/imports',
            '/reports',
            '/settings',
            '/source-connectors',
            '/recruitment-statuses',
            '/rejection-reasons',
            '/users',
        ];

        foreach ($pages as $page) {
            $this->actingAs($admin)->get($page)->assertOk();
        }
    }

    public function test_admin_can_create_department_and_job_position(): void
    {
        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)
            ->post('/departments', [
                'name' => 'محصول',
                'slug' => 'product',
                'is_active' => true,
                'is_public' => true,
            ])->assertRedirect('/departments');

        $department = Department::query()->where('slug', 'product')->firstOrFail();

        $this->actingAs($admin)
            ->post('/job-positions', [
                'department_id' => $department->id,
                'title' => 'Senior Product Manager',
                'slug' => 'senior-product-manager',
                'employment_type' => 'full_time',
                'work_mode' => 'hybrid',
                'status' => 'published',
                'is_public' => true,
            ])->assertRedirect('/job-positions');

        $this->actingAs($admin)
            ->get('/job-positions/1')->assertOk();
    }

    public function test_admin_can_create_panel_user_with_role(): void
    {
        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)
            ->post('/users', [
                'name' => 'Recruiter One',
                'email' => 'recruiter1@yadahrm.local',
                'mobile' => '09125550000',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'status' => 'active',
                'role' => 'HR Staff / Recruiter',
            ])->assertRedirect('/users');

        $this->assertDatabaseHas('users', [
            'email' => 'recruiter1@yadahrm.local',
            'name' => 'Recruiter One',
        ]);
    }

    public function test_public_landing_does_not_show_admin_login_link(): void
    {
        $this->get(route('careers.index'))
            ->assertOk()
            ->assertDontSee('ورود پنل');
    }

    public function test_login_page_has_professional_copy(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('ورود به پنل مدیریت جذب و استخدام');
    }

    public function test_updated_panel_and_landing_titles_are_reflected(): void
    {
        HrmSetting::query()->updateOrCreate(
            ['key' => 'company.name'],
            ['group' => 'general', 'type' => 'string', 'value' => 'یادا تست', 'is_public' => true],
        );

        HrmSetting::query()->updateOrCreate(
            ['key' => 'panel.title'],
            ['group' => 'general', 'type' => 'string', 'value' => 'پنل جذب تست', 'is_public' => true],
        );

        HrmSetting::query()->updateOrCreate(
            ['key' => 'landing.hero_title'],
            ['group' => 'landing', 'type' => 'string', 'value' => 'قهرمان تست لندینگ', 'is_public' => true],
        );

        HrmSetting::query()->updateOrCreate(
            ['key' => 'landing.hero_badge'],
            ['group' => 'landing', 'type' => 'string', 'value' => 'جای تو در تیم {company} خالیه', 'is_public' => true],
        );

        HrmSetting::query()->updateOrCreate(
            ['key' => 'landing.header_brand_text'],
            ['group' => 'landing', 'type' => 'string', 'value' => 'فرصت‌های همکاری {company}', 'is_public' => true],
        );

        HrmSetting::query()->updateOrCreate(
            ['key' => 'landing.why_title'],
            ['group' => 'landing', 'type' => 'string', 'value' => 'چرا {company}؟', 'is_public' => true],
        );

        HrmSetting::query()->updateOrCreate(
            ['key' => 'landing.culture_title'],
            ['group' => 'landing', 'type' => 'string', 'value' => 'فرهنگ همکاری در {company}', 'is_public' => true],
        );

        \Illuminate\Support\Facades\Cache::forget('hrm.settings.map');

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)->get('/dashboard')->assertSee('پنل جذب تست')->assertSee('یادا تست');
        $this->get(route('careers.index'))
            ->assertSee('قهرمان تست لندینگ')
            ->assertSee('جای تو در تیم یادا تست خالیه')
            ->assertSee('فرصت‌های همکاری یادا تست')
            ->assertSee('چرا یادا تست؟')
            ->assertSee('فرهنگ همکاری در یادا تست');
    }

    public function test_admin_can_upload_landing_logo(): void
    {
        Storage::fake('public');

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('hrm.settings.gallery-upload'), [
            'logo' => UploadedFile::fake()->image('brand-logo.png', 240, 80),
        ]);

        $response->assertOk()->assertJson(['status' => 'ok']);

        $path = (string) $response->json('path');
        $this->assertStringStartsWith('/media/brand/logos/logo-', $path);
        Storage::disk('public')->assertExists('brand/logos/'.basename($path));
    }

    public function test_media_route_serves_uploaded_logo(): void
    {
        Storage::disk('public')->put('brand/logos/logo-test.png', 'fake-image');

        $this->get('/media/brand/logos/logo-test.png')->assertOk();
    }

    public function test_media_route_serves_dark_mode_logo(): void
    {
        Storage::disk('public')->put('brand/logos-dark/logo-dark-test.png', 'fake-image');

        $this->get('/media/brand/logos-dark/logo-dark-test.png')->assertOk();
    }

    public function test_admin_can_upload_dark_mode_logo(): void
    {
        Storage::fake('public');

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('hrm.settings.gallery-upload'), [
            'logo_dark' => UploadedFile::fake()->image('brand-logo-dark.png', 240, 80),
        ]);

        $response->assertOk()->assertJson(['status' => 'ok']);

        $path = (string) $response->json('path');
        $this->assertStringStartsWith('/media/brand/logos-dark/logo-dark-', $path);
        Storage::disk('public')->assertExists('brand/logos-dark/'.basename($path));
    }

    public function test_legacy_brand_logo_route_serves_uploaded_logo(): void
    {
        Storage::disk('public')->put('brand/logos/logo-20260607-test.png', 'fake-image');

        $this->get('/assets/brand/uploads/logo-20260607-test.png')->assertOk();
    }

    public function test_admin_can_upload_gallery_slide_image(): void
    {
        Storage::fake('public');

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('hrm.settings.gallery-upload'), [
            'image' => UploadedFile::fake()->image('slide.png', 700, 467),
        ]);

        $response->assertOk()->assertJson(['status' => 'ok']);

        $path = (string) $response->json('path');
        $this->assertStringStartsWith('/media/careers/gallery/gallery-', $path);
        Storage::disk('public')->assertExists('careers/gallery/'.basename($path));
    }

    public function test_media_route_serves_uploaded_gallery_image(): void
    {
        Storage::disk('public')->put('careers/gallery/gallery-20260607-test.png', 'fake-image');

        $this->get('/media/careers/gallery/gallery-20260607-test.png')->assertOk();
    }

    public function test_legacy_gallery_route_serves_uploaded_image_from_storage(): void
    {
        Storage::disk('public')->put('careers/gallery/gallery-20260607-test.png', 'fake-image');

        $this->get('/assets/careers/gallery/gallery-20260607-test.png')->assertOk();
    }

    public function test_admin_can_view_resume_pdf_inline(): void
    {
        Storage::fake('private');

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();
        $status = RecruitmentStatus::query()->orderBy('sort_order')->firstOrFail();

        $candidate = Candidate::query()->create([
            'full_name' => 'PDF Viewer Candidate',
            'mobile' => '09120000999',
            'email' => 'pdf@example.com',
        ]);

        $application = Application::query()->create([
            'candidate_id' => $candidate->id,
            'current_status_id' => $status->id,
            'tracking_code' => '482',
            'applied_at' => now(),
        ]);

        $path = 'resumes/test-resume.pdf';
        Storage::disk('private')->put($path, '%PDF-1.4 fake resume content');

        $file = CandidateFile::query()->create([
            'candidate_id' => $candidate->id,
            'application_id' => $application->id,
            'type' => 'resume',
            'disk' => 'private',
            'path' => $path,
            'original_name' => 'resume.pdf',
            'mime_type' => 'application/pdf',
            'size' => 128,
        ]);

        $this->actingAs($admin)
            ->get(route('hrm.applications.files.view', [$application, $file]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('x-frame-options', 'SAMEORIGIN');

        $this->actingAs($admin)
            ->get(route('hrm.applications.show', $application))
            ->assertOk()
            ->assertSee('رزومه پیوست شده')
            ->assertSee(route('hrm.applications.files.view', [$application, $file], false));

        $this->actingAs($admin)
            ->get(route('hrm.applications.index'))
            ->assertOk()
            ->assertSee('مشاهده رزومه')
            ->assertSee('482');
    }
}
