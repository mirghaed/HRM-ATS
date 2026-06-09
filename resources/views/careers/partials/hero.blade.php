<section id="hero" class="ys-hero">
    <div class="ys-container ys-hero__inner">
        <div class="ys-hero__content">
            <span class="ys-hero__badge">{{ $heroBadge }}</span>
            <h1>{{ $heroTitle }}</h1>
            <p>{{ $heroSubtitle }}</p>
            <div class="ys-hero__actions">
                <a href="#jobs" class="ys-btn ys-btn--primary">مشاهده فرصت‌های شغلی</a>
                <a href="#general-apply" class="ys-btn ys-btn--secondary">ارسال رزومه عمومی</a>
            </div>
        </div>

        <div class="ys-hero__visual ys-hero__visual--illustrated">
            <x-career-picture
                name="hero-teamwork"
                alt="تیم‌های کاری {{ $companyName }}"
                width="1440"
                height="810"
                loading="eager"
                fetchpriority="high"
                sizes="(max-width: 1024px) 100vw, 48vw"
                class="ys-hero-illustration"
            />

            <div class="ys-hero-floating-cards">
                <div class="ys-hero-floating-card ys-hero-floating-card--top">
                    <span>تیم‌های همکار</span>
                    <strong>فروش، نرم‌افزار، عملیات</strong>
                    <small>هم‌راستا برای تجربه بهتر مشتری</small>
                </div>

                <div class="ys-hero-floating-card ys-hero-floating-card--bottom">
                    @if($hasReliableStats)
                        <span>فرصت‌های فعال</span>
                        <strong>{{ number_format($stats['open_jobs']) }}</strong>
                        <small>در {{ number_format($stats['active_departments']) }} تیم</small>
                    @else
                        <span>مسیر جذب</span>
                        <strong>شفاف و سریع</strong>
                        <small>از ارسال رزومه تا نتیجه نهایی</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
