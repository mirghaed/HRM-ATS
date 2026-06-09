<section class="ys-dashboard-hero">
    <div>
        <span class="ys-dashboard-hero__kicker">مرکز فرمان HRM</span>
        <h2>نمای زنده جذب نیرو و عملکرد موقعیت‌های شغلی</h2>
        <p>از اینجا تعداد رزومه‌ها، بازدید موقعیت‌ها، مصاحبه‌های پیش‌رو و نرخ تبدیل هر فرصت شغلی را بررسی کنید.</p>
        <p class="ys-dashboard-hero__date">{{ now()->translatedFormat('l j F Y') }}</p>
    </div>

    <div class="ys-dashboard-hero__actions">
        <div class="ys-date-filter">
            <a class="{{ ($range ?? '30d') === 'today' ? 'is-active' : '' }}" href="{{ route('dashboard', ['range' => 'today']) }}">امروز</a>
            <a class="{{ ($range ?? '30d') === '7d' ? 'is-active' : '' }}" href="{{ route('dashboard', ['range' => '7d']) }}">۷ روز اخیر</a>
            <a class="{{ ($range ?? '30d') === '30d' ? 'is-active' : '' }}" href="{{ route('dashboard', ['range' => '30d']) }}">۳۰ روز اخیر</a>
        </div>

        @can('job_positions.create')
            <a href="{{ route('hrm.job-positions.create') }}" class="ys-btn ys-btn--light">+ موقعیت جدید</a>
        @endcan
        @if(auth()->user()?->can('applications.view_all') || auth()->user()?->can('applications.view_department') || auth()->user()?->can('applications.view_assigned'))
            <a href="{{ route('hrm.applications.index') }}" class="ys-btn ys-btn--ghost">مشاهده رزومه‌ها</a>
        @endif
    </div>
</section>
