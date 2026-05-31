@extends('layouts.public', [
    'title' => 'فرصت‌های شغلی و همکاری ' . ($hrmSettings['company.name'] ?? config('app.name', 'Brand')),
    'description' => 'فرصت‌های شغلی و همکاری ' . ($hrmSettings['company.name'] ?? config('app.name', 'Brand')) . ' را ببینید و رزومه خود را برای تیم‌های فروش، نرم‌افزار، عملیات و پشتیبانی ارسال کنید.',
])

@push('meta')
    <meta property="og:title" content="فرصت‌های شغلی و همکاری {{ $hrmSettings['company.name'] ?? config('app.name', 'Brand') }}">
    <meta property="og:description" content="فرصت‌های شغلی و همکاری {{ $hrmSettings['company.name'] ?? config('app.name', 'Brand') }} را ببینید و رزومه خود را برای تیم‌های مختلف ارسال کنید.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('careers.index') }}">
    <link rel="canonical" href="{{ route('careers.index') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="فرصت‌های شغلی و همکاری {{ $hrmSettings['company.name'] ?? config('app.name', 'Brand') }}">
    <meta name="twitter:description" content="فرصت‌های شغلی و همکاری {{ $hrmSettings['company.name'] ?? config('app.name', 'Brand') }} را ببینید و رزومه خود را برای تیم‌های مختلف ارسال کنید.">
    @if(!empty($hrmSettings['landing.og_image']))
        <meta property="og:image" content="{{ $hrmSettings['landing.og_image'] }}">
        <meta name="twitter:image" content="{{ $hrmSettings['landing.og_image'] }}">
    @endif
    <link rel="preload" as="image" href="{{ asset('assets/careers/illustrations/hero-teamwork.webp') }}" type="image/webp" fetchpriority="high">
@endpush

