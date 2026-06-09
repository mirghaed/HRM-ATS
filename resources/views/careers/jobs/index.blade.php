@extends('layouts.public', [
    'title' => 'همه فرصت‌های شغلی | ' . ($hrmSettings['company.name'] ?? config('app.name', 'Brand')),
    'description' => 'لیست کامل فرصت‌های شغلی فعال در ' . ($hrmSettings['company.name'] ?? config('app.name', 'Brand')) . '. موقعیت مناسب خود را پیدا کنید و رزومه ارسال کنید.',
])

@push('meta')
    <meta property="og:title" content="همه فرصت‌های شغلی | {{ $hrmSettings['company.name'] ?? config('app.name', 'Brand') }}">
    <meta property="og:description" content="لیست کامل فرصت‌های شغلی فعال. موقعیت مناسب خود را پیدا کنید و رزومه ارسال کنید.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('careers.jobs.index') }}">
    <link rel="canonical" href="{{ route('careers.jobs.index') }}">
@endpush

@section('content')
@php
    $logoUrl = $logoUrl ?? app(\App\Services\HRM\HrmSettingService::class)->landingLogoUrl('landing.logo_url', '/assets/brand/yadak-shop-logo.svg');
    $headerAnchorBase = route('careers.index');
@endphp

<div x-data="careersPage({ hasAnyJobs: {{ $jobPositions->isNotEmpty() ? 'true' : 'false' }}, logoUrl: @js($logoUrl), logoUrlDark: @js($logoUrlDark ?? '') })" x-init="init()" class="ys-page">
    @include('careers.partials.header')

    <section class="ys-section ys-section--jobs-page">
        <div class="ys-container">
            <nav class="ys-jobs-page__breadcrumb" aria-label="مسیر صفحه">
                <a href="{{ route('careers.index') }}">صفحه اصلی</a>
                <span aria-hidden="true">/</span>
                <span>فرصت‌های شغلی</span>
            </nav>
            <h1 class="ys-jobs-page__title">همه فرصت‌های شغلی باز</h1>
            <p class="ys-jobs-page__subtitle">{{ number_format($jobPositions->count()) }} موقعیت فعال برای همکاری</p>
        </div>
    </section>

    @include('careers.partials.jobs', [
        'jobsSectionTitle' => 'جستجو در بین همه فرصت‌ها',
    ])

    @include('careers.partials.footer')
</div>

@include('careers.partials.scripts')
@endsection
