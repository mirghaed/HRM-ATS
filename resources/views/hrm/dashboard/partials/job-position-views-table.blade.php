<section class="ys-panel-card">
    <div class="ys-panel-card__head">
        <div>
            <h3>آمار بازدید موقعیت‌های شغلی</h3>
            <p>بررسی عملکرد هر آگهی بر اساس بازدید و تعداد رزومه دریافتی</p>
        </div>
        @can('job_positions.view')
            <a href="{{ route('hrm.job-positions.index') }}" class="ys-admin-link">مدیریت موقعیت‌ها</a>
        @endcan
    </div>

    <div class="ys-table-wrap">
        <table class="ys-admin-table">
            <thead>
                <tr>
                    <th>موقعیت</th>
                    <th>دپارتمان</th>
                    <th>بازدید کل</th>
                    <th>امروز</th>
                    <th>هفته</th>
                    <th>ماه</th>
                    <th>رزومه</th>
                    <th>نرخ تبدیل</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $position)
                    @php
                        $rate = $position['conversion_rate'];
                        $rateClass = $rate > 5 ? 'ys-badge--success' : ($rate >= 1 ? 'ys-badge--info' : 'ys-badge--warning');
                    @endphp
                    <tr>
                        <td><strong>{{ $position['title'] }}</strong></td>
                        <td>{{ $position['department_name'] ?? '—' }}</td>
                        <td>{{ number_format($position['views_total']) }}</td>
                        <td>{{ number_format($position['views_today']) }}</td>
                        <td>{{ number_format($position['views_week']) }}</td>
                        <td>{{ number_format($position['views_month']) }}</td>
                        <td>{{ number_format($position['applications_count']) }}</td>
                        <td><span class="ys-badge {{ $rateClass }}">{{ $rate }}٪</span></td>
                        <td><span class="ys-badge ys-badge--success">{{ $position['status'] }}</span></td>
                        <td>
                            @can('job_positions.update')
                                <a href="{{ $position['admin_url'] }}" class="ys-admin-link">ویرایش</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="ys-empty-cell">هنوز موقعیت فعالی ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
