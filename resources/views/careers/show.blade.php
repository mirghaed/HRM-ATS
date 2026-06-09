@extends('layouts.public', [
    'title' => ($jobPosition->title ?: 'فرصت شغلی') . ' | فرصت‌های شغلی',
    'description' => \Illuminate\Support\Str::limit(strip_tags((string) ($jobPosition->description ?: $jobPosition->requirements ?: $jobPosition->title)), 155),
])

@push('meta')
    <meta property="og:title" content="{{ $jobPosition->title }} | {{ $hrmSettings['company.name'] ?? config('app.name', 'Brand') }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags((string) ($jobPosition->description ?: $jobPosition->requirements ?: $jobPosition->title)), 155) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('careers.jobs.show', $jobPosition) }}">
@endpush

@section('content')
@php
    $companyName = $companyName ?? ($hrmSettings['company.name'] ?? config('app.name', 'Brand'));
    $logoUrl = $logoUrl ?? app(\App\Services\HRM\HrmSettingService::class)->landingLogoUrl('landing.logo_url', '/assets/brand/yadak-shop-logo.svg');
    $employmentLabel = $employmentTypes[$jobPosition->employment_type] ?? $jobPosition->employment_type;
    $workModeLabel = $workModes[$jobPosition->work_mode] ?? $jobPosition->work_mode;
    $locationLabel = trim((string) ($jobPosition->location ?: 'تهران'));
    $departmentLabel = $jobPosition->department?->name;
    $companyIntro = $cultureContent ?? app(\App\Services\HRM\HrmSettingService::class)->landingText(
        'landing.culture_content',
        'در {company} شفافیت، مسئولیت‌پذیری و کار تیمی پایه‌های اصلی همکاری هستند.',
        $companyName
    );

    $salaryLabel = null;
    if ($jobPosition->is_salary_visible_public && $jobPosition->salary_min) {
        $currency = $jobPosition->salary_currency ?: 'تومان';
        if ($jobPosition->salary_max && $jobPosition->salary_max > $jobPosition->salary_min) {
            $salaryLabel = 'از ' . number_format($jobPosition->salary_min) . ' تا ' . number_format($jobPosition->salary_max) . ' ' . $currency;
        } else {
            $salaryLabel = 'از ' . number_format($jobPosition->salary_min) . ' ' . $currency;
        }
    }

    $jobBodyParts = array_values(array_filter([
        filled(trim(strip_tags((string) $jobPosition->description))) ? $jobPosition->description : null,
        filled(trim(strip_tags((string) $jobPosition->requirements))) ? $jobPosition->requirements : null,
    ]));

    $shareUrl = route('careers.jobs.show', $jobPosition);
    $shareTitle = $jobPosition->title . ' | ' . $companyName;

    $jobSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => $jobPosition->title,
        'description' => strip_tags((string) ($jobPosition->description ?: $jobPosition->requirements ?: $jobPosition->title)),
        'datePosted' => optional($jobPosition->opened_at ?? $jobPosition->created_at)->toDateString(),
        'employmentType' => match ($jobPosition->employment_type) {
            'full_time' => 'FULL_TIME',
            'part_time' => 'PART_TIME',
            'project_based', 'contract' => 'CONTRACTOR',
            'internship' => 'INTERN',
            default => 'FULL_TIME',
        },
        'hiringOrganization' => [
            '@type' => 'Organization',
            'name' => $companyName,
            'sameAs' => (string) ($hrmSettings['company.website'] ?? config('app.url')),
            'logo' => $logoUrl,
        ],
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'IR',
                'addressLocality' => $locationLabel,
            ],
        ],
        'url' => $shareUrl,
    ];
@endphp

