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
    $companyName = $hrmSettings['company.name'] ?? config('app.name', 'Brand');
    $logoSetting = (string) ($hrmSettings['landing.logo_url'] ?? '/assets/brand/yadak-shop-logo.svg');
    $logoUrl = \Illuminate\Support\Str::startsWith($logoSetting, ['http://', 'https://']) ? $logoSetting : asset(ltrim($logoSetting, '/'));
    $employmentLabel = $employmentTypes[$jobPosition->employment_type] ?? $jobPosition->employment_type;
    $workModeLabel = $workModes[$jobPosition->work_mode] ?? $jobPosition->work_mode;

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
                'addressLocality' => 'Tehran',
            ],
        ],
        'url' => route('careers.jobs.show', $jobPosition),
    ];
@endphp

<div x-data="careersPage({ hasAnyJobs: false })" x-init="init()" class="ys-page">
    @include('careers.partials.header')

    <section class="ys-section">
        <div class="ys-container space-y-6">
            <div class="rounded-[30px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:p-9">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <a href="{{ route('careers.index') }}#jobs" class="inline-flex items-center gap-1 text-xs font-bold text-blue-900 dark:text-blue-300">
                            <span>بازگشت به لیست فرصت‌ها</span>
                            <span aria-hidden="true">←</span>
                        </a>
                        <h1 class="mt-3 text-3xl font-black leading-tight text-slate-900 dark:text-slate-100 lg:text-4xl">{{ $jobPosition->title }}</h1>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $jobPosition->department?->name ?? 'بدون دپارتمان' }} • تهران</p>
                    </div>
                    <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="h-12 w-auto" loading="lazy">
                </div>

                <div class="mt-5 flex flex-wrap gap-2 text-xs font-bold">
                    <span class="rounded-full bg-red-100 px-3 py-1 text-red-700 dark:bg-red-900/35 dark:text-red-200">{{ $employmentLabel }}</span>
                    <span class="rounded-full bg-blue-100 px-3 py-1 text-blue-800 dark:bg-blue-900/35 dark:text-blue-200">{{ $workModeLabel }}</span>
                    @if($jobPosition->is_salary_visible_public && $jobPosition->salary_min && $jobPosition->salary_max)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700 dark:bg-emerald-900/35 dark:text-emerald-200">{{ number_format($jobPosition->salary_min) }} تا {{ number_format($jobPosition->salary_max) }} {{ $jobPosition->salary_currency }}</span>
                    @endif
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800 lg:col-span-2">
                        <h2 class="text-lg font-black text-slate-900 dark:text-slate-100">شرح موقعیت</h2>
                        @if(!empty($jobPosition->description))
                            <div class="mt-3 ys-rich-content text-sm leading-7 text-slate-700 dark:text-slate-300">{!! $jobPosition->description !!}</div>
                        @else
                            <p class="mt-3 text-sm leading-7 text-slate-700 dark:text-slate-300">توضیحی برای این موقعیت ثبت نشده است.</p>
                        @endif
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                        <h2 class="text-lg font-black text-slate-900 dark:text-slate-100">نیازمندی‌ها</h2>
                        @if(!empty($jobPosition->requirements))
                            <div class="mt-3 ys-rich-content text-sm leading-7 text-slate-700 dark:text-slate-300">{!! $jobPosition->requirements !!}</div>
                        @else
                            <p class="mt-3 text-sm leading-7 text-slate-700 dark:text-slate-300">نیازمندی خاصی ثبت نشده است.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div id="general-apply" class="ys-general-apply">
                <div>
                    <span class="ys-kicker">درخواست برای این موقعیت</span>
                    <h2>ارسال رزومه برای {{ $jobPosition->title }}</h2>
                    <p>فرم زیر را کامل کنید تا تیم منابع انسانی پس از بررسی با شما در ارتباط باشد.</p>
                    <figure class="ys-general-apply__visual mt-4">
                        <img src="{{ asset('assets/illustrations/resume-journey.svg') }}" alt="فرآیند حرفه‌ای بررسی رزومه" loading="lazy">
                    </figure>
                </div>

                <form class="ys-form" method="post" enctype="multipart/form-data" action="{{ route('careers.jobs.apply', $jobPosition) }}" novalidate @submit="handleGeneralSubmit($event)">
                    @csrf
                    <div class="ys-form-feedback ys-form-feedback--success" x-show="submitState === 'success'" x-cloak>
                        <p x-text="submitMessage"></p>
                    </div>
                    <div class="ys-form-feedback ys-form-feedback--error" x-show="submitState === 'error'" x-cloak>
                        <p x-text="submitMessage"></p>
                        <ul x-show="submitErrors.length" class="ys-form-feedback__list">
                            <template x-for="(item, idx) in submitErrors" :key="idx">
                                <li x-text="item"></li>
                            </template>
                        </ul>
                    </div>
                    <div class="ys-form-grid">
                        <label>
                            <span>نام و نام خانوادگی *</span>
                            <input name="full_name" value="{{ old('full_name') }}" required>
                            @error('full_name')<em>{{ $message }}</em>@enderror
                        </label>
                        <label>
                            <span>موبایل *</span>
                            <input name="mobile" value="{{ old('mobile') }}" required>
                            @error('mobile')<em>{{ $message }}</em>@enderror
                        </label>
                        <label>
                            <span>ایمیل</span>
                            <input name="email" type="email" value="{{ old('email') }}">
                            @error('email')<em>{{ $message }}</em>@enderror
                        </label>
                        <label>
                            <span>حقوق مورد انتظار (اختیاری)</span>
                            <input name="expected_salary" type="number" min="0" value="{{ old('expected_salary') }}" placeholder="مثال: 35000000">
                            @error('expected_salary')<em>{{ $message }}</em>@enderror
                        </label>
                        <label class="ys-form-grid__full">
                            <span>توضیح کوتاه</span>
                            <textarea name="cover_letter" rows="4">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')<em>{{ $message }}</em>@enderror
                        </label>
                        <label class="ys-form-grid__full ys-upload">
                            <span>فایل رزومه (فقط PDF) *</span>
                            <input id="resume" type="file" name="resume" accept=".pdf,application/pdf" required @change="setResumeFileName($event)">
                            <div class="ys-upload__box">
                                <strong x-text="resumeFileName || 'فایل رزومه را به‌صورت PDF انتخاب کنید'"></strong>
                            </div>
                            <em x-show="resumeClientError" x-text="resumeClientError" x-cloak></em>
                            @error('resume')<em>{{ $message }}</em>@enderror
                        </label>

                        @foreach($jobPosition->questions as $question)
                            <label class="ys-form-grid__full">
                                <span>{{ $question->question }}</span>
                                <input name="answers[{{ $question->id }}]" value="{{ old('answers.' . $question->id) }}">
                            </label>
                        @endforeach

                        <div class="ys-form-grid__full ys-captcha">
                            <span>کد امنیتی *</span>
                            <div class="ys-captcha__row">
                                <img :src="captchaSrc" alt="کد امنیتی" class="ys-captcha__image">
                                <input name="captcha" inputmode="numeric" pattern="[0-9۰-۹٠-٩]{5}" maxlength="5" placeholder="کد ۵ رقمی" required>
                                <button type="button" class="ys-captcha__refresh" @click.stop="refreshCaptcha()" aria-label="بارگذاری مجدد کد امنیتی" title="بارگذاری مجدد کد امنیتی">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path d="M20.33 8.67A8 8 0 1 0 20 13h-2.5" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                        <path d="M20 5v6h-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                    </svg>
                                </button>
                            </div>
                            <small>کد عددی داخل تصویر را وارد کنید.</small>
                            @error('captcha')<em>{{ $message }}</em>@enderror
                        </div>
                    </div>

                    <button class="ys-btn ys-btn--primary ys-form__submit" :disabled="submitting" :class="submitting ? 'is-loading' : ''">
                        <span x-show="!submitting">ثبت درخواست</span>
                        <span x-show="submitting">در حال ارسال...</span>
                    </button>
                </form>
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
