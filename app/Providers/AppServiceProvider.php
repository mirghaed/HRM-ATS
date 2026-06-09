<?php

namespace App\Providers;

use App\Services\HRM\HrmSettingService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->removeViteHotFileOutsideLocal();

        View::composer('*', function ($view): void {
            $view->with('hrmSettings', app(HrmSettingService::class)->all());
        });

        View::composer([
            'careers.index',
            'careers.jobs.index',
            'careers.show',
            'careers.partials.header',
            'careers.partials.hero',
            'careers.partials.departments',
            'careers.partials.why',
            'careers.partials.culture',
            'careers.partials.footer',
        ], function ($view): void {
            $settings = app(HrmSettingService::class);
            $companyName = (string) $settings->get('company.name', config('app.name', 'Brand'));
            $landingText = static fn (string $key, string $default) => $settings->landingText($key, $default, $companyName);
            $logoUrl = $settings->landingLogoUrl('landing.logo_url', '/assets/brand/yadak-shop-logo.svg');
            $logoUrlDark = $settings->landingLogoUrl('landing.logo_url_dark');

            $view->with([
                'companyName' => $companyName,
                'logoUrl' => $logoUrl,
                'logoUrlDark' => $logoUrlDark,
                'headerBrandText' => $landingText('landing.header_brand_text', 'فرصت‌های همکاری {company}'),
                'heroBadge' => $landingText('landing.hero_badge', 'جای تو در تیم {company} خالیه'),
                'heroTitle' => $landingText('landing.hero_title', 'به تیم‌هایی بپیوند که رشد را واقعی می‌سازند'),
                'heroSubtitle' => $landingText('landing.hero_subtitle', 'در {company} روی پروژه‌های واقعی کار می‌کنیم، مسئولیت می‌گیریم و با داده تصمیم می‌گیریم.'),
                'departmentsKicker' => $landingText('landing.departments_kicker', 'تیم‌ها و دپارتمان‌ها'),
                'departmentsTitle' => $landingText('landing.departments_title', 'تیم‌هایی که در {company} کنار هم کار می‌کنند'),
                'whyKicker' => $landingText('landing.why_kicker', 'چرا همکاری با ما'),
                'whyTitle' => $landingText('landing.why_title', 'چرا {company}؟'),
                'cultureKicker' => $landingText('landing.culture_kicker', 'ترکیب نظم عملیاتی و سرعت اجرای تکنولوژی'),
                'cultureTitle' => $landingText('landing.culture_title', 'فرهنگ همکاری در {company}'),
                'cultureContent' => $landingText('landing.culture_content', 'در {company} شفافیت، مسئولیت‌پذیری و کار تیمی پایه‌های اصلی همکاری هستند. هر نقش مالک خروجی خود است و با هماهنگی بین تیم‌ها، نتیجه‌ای بهتر برای مشتری ساخته می‌شود.'),
            ]);
        });
    }

    private function removeViteHotFileOutsideLocal(): void
    {
        if ($this->app->environment('local') && filter_var(env('VITE_DEV_SERVER', false), FILTER_VALIDATE_BOOL)) {
            return;
        }

        $hotPath = public_path('hot');

        if (is_file($hotPath)) {
            @unlink($hotPath);
        }
    }
}