@section('content')
@php
    $companyName = $hrmSettings['company.name'] ?? config('app.name', 'Brand');

    $sectionsByKey = $sections->keyBy('key');
    $heroSection = $sectionsByKey->get('hero');
    $whySection = $sectionsByKey->get('why_yadak') ?? $sectionsByKey->get('benefits');
    $cultureSection = $sectionsByKey->get('culture') ?? $sectionsByKey->get('story');
    $growthSection = $sectionsByKey->get('growth_path') ?? $sectionsByKey->get('growth');
    $processSection = $sectionsByKey->get('process');
    $faqSection = $sectionsByKey->get('faq');

    $heroBadge = data_get($heroSection, 'payload.badge', 'فرصت‌های شغلی و همکاری در ' . $companyName);
    $heroTitle = $hrmSettings['landing.hero_title'] ?? data_get($heroSection, 'title', 'با هم مسیر آینده بازار قطعات خودرو را حرفه‌ای‌تر می‌سازیم');
    $heroSubtitle = $hrmSettings['landing.hero_subtitle'] ?? data_get($heroSection, 'subtitle', 'در ' . $companyName . ' تیم‌های فروش، تکنولوژی، عملیات و پشتیبانی کنار هم کار می‌کنند تا تجربه‌ای سریع‌تر، دقیق‌تر و قابل اعتمادتر برای مشتری ساخته شود.');

    $hasReliableStats = ((int) ($stats['open_jobs'] ?? 0)
        + (int) ($stats['active_departments'] ?? 0)
        + (int) ($stats['applications_total'] ?? 0)
        + (int) ($stats['hired_total'] ?? 0)) > 0;

    $benefitItems = data_get($whySection, 'payload.items', [
        ['title' => 'اثرگذاری واقعی', 'text' => 'روی پروژه‌هایی کار می‌کنی که خروجی آن مستقیم در کسب‌وکار دیده می‌شود.'],
        ['title' => 'رشد شغلی شفاف', 'text' => 'بازخورد منظم، مسیر ارتقا و ارزیابی دوره‌ای روشن است.'],
        ['title' => 'تیم حرفه‌ای', 'text' => 'در کنار تیم‌های متخصص فروش، عملیات و فناوری تجربه چندبعدی کسب می‌کنی.'],
        ['title' => 'یادگیری مستمر', 'text' => 'فرهنگ کاری ما بر یادگیری، همکاری و بهبود مداوم بنا شده است.'],
    ]);

    $cultureTitle = data_get($cultureSection, 'title', 'فرهنگ همکاری در ' . $companyName);
    $cultureSubtitle = data_get($cultureSection, 'subtitle', 'ترکیب نظم عملیاتی و سرعت اجرای تکنولوژی');
    $cultureContent = data_get($cultureSection, 'content', 'در ' . $companyName . ' شفافیت، مسئولیت‌پذیری و کار تیمی پایه‌های اصلی همکاری هستند. هر نقش مالک خروجی خود است و با هماهنگی بین تیم‌ها، نتیجه‌ای بهتر برای مشتری ساخته می‌شود.');

    $growthItems = data_get($growthSection, 'payload.items', [
        ['step' => '01', 'title' => 'Onboarding هدفمند', 'desc' => 'با مسیر مشخص، سریع با نقش و تیم هم‌راستا می‌شوی.'],
        ['step' => '02', 'title' => 'مالکیت خروجی', 'desc' => 'از روزهای اول روی خروجی واقعی اثر می‌گذاری.'],
        ['step' => '03', 'title' => 'بازخورد مستمر', 'desc' => 'بازخورد منظم، منتورینگ و ارزیابی دوره‌ای داری.'],
        ['step' => '04', 'title' => 'مسیر ارتقا', 'desc' => 'بر اساس عملکرد، مسیر رشد شغلی روشن و قابل پیگیری است.'],
    ]);

    $processItems = data_get($processSection, 'payload.items', [
        ['title' => 'ارسال رزومه', 'desc' => 'رزومه‌ات را برای موقعیت مدنظر ارسال کن.'],
        ['title' => 'بررسی اولیه', 'desc' => 'تیم جذب در کوتاه‌ترین زمان بررسی اولیه انجام می‌دهد.'],
        ['title' => 'مصاحبه تخصصی', 'desc' => 'گفت‌وگو با تیم مرتبط برای ارزیابی دقیق.'],
        ['title' => 'پیشنهاد همکاری', 'desc' => 'در صورت تایید نهایی، پیشنهاد رسمی همکاری ارسال می‌شود.'],
    ]);

    $faqItems = data_get($faqSection, 'payload.items', [
        ['q' => 'بعد از ارسال رزومه چه اتفاقی می‌افتد؟', 'a' => 'رزومه شما بررسی می‌شود و نتیجه اولیه اطلاع‌رسانی خواهد شد.'],
        ['q' => 'اگر موقعیت مناسب نبود، می‌توانم رزومه عمومی بفرستم؟', 'a' => 'بله، از طریق فرم رزومه عمومی پایین صفحه اقدام کنید.'],
        ['q' => 'مصاحبه‌ها حضوری هستند یا آنلاین؟', 'a' => 'بسته به نقش و نیاز تیم، حضوری یا آنلاین برگزار می‌شود.'],
        ['q' => 'فرمت فایل رزومه باید چگونه باشد؟', 'a' => 'فقط فایل PDF قابل بارگذاری است.'],
    ]);

    $logoSetting = (string) ($hrmSettings['landing.logo_url'] ?? '/assets/brand/yadak-shop-logo.svg');
    $logoUrl = \Illuminate\Support\Str::startsWith($logoSetting, ['http://', 'https://']) ? $logoSetting : asset(ltrim($logoSetting, '/'));

    $companySiteUrl = (string) ($hrmSettings['company.website'] ?? config('app.url'));
    $normalizeJobTitle = static function (?string $title): string {
        $value = trim((string) $title);
        if ($value === '' || strcasecmp($value, 'laravel dev') === 0) {
            return 'برنامه‌نویس Laravel';
        }

        return $value;
    };

    $jobPostingSchema = $jobPositions->map(function ($job) use ($hrmSettings, $logoUrl, $companySiteUrl, $normalizeJobTitle) {
        $employmentMap = [
            'full_time' => 'FULL_TIME',
            'part_time' => 'PART_TIME',
            'project_based' => 'CONTRACTOR',
            'internship' => 'INTERN',
            'contract' => 'CONTRACTOR',
        ];

        $openedAt = $job->opened_at ?? $job->created_at;
        $validThrough = $openedAt ? $openedAt->copy()->addDays(60)->toDateString() : null;

        $jobTitle = $normalizeJobTitle($job->title);
        $description = strip_tags((string) ($job->description ?: $job->requirements ?: 'فرصت شغلی برای همکاری در تیم‌های مختلف ' . ($hrmSettings['company.name'] ?? config('app.name', 'Brand'))));

        return [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $jobTitle,
            'description' => $description,
            'datePosted' => optional($openedAt)->toDateString(),
            'validThrough' => $validThrough,
            'employmentType' => $employmentMap[$job->employment_type] ?? 'FULL_TIME',
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $hrmSettings['company.name'] ?? config('app.name', 'Brand'),
                'sameAs' => $companySiteUrl,
                'logo' => $logoUrl,
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => 'IR',
                    'addressLocality' => 'Tehran',
                ],
            ],
            'url' => route('careers.jobs.show', $job),
            'baseSalary' => ($job->is_salary_visible_public && $job->salary_min && $job->salary_max)
                ? [
                    '@type' => 'MonetaryAmount',
                    'currency' => $job->salary_currency ?: 'IRR',
                    'value' => [
                        '@type' => 'QuantitativeValue',
                        'minValue' => (int) $job->salary_min,
                        'maxValue' => (int) $job->salary_max,
                        'unitText' => 'MONTH',
                    ],
                ]
                : null,
        ];
    })->map(static fn (array $item) => array_filter($item, static fn ($value) => ! is_null($value)))
        ->values()
        ->all();
@endphp

<div x-data="careersPage({ hasAnyJobs: {{ $jobPositions->isNotEmpty() ? 'true' : 'false' }} })" x-init="init()" class="ys-page">
    @include('careers.partials.header')
    @include('careers.partials.hero')
    @include('careers.partials.gallery')
    @include('careers.partials.jobs')
    @include('careers.partials.departments')
    @include('careers.partials.why')
    @include('careers.partials.culture')
    @include('careers.partials.growth')
    @include('careers.partials.process')
    @include('careers.partials.faq')
    @include('careers.partials.general-apply')
    @include('careers.partials.footer')
</div>

@include('careers.partials.scripts')
@endsection

@push('structured-data')
    <script type="application/ld+json">@json($jobPostingSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
@endpush
