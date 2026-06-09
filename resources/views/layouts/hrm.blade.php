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

                <div class="ys-admin-header__actions">
                    @if(auth()->user()?->can('applications.view_all') || auth()->user()?->can('applications.view_department') || auth()->user()?->can('applications.view_assigned'))
                        <form class="ys-admin-search" method="get" action="{{ route('hrm.applications.index') }}">
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجو در رزومه‌ها، موقعیت‌ها...">
                        </form>
                    @endif

                    @can('settings.view')
                        <a href="{{ route('hrm.settings.edit') }}" class="ys-icon-btn" aria-label="تنظیمات">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" stroke="currentColor" stroke-width="1.8"/><path d="M19.4 13a7.7 7.7 0 0 0 .1-2l2-1.5-2-3.5-2.3 1a7.5 7.5 0 0 0-1.7-1L15 2h-6l-.5 3.5a7.5 7.5 0 0 0-1.7 1l-2.3-1-2 3.5 2 1.5a7.7 7.7 0 0 0 .1 2l-2 1.5 2 3.5 2.3-1a7.5 7.5 0 0 0 1.7 1L9 22h6l.5-3.5a7.5 7.5 0 0 0 1.7-1l2.3 1 2-3.5-2-1.5Z" stroke="currentColor" stroke-width="1.8"/></svg>
                        </a>
                    @endcan

                    <form method="post" action="{{ route('logout') }}" class="ys-admin-logout">
                        @csrf
                        <button type="submit" class="ys-btn ys-btn--danger">خروج</button>
                    </form>
                </div>
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