<div x-data="careersPage({ hasAnyJobs: false, logoUrl: @js($logoUrl), logoUrlDark: @js($logoUrlDark ?? '') })" x-init="init()" class="ys-page">
    @include('careers.partials.header')

    <section class="ys-section ys-section--job-show">
        <div class="ys-container ys-job-view">
            <div class="ys-job-view__layout">
                <div class="ys-job-view__main">
                    <article class="ys-job-view__box">
                        <div class="ys-job-view__head">
                            <a href="{{ route('careers.index') }}#jobs" class="ys-job-view__back">
                                <span aria-hidden="true">←</span>
                                <span>بازگشت به لیست فرصت‌ها</span>
                            </a>
                            <h1 class="ys-job-view__title">{{ $jobPosition->title }}</h1>
                        </div>

                        <ul class="ys-job-view__info">
                            @if($departmentLabel)
                                <li class="ys-job-view__info-item">
                                    <h4 class="ys-job-view__info-label">دسته‌بندی شغلی</h4>
                                    <div class="ys-job-view__tags">
                                        <span class="ys-job-view__tag">{{ $departmentLabel }}</span>
                                    </div>
                                </li>
                            @endif
                            <li class="ys-job-view__info-item">
                                <h4 class="ys-job-view__info-label">موقعیت مکانی</h4>
                                <div class="ys-job-view__tags">
                                    <span class="ys-job-view__tag">{{ $locationLabel }}</span>
                                </div>
                            </li>
                            <li class="ys-job-view__info-item">
                                <h4 class="ys-job-view__info-label">نوع همکاری</h4>
                                <div class="ys-job-view__tags">
                                    <span class="ys-job-view__tag">{{ $employmentLabel }}</span>
                                </div>
                            </li>
                            <li class="ys-job-view__info-item">
                                <h4 class="ys-job-view__info-label">مدل کار</h4>
                                <div class="ys-job-view__tags">
                                    <span class="ys-job-view__tag">{{ $workModeLabel }}</span>
                                </div>
                            </li>
                            @if($salaryLabel)
                                <li class="ys-job-view__info-item">
                                    <h4 class="ys-job-view__info-label">حقوق</h4>
                                    <div class="ys-job-view__tags">
                                        <span class="ys-job-view__tag ys-job-view__tag--salary">{{ $salaryLabel }}</span>
                                    </div>
                                </li>
                            @endif
                        </ul>

                        <h4 class="ys-job-view__section-title">شرح موقعیت شغلی</h4>
                        @if($jobBodyParts !== [])
                            <div class="ys-job-view__body ys-rich-content">
                                @foreach($jobBodyParts as $part)
                                    {!! $part !!}
                                @endforeach
                            </div>
                        @else
                            <p class="ys-job-view__empty">توضیحی برای این موقعیت ثبت نشده است.</p>
                        @endif

                        <h4 class="ys-job-view__section-title">معرفی شرکت</h4>
                        <div class="ys-job-view__body ys-job-view__company">
                            {!! nl2br(e($companyIntro)) !!}
                        </div>

                        @if($jobPosition->requiredSkills->isNotEmpty())
                            <ul class="ys-job-view__info ys-job-view__info--bottom">
                                <li class="ys-job-view__info-item ys-job-view__info-item--wide">
                                    <h4 class="ys-job-view__info-label">مهارت‌های مورد نیاز</h4>
                                    <div class="ys-job-view__tags">
                                        @foreach($jobPosition->requiredSkills as $skill)
                                            <span class="ys-job-view__tag">{{ $skill->name }}</span>
                                        @endforeach
                                    </div>
                                </li>
                            </ul>
                        @endif
                    </article>

                    @if($similarJobs->isNotEmpty())
                        <section class="ys-job-view__similar" id="similar-jobs">
                            <div class="ys-job-view__similar-head">
                                <h3 class="ys-job-view__similar-title">مشاغل مشابه</h3>
                                <a href="{{ route('careers.index') }}#jobs" class="ys-job-view__similar-link">مشاهده همه فرصت‌ها</a>
                            </div>
                            <div class="ys-job-view__similar-box">
                                <ul class="ys-job-view__similar-list">
                                    @foreach($similarJobs as $similarJob)
                                        @php
                                            $similarEmployment = $employmentTypes[$similarJob->employment_type] ?? $similarJob->employment_type;
                                            $similarWorkMode = $workModes[$similarJob->work_mode] ?? $similarJob->work_mode;
                                            $similarSalary = null;
                                            if ($similarJob->is_salary_visible_public && $similarJob->salary_min) {
                                                $similarSalary = $similarJob->salary_max && $similarJob->salary_max > $similarJob->salary_min
                                                    ? 'از ' . number_format($similarJob->salary_min) . ' تا ' . number_format($similarJob->salary_max) . ' ' . ($similarJob->salary_currency ?: 'تومان')
                                                    : 'از ' . number_format($similarJob->salary_min) . ' ' . ($similarJob->salary_currency ?: 'تومان');
                                            }
                                            $postedAt = $similarJob->opened_at ?? $similarJob->created_at;
                                        @endphp
                                        <li class="ys-job-view__similar-item">
                                            <div class="ys-job-view__similar-content">
                                                <h4 class="ys-job-view__similar-job-title">
                                                    <a href="{{ route('careers.jobs.show', $similarJob) }}">{{ $similarJob->title }}</a>
                                                </h4>
                                                @if($postedAt)
                                                    <span class="ys-job-view__similar-date">{{ $postedAt->locale('fa')->diffForHumans() }}</span>
                                                @endif
                                                <ul class="ys-job-view__similar-meta">
                                                    <li>{{ $companyName }}</li>
                                                    <li>{{ $similarJob->location ?: 'تهران' }}</li>
                                                    <li>{{ $similarEmployment }} • {{ $similarWorkMode }}@if($similarSalary) ({{ $similarSalary }})@endif</li>
                                                </ul>
                                            </div>
                                            <a href="{{ route('careers.jobs.show', $similarJob) }}" class="ys-job-view__similar-action">مشاهده</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </section>
                    @endif
                </div>

                <aside class="ys-job-view__sidebar" x-data="{ shareOpen: false, copied: false }">
                    @include('careers.partials.job-apply-form')

                    <div class="ys-job-view__share">
                        <button type="button" class="ys-job-view__share-toggle" @click="shareOpen = !shareOpen">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path d="M8.59 13.51 15.42 17.49M15.41 6.51 8.59 10.49M21 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM9 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm12 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"></path>
                            </svg>
                            اشتراک‌گذاری فرصت شغلی
                        </button>
                        <div class="ys-job-view__share-panel" x-show="shareOpen" x-cloak>
                            <ul class="ys-job-view__share-links">
                                <li>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode($shareUrl) }}&amp;title={{ urlencode($shareTitle) }}" target="_blank" rel="nofollow noopener" aria-label="اشتراک در لینکدین">LinkedIn</a>
                                </li>
                                <li>
                                    <a href="https://t.me/share/url?url={{ urlencode($shareUrl) }}&amp;text={{ urlencode($shareTitle) }}" target="_blank" rel="nofollow noopener" aria-label="اشتراک در تلگرام">Telegram</a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&amp;text={{ urlencode($shareTitle) }}" target="_blank" rel="nofollow noopener" aria-label="اشتراک در ایکس">X</a>
                                </li>
                            </ul>
                            <button type="button" class="ys-job-view__share-copy" @click="navigator.clipboard.writeText(@js($shareUrl)); copied = true; setTimeout(() => copied = false, 2000)">
                                <span x-show="!copied">{{ $shareUrl }}</span>
                                <span x-show="copied" x-cloak>لینک کپی شد</span>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('careers.partials.footer')
</div>

@include('careers.partials.scripts')
@endsection

@push('structured-data')
    <script type="application/ld+json">@json($jobSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
@endpush
