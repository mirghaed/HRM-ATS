<section id="hero" class="ys-hero">
    <div class="ys-container ys-hero__inner">
        <div class="ys-hero__content">
            <span class="ys-hero__badge">{{ $heroBadge }}</span>
            <h1>{{ $heroTitle }}</h1>
            <p>{{ $heroSubtitle }}</p>
            <div class="ys-hero__actions">
                <a href="#jobs" class="ys-btn ys-btn--primary">مشاهده فرصت‌هاي شغلي</a>
                <a href="#general-apply" class="ys-btn ys-btn--secondary">ارسال رزومه عمومي</a>
            </div>
        </div>

        <div class="ys-hero__visual ys-hero__visual--illustrated">
            <x-career-picture
                name="hero-teamwork"
                alt="تيم‌هاي کاري {{ $companyName }}"
                width="1440"
                height="810"
                loading="eager"
                fetchpriority="high"
                sizes="(max-width: 1024px) 100vw, 48vw"
                class="ys-hero-illustration"
            />

            <div class="ys-hero-floating-card ys-hero-floating-card--top">
                <span>تيم‌هاي همکار</span>
                <strong>فروش، نرم‌افزار، عمليات</strong>
                <small>هم‌راستا براي تجربه بهتر مشتري</small>
            </div>

            <div class="ys-hero-floating-card ys-hero-floating-card--bottom">
                @if($hasReliableStats)
                    <span>فرصت‌هاي فعال</span>
                    <strong>{{ number_format($stats['open_jobs']) }}</strong>
                    <small>در {{ number_format($stats['active_departments']) }} تيم</small>
                @else
                    <span>مسير جذب</span>
                    <strong>شفاف و سريع</strong>
                    <small>از ارسال رزومه تا نتيجه نهايي</small>
                @endif
            </div>
        </div>
    </div>
</section>
