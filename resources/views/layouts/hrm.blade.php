<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($hrmSettings['panel.title'] ?? 'پنل HRM') }}</title>
    @include('layouts.partials.vite')
    @include('layouts.partials.fonts')
    @stack('head')
</head>
<body class="hrm-panel text-slate-800">
@php
    $panelTitle = $hrmSettings['panel.title'] ?? 'سامانه جذب نیرو';
    $panelSubtitle = $hrmSettings['panel.subtitle'] ?? 'HRM / ATS';
    $companyName = $hrmSettings['company.name'] ?? 'یادا';
@endphp
<div class="ys-admin-shell">
    <header class="ys-admin-header">
        <div class="ys-admin-header__inner">
            <div class="ys-admin-header__top">
                <div class="ys-admin-brand">
                    <span class="ys-admin-brand__kicker">{{ $panelSubtitle }}</span>
                    <h1>{{ $companyName }} <small>{{ $panelTitle }}</small></h1>
                </div>

                <form method="post" action="{{ route('logout') }}" class="ys-admin-logout">
                    @csrf
                    <button type="submit" class="ys-btn ys-btn--primary">خروج</button>
                </form>
            </div>

            <div class="ys-admin-nav-wrap">
                <nav class="ys-admin-nav">
                    @can('dashboard.view')
                        <a class="ys-admin-nav__link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">داشبورد</a>
                    @endcan
                    @can('departments.view')
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.departments.*') ? 'is-active' : '' }}" href="{{ route('hrm.departments.index') }}">دپارتمان‌ها</a>
                    @endcan
                    @can('job_positions.view')
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.job-positions.*') ? 'is-active' : '' }}" href="{{ route('hrm.job-positions.index') }}">موقعیت‌ها</a>
                    @endcan
                    @if(auth()->user()?->can('applications.view_all') || auth()->user()?->can('applications.view_department') || auth()->user()?->can('applications.view_assigned'))
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.applications.*') ? 'is-active' : '' }}" href="{{ route('hrm.applications.index') }}">رزومه‌ها</a>
                    @endif
                    @if(auth()->user()?->can('interviews.view_all') || auth()->user()?->can('interviews.view_department') || auth()->user()?->can('interviews.view_own'))
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.interviews.*') ? 'is-active' : '' }}" href="{{ route('hrm.interviews.index') }}">مصاحبه‌ها</a>
                    @endif
                    @can('settings.view')
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.recruitment-statuses.*') ? 'is-active' : '' }}" href="{{ route('hrm.recruitment-statuses.index') }}">وضعیت‌ها</a>
                    @endcan
                    @can('settings.view')
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.rejection-reasons.*') ? 'is-active' : '' }}" href="{{ route('hrm.rejection-reasons.index') }}">دلایل رد</a>
                    @endcan
                    @can('application_sources.view')
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.source-connectors.*') ? 'is-active' : '' }}" href="{{ route('hrm.source-connectors.index') }}">Connectorها</a>
                    @endcan
                    @can('reports.view')
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.reports.*') ? 'is-active' : '' }}" href="{{ route('hrm.reports.index') }}">گزارش</a>
                    @endcan
                    @can('settings.view')
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.settings.*') ? 'is-active' : '' }}" href="{{ route('hrm.settings.edit') }}">تنظیمات</a>
                    @endcan
                    @can('settings.manage')
                        <a class="ys-admin-nav__link {{ request()->routeIs('hrm.users.*') ? 'is-active' : '' }}" href="{{ route('hrm.users.index') }}">کاربران</a>
                    @endcan
                </nav>
            </div>
        </div>
    </header>

    <main class="ys-admin-main">
        @if(session('success'))
            <div class="ys-admin-alert ys-admin-alert--success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="ys-admin-alert ys-admin-alert--error">
                <p class="font-semibold">خطا در اعتبارسنجی:</p>
                <ul class="mt-2 list-disc pr-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>
</div>
@stack('scripts')
</body>
</html>
