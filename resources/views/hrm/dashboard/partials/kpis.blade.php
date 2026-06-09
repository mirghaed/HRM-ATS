<section class="ys-kpi-grid">
    <article class="ys-kpi-card">
        <div class="ys-kpi-card__icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 21V9l8-6 8 6v12H4Z" stroke="currentColor" stroke-width="1.8"/></svg>
        </div>
        <span class="ys-kpi-card__label">دپارتمان‌های فعال</span>
        <strong>{{ number_format($departmentsCount) }}</strong>
        <small>تیم فعال در سازمان</small>
    </article>

    <article class="ys-kpi-card">
        <div class="ys-kpi-card__icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 7h18M7 7V5h10v2M6 11h12v8H6v-8Z" stroke="currentColor" stroke-width="1.8"/></svg>
        </div>
        <span class="ys-kpi-card__label">موقعیت‌های باز</span>
        <strong>{{ number_format($openPositionsCount) }}</strong>
        <small>فرصت شغلی منتشر شده</small>
    </article>

    <article class="ys-kpi-card ys-kpi-card--accent">
        <div class="ys-kpi-card__icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
        </div>
        <span class="ys-kpi-card__label">بازدید {{ $rangeLabel }}</span>
        <strong>{{ number_format($rangeViews) }}</strong>
        <small>بازدید صفحه موقعیت‌ها</small>
    </article>

    <article class="ys-kpi-card">
        <div class="ys-kpi-card__icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M6 4h9l3 3v13H6V4Z" stroke="currentColor" stroke-width="1.8"/><path d="M15 4v4h4" stroke="currentColor" stroke-width="1.8"/></svg>
        </div>
        <span class="ys-kpi-card__label">رزومه‌های {{ $rangeLabel }}</span>
        <strong>{{ number_format($applicationsCount) }}</strong>
        <small>ثبت‌شده در بازه انتخابی</small>
    </article>

    <article class="ys-kpi-card">
        <div class="ys-kpi-card__icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M8 2v3M16 2v3M3 9h18M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8"/></svg>
        </div>
        <span class="ys-kpi-card__label">مصاحبه‌های پیش‌رو</span>
        <strong>{{ number_format($upcomingInterviewsCount) }}</strong>
        <small>در صف پیگیری</small>
    </article>

    <article class="ys-kpi-card">
        <div class="ys-kpi-card__icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 19V5M4 19h16M8 15l3-3 2 2 5-6" stroke="currentColor" stroke-width="1.8"/></svg>
        </div>
        <span class="ys-kpi-card__label">بازدید کل موقعیت‌ها</span>
        <strong>{{ number_format($totalViews) }}</strong>
        <small>از ابتدای ثبت آمار</small>
    </article>

    <article class="ys-kpi-card">
        <div class="ys-kpi-card__icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3l2.4 4.8 5.4.8-3.9 3.8.9 5.3L12 15.8 7.2 17.7l.9-5.3L4.2 8.6l5.4-.8L12 3Z" stroke="currentColor" stroke-width="1.8"/></svg>
        </div>
        <span class="ys-kpi-card__label">پربازدیدترین موقعیت</span>
        <strong class="ys-kpi-card__title">{{ $topPosition['title'] ?? '—' }}</strong>
        <small>{{ isset($topPosition['views_total']) ? number_format($topPosition['views_total']).' بازدید' : 'هنوز بازدیدی ثبت نشده' }}</small>
    </article>

    <article class="ys-kpi-card ys-kpi-card--info">
        <div class="ys-kpi-card__icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 18V6M8 18V10M12 18V14M16 18V8M20 18V4" stroke="currentColor" stroke-width="1.8"/></svg>
        </div>
        <span class="ys-kpi-card__label">نرخ تبدیل</span>
        <strong>{{ $conversionRate }}٪</strong>
        <small>رزومه نسبت به بازدید کل</small>
    </article>
</section>
