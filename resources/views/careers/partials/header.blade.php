@php
    $headerAnchorBase = $headerAnchorBase ?? '';
@endphp

<header class="ys-header" :class="scrolled ? 'ys-header--scrolled' : ''">
    <div class="ys-container ys-header__inner">
        <a href="{{ route('careers.index') }}" class="ys-brand" aria-label="صفحه فرصت‌های شغلی">
            <img :src="activeLogoUrl" src="{{ $logoUrl }}" alt="لوگوی {{ $companyName }}" class="ys-brand__logo" loading="eager" fetchpriority="high">
            <span class="ys-brand__text">{{ $headerBrandText }}</span>
        </a>

        <nav class="ys-nav" aria-label="ناوبری اصلی">
            <a href="{{ $headerAnchorBase }}#jobs">فرصت‌ها</a>
            <a href="{{ $headerAnchorBase }}#departments">تیم‌ها</a>
            <a href="{{ $headerAnchorBase }}#why-yadak">مزایا</a>
            <a href="{{ $headerAnchorBase }}#process">فرایند جذب</a>
            <a href="{{ $headerAnchorBase }}#faq">سوالات متداول</a>
        </nav>

        <div class="ys-header__actions">
            <button type="button" class="ys-icon-btn" @click="toggleTheme()" :aria-label="dark ? 'فعال‌سازی حالت روشن' : 'فعال‌سازی حالت تیره'">
                <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21.75 15.5A9.75 9.75 0 0 1 8.5 2.25a.75.75 0 0 0-.81 1.2 8.25 8.25 0 1 0 11.86 11.86.75.75 0 0 0 1.2-.81Z"/></svg>
                <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.25a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75Zm0 16.5a.75.75 0 0 1 .75.75V21a.75.75 0 0 1-1.5 0v-1.5a.75.75 0 0 1 .75-.75ZM3 11.25h1.5a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1 0-1.5Zm16.5 0H21a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1 0-1.5ZM5.47 5.47a.75.75 0 0 1 1.06 0l1.06 1.06a.75.75 0 1 1-1.06 1.06L5.47 6.53a.75.75 0 0 1 0-1.06Zm10.94 10.94a.75.75 0 0 1 1.06 0l1.06 1.06a.75.75 0 0 1-1.06 1.06l-1.06-1.06a.75.75 0 0 1 0-1.06Zm2.12-10.94a.75.75 0 0 1 0 1.06l-1.06 1.06a.75.75 0 1 1-1.06-1.06l1.06-1.06a.75.75 0 0 1 1.06 0ZM7.59 16.41a.75.75 0 0 1 0 1.06l-1.06 1.06a.75.75 0 0 1-1.06-1.06l1.06-1.06a.75.75 0 0 1 1.06 0ZM12 7.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Z"/></svg>
            </button>
            <a href="{{ $headerAnchorBase }}#jobs" class="ys-btn ys-btn--secondary">مشاهده فرصت‌ها</a>
            <a href="{{ $headerAnchorBase }}#general-apply" class="ys-btn ys-btn--primary ys-header__resume-btn">ارسال رزومه</a>
            <button type="button" class="ys-menu-btn" @click="drawer = true" aria-label="باز کردن منو" :aria-expanded="drawer ? 'true' : 'false'">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<div class="ys-drawer-backdrop" x-show="drawer" x-transition.opacity @click="drawer = false" x-cloak></div>
<aside
    class="ys-drawer"
    x-show="drawer"
    x-ref="drawerPanel"
    role="dialog"
    aria-modal="true"
    aria-label="منوی موبایل"
    @keydown.escape.window="drawer = false"
    @keydown.tab="handleDrawerTab($event)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    x-cloak
>
    <div class="ys-drawer__head">
        <img :src="activeLogoUrl" src="{{ $logoUrl }}" alt="لوگوی {{ $companyName }}" class="h-10 w-auto">
        <button type="button" class="ys-icon-btn ys-drawer__close" @click="drawer = false" aria-label="بستن منو">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.225 4.811a1 1 0 0 0-1.414 1.414L10.586 12l-5.775 5.775a1 1 0 1 0 1.414 1.414L12 13.414l5.775 5.775a1 1 0 0 0 1.414-1.414L13.414 12l5.775-5.775a1 1 0 0 0-1.414-1.414L12 10.586 6.225 4.81Z"/></svg>
        </button>
    </div>
    <nav class="ys-drawer__nav" aria-label="منوی موبایل">
        <a href="{{ $headerAnchorBase }}#jobs" x-ref="drawerFirstLink" @click="drawer = false">فرصت‌ها</a>
        <a href="{{ $headerAnchorBase }}#departments" @click="drawer = false">تیم‌ها</a>
        <a href="{{ $headerAnchorBase }}#why-yadak" @click="drawer = false">مزایا</a>
        <a href="{{ $headerAnchorBase }}#process" @click="drawer = false">فرایند جذب</a>
        <a href="{{ $headerAnchorBase }}#faq" @click="drawer = false">سوالات متداول</a>
    </nav>
    <div class="ys-drawer__actions">
        <a href="{{ $headerAnchorBase }}#jobs" class="ys-btn ys-btn--secondary" @click="drawer = false">مشاهده فرصت‌ها</a>
        <a href="{{ $headerAnchorBase }}#general-apply" class="ys-btn ys-btn--primary" @click="drawer = false">ارسال رزومه</a>
    </div>
</aside>
