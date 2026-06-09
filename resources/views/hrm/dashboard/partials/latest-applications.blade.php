<section class="ys-dashboard-split">
    <div class="ys-panel-card">
        <div class="ys-panel-card__head">
            <div>
                <h3>آخرین رزومه‌ها</h3>
                <p>جدیدترین درخواست‌های همکاری</p>
            </div>
            @if(auth()->user()?->can('applications.view_all') || auth()->user()?->can('applications.view_department') || auth()->user()?->can('applications.view_assigned'))
                <a href="{{ route('hrm.applications.index') }}" class="ys-admin-link">مشاهده همه</a>
            @endif
        </div>

        <div class="ys-table-wrap">
            <table class="ys-admin-table">
                <thead>
                    <tr>
                        <th>کارجو</th>
                        <th>موقعیت</th>
                        <th>وضعیت</th>
                        <th>کد رهگیری</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestApplications as $application)
                        <tr>
                            <td>
                                <strong>{{ $application->candidate?->full_name }}</strong>
                                @if($application->candidate?->mobile)
                                    <div class="ys-table-sub ys-ltr-input">{{ $application->candidate->mobile }}</div>
                                @endif
                            </td>
                            <td>{{ $application->jobPosition?->title ?? 'رزومه عمومی' }}</td>
                            <td>
                                <span class="ys-badge ys-badge--info">{{ $application->currentStatus?->title ?? '—' }}</span>
                            </td>
                            <td class="font-mono">{{ $application->tracking_code }}</td>
                            <td>
                                <a href="{{ route('hrm.applications.show', $application) }}" class="ys-admin-link">مشاهده</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="ys-empty-cell">رزومه‌ای ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="ys-dashboard-side">
        <div class="ys-panel-card">
            <div class="ys-panel-card__head">
                <div>
                    <h3>مصاحبه‌های پیش‌رو</h3>
                    <p>برنامه زمانی نزدیک</p>
                </div>
                @if(auth()->user()?->can('interviews.view_all') || auth()->user()?->can('interviews.view_department') || auth()->user()?->can('interviews.view_own'))
                    <a href="{{ route('hrm.interviews.index') }}" class="ys-admin-link">همه</a>
                @endif
            </div>

            <div class="ys-mini-list">
                @forelse($upcomingInterviews as $interview)
                    <div class="ys-mini-list__item">
                        <strong>{{ $interview->candidate?->full_name }}</strong>
                        <span>{{ $interview->jobPosition?->title }}</span>
                        <time>{{ optional($interview->start_at)->format('Y/m/d H:i') }}</time>
                    </div>
                @empty
                    <p class="ys-empty-inline">مصاحبه‌ای در صف نیست.</p>
                @endforelse
            </div>
        </div>

        <div class="ys-panel-card">
            <div class="ys-panel-card__head">
                <div>
                    <h3>خلاصه وضعیت رزومه‌ها</h3>
                    <p>توزیع در بازه {{ $rangeLabel }}</p>
                </div>
            </div>

            <div class="ys-status-list">
                @forelse($statusSummary as $status)
                    <div class="ys-status-list__item">
                        <span>{{ $status['title'] }}</span>
                        <strong>{{ number_format($status['applications_count']) }}</strong>
                    </div>
                @empty
                    <p class="ys-empty-inline">داده‌ای برای نمایش وجود ندارد.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
