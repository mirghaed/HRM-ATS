@extends('layouts.hrm', ['title' => 'داشبورد'])

@section('content')
<div class="ys-admin-dashboard space-y-6">
    <section class="ys-admin-hero card p-6 lg:p-7">
        <span class="ys-admin-hero__kicker">مرکز فرمان HRM</span>
        <h2>نمای کلی جذب و فرصت‌های شغلی</h2>
        <p>شاخص‌های کلیدی، آخرین رزومه‌ها و وضعیت جذب را در یک نگاه مدیریت کنید.</p>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="ys-kpi-card">
            <span class="ys-kpi-card__label">دپارتمان‌ها</span>
            <strong>{{ $departmentCount }}</strong>
            <small>تیم فعال در سازمان</small>
        </article>
        <article class="ys-kpi-card">
            <span class="ys-kpi-card__label">موقعیت‌های باز</span>
            <strong>{{ $openJobCount }}</strong>
            <small>فرصت شغلی قابل جذب</small>
        </article>
        <article class="ys-kpi-card">
            <span class="ys-kpi-card__label">کل رزومه‌ها</span>
            <strong>{{ $applicationCount }}</strong>
            <small>در پایگاه رزومه</small>
        </article>
        <article class="ys-kpi-card">
            <span class="ys-kpi-card__label">مصاحبه‌های پیش‌رو</span>
            <strong>{{ $pendingInterviews }}</strong>
            <small>در صف پیگیری</small>
        </article>
    </section>

    <section class="card overflow-hidden">
        <div class="ys-admin-section-head">
            <h3>آخرین رزومه‌ها</h3>
            <a href="{{ route('hrm.applications.index') }}" class="ys-admin-link">مشاهده همه رزومه‌ها</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                <tr>
                    <th class="px-4 py-3 text-right">کارجو</th>
                    <th class="px-4 py-3 text-right">موقعیت</th>
                    <th class="px-4 py-3 text-right">وضعیت</th>
                    <th class="px-4 py-3 text-right">جزئیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentApplications as $application)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $application->candidate?->full_name }}</td>
                        <td class="px-4 py-3">{{ $application->jobPosition?->title }}</td>
                        <td class="px-4 py-3">{{ $application->currentStatus?->title }}</td>
                        <td class="px-4 py-3">
                            <a class="ys-admin-link" href="{{ route('hrm.applications.show', $application) }}">مشاهده</a>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-3 text-slate-500" colspan="4">موردی یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
