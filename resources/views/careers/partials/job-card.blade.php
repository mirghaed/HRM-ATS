@php
    $departmentName = trim((string) ($job->department?->name ?? ''));
    $departmentKey = \Illuminate\Support\Str::lower($job->department?->slug ?: $departmentName);
    $workModeLabel = $workModes[$job->work_mode] ?? $job->work_mode;
    $employmentLabel = $employmentTypes[$job->employment_type] ?? $job->employment_type;
    $displayTitle = trim((string) $job->title);
    if ($displayTitle === '' || strcasecmp($displayTitle, 'laravel dev') === 0) {
        $displayTitle = 'برنامه‌نویس Laravel';
    }
    $rawDescription = $job->plainTextSummary();
    $description = ($rawDescription === '' || strtolower($rawDescription) === 'desc')
        ? 'ما به دنبال فردی هستیم که در توسعه ابزارهای داخلی، بهبود فرایندها و ارتقای تجربه مشتری نقش مستقیمی داشته باشد.'
        : $rawDescription;
@endphp
<article
    data-job-card
    data-title="{{ \Illuminate\Support\Str::lower($displayTitle) }}"
    data-department="{{ $departmentKey }}"
    data-work-mode="{{ $job->work_mode }}"
    data-employment-type="{{ $job->employment_type }}"
    x-show="matchCard($el)"
    x-transition.opacity.duration.200ms
    class="ys-job-card"
>
    <div class="ys-job-card__head">
        <h3>{{ $displayTitle }}</h3>
        <span class="ys-pill ys-pill--danger">{{ $employmentLabel }}</span>
    </div>
    <p class="ys-job-card__meta">
        @if($departmentName !== '')
            <span>
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 4h16v4H4V4Zm2 6h12v10H6V10Z"/></svg>
                {{ $departmentName }}
            </span>
        @endif
        <span>
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 4 8h6l-5 5 2 7-7-4-7 4 2-7-5-5h6l4-8Z"/></svg>
            {{ $workModeLabel }}
        </span>
    </p>
    @if(($hrmSettings['landing.show_salary'] ?? false) && $job->is_salary_visible_public && $job->salary_min && $job->salary_max)
        <p class="ys-job-card__salary">حقوق: {{ number_format($job->salary_min) }} تا {{ number_format($job->salary_max) }} {{ $job->salary_currency ?: 'تومان' }}</p>
    @endif
    <p class="ys-job-card__desc">{{ \Illuminate\Support\Str::limit($description, 170) }}</p>
    <p class="ys-job-card__note">زمان پاسخ اولیه: معمولا 3 تا 5 روز کاری</p>
    <a href="{{ route('careers.jobs.show', $job) }}" class="ys-job-card__cta">مشاهده جزئیات و ارسال رزومه</a>
</article>
