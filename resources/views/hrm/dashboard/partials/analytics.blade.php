@php
    $positionRows = collect($positions);
    $maxViews = max(1, (int) $positionRows->max('views_total'));
@endphp

<section class="ys-analytics-grid">
    <div class="ys-panel-card">
        <div class="ys-panel-card__head">
            <div>
                <h3>پربازدیدترین موقعیت‌ها</h3>
                <p>بر اساس کل بازدید ثبت‌شده</p>
            </div>
        </div>

        <div class="ys-top-list">
            @forelse($positionRows->take(5) as $position)
                @php $width = round(($position['views_total'] / $maxViews) * 100); @endphp
                <div class="ys-top-list__item">
                    <div class="ys-top-list__meta">
                        <strong>{{ $position['title'] }}</strong>
                        <span>{{ number_format($position['views_total']) }} بازدید</span>
                    </div>
                    <div class="ys-progress">
                        <span style="width: {{ $width }}%"></span>
                    </div>
                </div>
            @empty
                <p class="ys-empty-inline">هنوز بازدیدی ثبت نشده است.</p>
            @endforelse
        </div>
    </div>

    <div class="ys-panel-card">
        <div class="ys-panel-card__head">
            <div>
                <h3>خلاصه قیف جذب</h3>
                <p>بازدید تا رزومه و مصاحبه</p>
            </div>
        </div>

        <div class="ys-funnel">
            <div><span>بازدید کل</span><strong>{{ number_format($totalViews) }}</strong></div>
            <div><span>رزومه {{ $rangeLabel }}</span><strong>{{ number_format($applicationsCount) }}</strong></div>
            <div><span>مصاحبه پیش‌رو</span><strong>{{ number_format($upcomingInterviewsCount) }}</strong></div>
            <div><span>نرخ تبدیل</span><strong>{{ $conversionRate }}٪</strong></div>
        </div>
    </div>
</section>
