<?php

namespace Database\Seeders;

use App\Models\ApplicationSource;
use Illuminate\Database\Seeder;

class ApplicationSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            ['name' => 'فرم سایت', 'key' => 'website_form', 'type' => 'website_form', 'supports_auto_import' => false],
            ['name' => 'جابینجا', 'key' => 'jobinja', 'type' => 'job_board', 'supports_auto_import' => false],
            ['name' => 'جاب ویژن', 'key' => 'jobvision', 'type' => 'job_board', 'supports_auto_import' => false],
            ['name' => 'ایران استخدام', 'key' => 'iranestekhdam', 'type' => 'job_board', 'supports_auto_import' => false],
            ['name' => 'دیوار', 'key' => 'divar', 'type' => 'job_board', 'supports_auto_import' => false],
            ['name' => 'واتساپ', 'key' => 'whatsapp', 'type' => 'social', 'supports_auto_import' => false],
            ['name' => 'تلگرام', 'key' => 'telegram', 'type' => 'social', 'supports_auto_import' => false],
            ['name' => 'ایمیل', 'key' => 'email', 'type' => 'email', 'supports_auto_import' => false],
            ['name' => 'معرفی همکار', 'key' => 'employee_referral', 'type' => 'referral', 'supports_auto_import' => false],
            ['name' => 'Excel / CSV', 'key' => 'excel_import', 'type' => 'import', 'supports_auto_import' => true],
            ['name' => 'ورود دستی', 'key' => 'manual', 'type' => 'manual', 'supports_auto_import' => false],
        ];

        foreach ($sources as $source) {
            ApplicationSource::query()->updateOrCreate(
                ['key' => $source['key']],
                [...$source, 'is_active' => true],
            );
        }
    }
}