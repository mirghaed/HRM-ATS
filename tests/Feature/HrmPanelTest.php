<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\HrmSetting;
use App\Models\User;
use Database\Seeders\ApplicationSourceSeeder;
use Database\Seeders\RecruitmentStatusSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            '/hrm/dashboard',
            '/hrm/departments',
            '/hrm/job-positions',
            '/hrm/application-sources',
            '/hrm/applications',
            '/hrm/candidates',
            '/hrm/interviews',
            '/hrm/sms-templates',
            '/hrm/sms-logs',
            '/hrm/imports',
            '/hrm/reports',
            '/hrm/settings',
            '/hrm/source-connectors',
            '/hrm/recruitment-statuses',
            '/hrm/rejection-reasons',
            '/hrm/users',
        ];

        foreach ($pages as $page) {
            $this->actingAs($admin)->get($page)->assertOk();
        }
    }

    public function test_admin_can_create_department_and_job_position(): void
    {
        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)
            ->post('/hrm/departments', [
                'name' => 'محصول',
                'slug' => 'product',
                'is_active' => true,
                'is_public' => true,
            ])->assertRedirect('/hrm/departments');

        $department = Department::query()->where('slug', 'product')->firstOrFail();

        $this->actingAs($admin)
            ->post('/hrm/job-positions', [
                'department_id' => $department->id,
                'title' => 'Senior Product Manager',
                'slug' => 'senior-product-manager',
                'employment_type' => 'full_time',
                'work_mode' => 'hybrid',
                'status' => 'published',
                'is_public' => true,
            ])->assertRedirect('/hrm/job-positions');

        $this->actingAs($admin)
            ->get('/hrm/job-positions/1')->assertOk();
    }

    public function test_admin_can_create_panel_user_with_role(): void
    {
        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)
            ->post('/hrm/users', [
                'name' => 'Recruiter One',
                'email' => 'recruiter1@yadahrm.local',
                'mobile' => '09125550000',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'status' => 'active',
                'role' => 'HR Staff / Recruiter',
            ])->assertRedirect('/hrm/users');

        $this->assertDatabaseHas('users', [
            'email' => 'recruiter1@yadahrm.local',
            'name' => 'Recruiter One',
        ]);
    }

    public function test_public_landing_does_not_show_admin_login_link(): void
    {
        $this->get('/careers')
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

        $admin = User::query()->where('email', 'admin@yadahrm.local')->firstOrFail();

        $this->actingAs($admin)->get('/hrm/dashboard')->assertSee('پنل جذب تست')->assertSee('یادا تست');
        $this->get('/careers')->assertSee('قهرمان تست لندینگ')->assertSee('یادا تست');
    }
}
