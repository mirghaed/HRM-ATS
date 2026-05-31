<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\HrmSettingUpdateRequest;
use App\Services\HRM\HrmSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HrmSettingController extends Controller
{
    public function edit(HrmSettingService $settingService)
    {
        abort_unless(auth()->user()?->can('settings.view'), 403);

        $settings = $this->definitions()
            ->groupBy('group')
            ->map(function (Collection $items) use ($settingService) {
                return $items->map(function (array $item) use ($settingService) {
                    $item['value'] = $settingService->get($item['key'], $item['default']);

                    return $item;
                });
            });

        return view('hrm.settings.edit', compact('settings'));
    }

    public function update(HrmSettingUpdateRequest $request, HrmSettingService $settingService)
    {
        foreach ($request->validated('settings') as $item) {
            $value = $this->normalizeValueByType($item['value'] ?? null, $item['type']);

            $settingService->set(
                key: $item['key'],
                value: $value,
                group: $item['group'],
                type: $item['type'],
                isPublic: (bool) ($item['is_public'] ?? false),
            );
        }

        return back()->with('success', 'تنظیمات با موفقیت ذخیره شد و در کل سیستم اعمال شد.');
    }

    public function uploadGalleryImage(Request $request)
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        $validated = $request->validate([
            'image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,avif',
                'mimetypes:image/jpeg,image/png,image/webp,image/avif',
                'max:5120',
            ],
        ]);

        $image = $validated['image'];
        $directory = public_path('assets/careers/gallery');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'gallery-'.now()->format('Ymd-His').'-'.Str::lower(Str::random(8)).'.'.$image->getClientOriginalExtension();
        $image->move($directory, $filename);

        $path = '/assets/careers/gallery/'.$filename;

        return response()->json([
            'status' => 'ok',
            'path' => $path,
            'url' => asset(ltrim($path, '/')),
        ]);
    }

    private function definitions(): Collection
    {
        return collect([
            // General
            ['key' => 'company.name', 'label' => 'نام شرکت', 'group' => 'general', 'type' => 'string', 'input' => 'text', 'default' => 'یدا', 'help' => 'نام برند در پنل و صفحات عمومی.'],
            ['key' => 'company.phone', 'label' => 'تلفن شرکت', 'group' => 'general', 'type' => 'string', 'input' => 'text', 'default' => '02100000000', 'help' => 'شماره تماس اصلی شرکت.'],
            ['key' => 'company.address', 'label' => 'آدرس شرکت', 'group' => 'general', 'type' => 'string', 'input' => 'textarea', 'default' => '', 'help' => 'برای نمایش در صفحات عمومی و پیام‌ها.'],
            ['key' => 'panel.title', 'label' => 'عنوان پنل', 'group' => 'general', 'type' => 'string', 'input' => 'text', 'default' => 'سامانه جذب نیرو', 'help' => 'عنوان اصلی هدر پنل ادمین.'],
            ['key' => 'panel.subtitle', 'label' => 'زیرعنوان پنل', 'group' => 'general', 'type' => 'string', 'input' => 'text', 'default' => 'HRM / ATS', 'help' => 'زیرعنوان کوتاه در هدر پنل.'],

            // Recruitment
            ['key' => 'recruitment.default_source', 'label' => 'منبع پیش‌فرض رزومه', 'group' => 'recruitment', 'type' => 'string', 'input' => 'text', 'default' => 'manual', 'help' => 'کلید منبع پیش‌فرض برای ثبت دستی.'],
            ['key' => 'recruitment.require_department', 'label' => 'الزام انتخاب دپارتمان هنگام ثبت رزومه', 'group' => 'recruitment', 'type' => 'boolean', 'input' => 'boolean', 'default' => false, 'help' => 'در فرم داخلی ثبت رزومه.'],
            ['key' => 'recruitment.auto_assign_recruiter', 'label' => 'اختصاص خودکار Recruiter پیش‌فرض موقعیت', 'group' => 'recruitment', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'در زمان ایجاد Application اعمال می‌شود.'],

            // Sources
            ['key' => 'sources.allow_excel_import', 'label' => 'فعال بودن ایمپورت Excel/CSV', 'group' => 'sources', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'کنترل دسترسی عملیاتی ایمپورت فایل.'],
            ['key' => 'sources.allow_email_import', 'label' => 'فعال بودن ایمپورت Email', 'group' => 'sources', 'type' => 'boolean', 'input' => 'boolean', 'default' => false, 'help' => 'تا زمان اتصال رسمی غیرفعال بماند.'],

            // SMS
            ['key' => 'sms.enabled', 'label' => 'فعال بودن ارسال پیامک', 'group' => 'sms', 'type' => 'boolean', 'input' => 'boolean', 'default' => false, 'help' => 'کلید اصلی فعال‌سازی پیامک.'],
            ['key' => 'sms.provider', 'label' => 'ارائه‌دهنده پیامک', 'group' => 'sms', 'type' => 'string', 'input' => 'text', 'default' => 'smsir', 'help' => 'در حال حاضر: smsir.'],
            ['key' => 'sms.api_key', 'label' => 'API Key سرویس پیامک', 'group' => 'sms', 'type' => 'string', 'input' => 'text', 'default' => '', 'help' => 'کلید اتصال سرویس پیامک.'],
            ['key' => 'sms.base_url', 'label' => 'Base URL سرویس پیامک', 'group' => 'sms', 'type' => 'string', 'input' => 'text', 'default' => 'https://api.sms.ir/v1', 'help' => 'آدرس پایه API.'],
            ['key' => 'sms.sandbox', 'label' => 'حالت Sandbox پیامک', 'group' => 'sms', 'type' => 'boolean', 'input' => 'boolean', 'default' => false, 'help' => 'برای محیط تست بدون ارسال واقعی.'],
            ['key' => 'sms.default_company_name', 'label' => 'نام شرکت در پیامک', 'group' => 'sms', 'type' => 'string', 'input' => 'text', 'default' => 'یدا', 'help' => 'مقدار پیش‌فرض متغیر company_name.'],
            ['key' => 'sms.default_contact_phone', 'label' => 'شماره تماس در پیامک', 'group' => 'sms', 'type' => 'string', 'input' => 'text', 'default' => '02100000000', 'help' => 'مقدار پیش‌فرض متغیر contact_phone.'],
            ['key' => 'sms.retry_attempts', 'label' => 'تعداد تلاش مجدد ارسال پیامک', 'group' => 'sms', 'type' => 'integer', 'input' => 'number', 'default' => 2, 'help' => 'تعداد تلاش مجدد Job ارسال پیامک.'],
            ['key' => 'sms.queue_name', 'label' => 'نام صف پیامک', 'group' => 'sms', 'type' => 'string', 'input' => 'text', 'default' => 'default', 'help' => 'نام صفی که Job پیامک در آن اجرا می‌شود.'],

            // Landing
            ['key' => 'landing.hero_title', 'label' => 'تیتر اصلی لندینگ', 'group' => 'landing', 'type' => 'string', 'input' => 'textarea', 'default' => 'به تیم‌هایی بپیوند که رشد را واقعی می‌سازند', 'help' => 'تیتر اصلی بالای صفحه careers.'],
            ['key' => 'landing.hero_subtitle', 'label' => 'متن زیر تیتر لندینگ', 'group' => 'landing', 'type' => 'string', 'input' => 'textarea', 'default' => 'در یادا روی پروژه‌های واقعی کار می‌کنیم، مسئولیت می‌گیریم و با داده تصمیم می‌گیریم.', 'help' => 'متن توضیحی Hero.'],
            ['key' => 'landing.logo_url', 'label' => 'آدرس لوگوی لندینگ', 'group' => 'landing', 'type' => 'string', 'input' => 'text', 'default' => '/assets/brand/yadak-shop-logo.svg', 'help' => 'مسیر لوگوی برند در هدر و هیرو.'],
            ['key' => 'landing.og_image', 'label' => 'تصویر OG لندینگ', 'group' => 'landing', 'type' => 'string', 'input' => 'text', 'default' => '', 'help' => 'تصویر اشتراک‌گذاری شبکه‌های اجتماعی.'],
            ['key' => 'landing.show_salary', 'label' => 'نمایش حقوق در لندینگ', 'group' => 'landing', 'type' => 'boolean', 'input' => 'boolean', 'default' => false, 'help' => 'نمایش بازه حقوق در کارت موقعیت‌ها.'],
            ['key' => 'landing.show_faq', 'label' => 'نمایش بخش FAQ', 'group' => 'landing', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'نمایش/عدم نمایش سوالات متداول.'],
            ['key' => 'landing.gallery_enabled', 'label' => 'فعال بودن گالری', 'group' => 'landing', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'نمایش یا عدم نمایش اسلایدر گالری.'],
            ['key' => 'landing.gallery_title', 'label' => 'عنوان گالری', 'group' => 'landing', 'type' => 'string', 'input' => 'text', 'default' => 'تصویر زندگی کاری در یادا', 'help' => 'عنوان نمایشی گالری.'],
            ['key' => 'landing.gallery_slides', 'label' => 'اسلایدهای گالری', 'group' => 'landing', 'type' => 'json', 'input' => 'gallery', 'default' => [], 'help' => 'مدیریت تصاویر اسلایدر و متن جایگزین.'],

            // Process
            ['key' => 'process.require_note_on_reject', 'label' => 'الزام توضیح هنگام رد رزومه', 'group' => 'process', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'برای کنترل کیفیت رد رزومه.'],
            ['key' => 'process.allow_force_transition', 'label' => 'اجازه انتقال خارج از Transition map', 'group' => 'process', 'type' => 'boolean', 'input' => 'boolean', 'default' => false, 'help' => 'پیشنهاد: غیرفعال بماند.'],

            // File
            ['key' => 'upload.max_resume_size_kb', 'label' => 'حداکثر حجم رزومه (KB)', 'group' => 'file', 'type' => 'integer', 'input' => 'number', 'default' => 10240, 'help' => 'حداکثر حجم فایل رزومه قابل آپلود.'],
            ['key' => 'upload.allowed_resume_extensions', 'label' => 'پسوندهای مجاز رزومه', 'group' => 'file', 'type' => 'string', 'input' => 'text', 'default' => 'pdf', 'help' => 'با کاما جدا شوند.'],

            // Notifications
            ['key' => 'notifications.department_manager_on_new_application', 'label' => 'اعلان رزومه جدید برای مدیر دپارتمان', 'group' => 'notifications', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'ثبت اعلان در Activity Log.'],
            ['key' => 'notifications.interviewer_on_schedule', 'label' => 'اعلان زمان‌بندی برای مصاحبه‌گر', 'group' => 'notifications', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'ثبت اعلان در Activity Log.'],
            ['key' => 'notifications.notify_hr_on_sms_failed', 'label' => 'اعلان خطای پیامک به تیم HR', 'group' => 'notifications', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'ثبت خطا در Activity Log.'],

            // Security
            ['key' => 'security.force_https_for_public_forms', 'label' => 'اجبار HTTPS برای فرم‌های عمومی', 'group' => 'security', 'type' => 'boolean', 'input' => 'boolean', 'default' => false, 'help' => 'در محیط Production توصیه می‌شود فعال باشد.'],
            ['key' => 'security.block_public_resume_download', 'label' => 'جلوگیری از دانلود عمومی رزومه', 'group' => 'security', 'type' => 'boolean', 'input' => 'boolean', 'default' => true, 'help' => 'رزومه‌ها فقط از پنل قابل دانلود باشند.'],
        ]);
    }

    private function normalizeValueByType(mixed $value, string $type): mixed
    {
        if ($type !== 'json') {
            return $value;
        }

        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }
}
