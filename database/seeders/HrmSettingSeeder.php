<?php

namespace Database\Seeders;

use App\Models\HrmSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class HrmSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company.name', 'value' => 'یادا', 'group' => 'general', 'type' => 'string'],
            ['key' => 'company.phone', 'value' => '02100000000', 'group' => 'general', 'type' => 'string'],
            ['key' => 'panel.title', 'value' => 'سامانه جذب نیرو', 'group' => 'general', 'type' => 'string'],
            ['key' => 'panel.subtitle', 'value' => 'HRM / ATS', 'group' => 'general', 'type' => 'string'],
            ['key' => 'landing.hero_title', 'value' => 'به تیم‌هایی بپیوند که رشد را واقعی می‌سازند', 'group' => 'landing', 'type' => 'string'],
            ['key' => 'landing.hero_subtitle', 'value' => 'در یادا روی پروژه‌های واقعی کار می‌کنیم، مسئولیت می‌گیریم و با داده تصمیم می‌گیریم.', 'group' => 'landing', 'type' => 'string'],
            ['key' => 'landing.logo_url', 'value' => '/assets/brand/yadak-shop-logo.svg', 'group' => 'landing', 'type' => 'string'],
            ['key' => 'landing.og_image', 'value' => '', 'group' => 'landing', 'type' => 'string'],
            ['key' => 'landing.show_salary', 'value' => '0', 'group' => 'landing', 'type' => 'boolean'],
            ['key' => 'landing.show_faq', 'value' => '1', 'group' => 'landing', 'type' => 'boolean'],
            ['key' => 'landing.gallery_enabled', 'value' => '1', 'group' => 'landing', 'type' => 'boolean'],
            ['key' => 'landing.gallery_title', 'value' => 'تصویر زندگی کاری در یادا', 'group' => 'landing', 'type' => 'string'],
            ['key' => 'landing.gallery_slides', 'value' => '[{"image":"/assets/careers/gallery/coverflow-reference.jpg","alt":"نمایی از فضای واقعی انبار و عملیات","sort_order":5},{"image":"/assets/careers/illustrations/hero-teamwork.png","alt":"فضای کاری تیم یادا","sort_order":10},{"image":"/assets/careers/illustrations/jobs-search-dashboard.png","alt":"همکاری تیم عملیات","sort_order":20},{"image":"/assets/careers/illustrations/culture-teamwork.png","alt":"فرهنگ کاری یادا","sort_order":30},{"image":"/assets/careers/illustrations/departments-team-network.png","alt":"تیم‌های تخصصی یادا","sort_order":40},{"image":"/assets/careers/illustrations/hiring-process.png","alt":"فرآیند جذب نیرو","sort_order":50},{"image":"/assets/careers/illustrations/career-growth.png","alt":"رشد حرفه‌ای در یادا","sort_order":60}]', 'group' => 'landing', 'type' => 'json'],

            ['key' => 'recruitment.auto_assign_recruiter', 'value' => '1', 'group' => 'recruitment', 'type' => 'boolean'],
            ['key' => 'recruitment.require_department', 'value' => '0', 'group' => 'recruitment', 'type' => 'boolean'],

            ['key' => 'sources.allow_excel_import', 'value' => '1', 'group' => 'sources', 'type' => 'boolean'],
            ['key' => 'sources.allow_email_import', 'value' => '0', 'group' => 'sources', 'type' => 'boolean'],

            ['key' => 'sms.enabled', 'value' => '0', 'group' => 'sms', 'type' => 'boolean'],
            ['key' => 'sms.provider', 'value' => 'smsir', 'group' => 'sms', 'type' => 'string'],
            ['key' => 'sms.api_key', 'value' => '', 'group' => 'sms', 'type' => 'string'],
            ['key' => 'sms.base_url', 'value' => 'https://api.sms.ir/v1', 'group' => 'sms', 'type' => 'string'],
            ['key' => 'sms.sandbox', 'value' => '0', 'group' => 'sms', 'type' => 'boolean'],
            ['key' => 'sms.retry_attempts', 'value' => '2', 'group' => 'sms', 'type' => 'integer'],
            ['key' => 'sms.queue_name', 'value' => 'default', 'group' => 'sms', 'type' => 'string'],
            ['key' => 'sms.default_company_name', 'value' => 'یادا', 'group' => 'sms', 'type' => 'string'],
            ['key' => 'sms.default_contact_phone', 'value' => '02100000000', 'group' => 'sms', 'type' => 'string'],

            ['key' => 'process.require_note_on_reject', 'value' => '1', 'group' => 'process', 'type' => 'boolean'],
            ['key' => 'process.allow_force_transition', 'value' => '0', 'group' => 'process', 'type' => 'boolean'],

            ['key' => 'upload.max_resume_size_kb', 'value' => '10240', 'group' => 'file', 'type' => 'integer'],
            ['key' => 'upload.allowed_resume_extensions', 'value' => 'pdf', 'group' => 'file', 'type' => 'string'],

            ['key' => 'notifications.department_manager_on_new_application', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'notifications.interviewer_on_schedule', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],
            ['key' => 'notifications.notify_hr_on_sms_failed', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean'],

            ['key' => 'security.force_https_for_public_forms', 'value' => '0', 'group' => 'security', 'type' => 'boolean'],
            ['key' => 'security.block_public_resume_download', 'value' => '1', 'group' => 'security', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            HrmSetting::query()->updateOrCreate(
                ['key' => $setting['key']],
                [...$setting, 'is_public' => false],
            );
        }

        Cache::forget('hrm.settings.map');
    }
}
