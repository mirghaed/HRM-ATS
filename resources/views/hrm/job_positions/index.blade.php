@extends('layouts.hrm', ['title' => 'موقعیت‌ها'])

@php
    $statusLabels = [
        'draft' => 'پیش‌نویس',
        'published' => 'منتشر شده',
        'paused' => 'متوقف شده',
        'closed' => 'بسته شده',
        'archived' => 'آرشیو',
    ];
@endphp

@section('content')
<div class="mb-4 flex items-center justify-between">
    <h2 class="text-xl font-bold">موقعیت‌های شغلی</h2>
    @can('job_positions.create')
        <a class="rounded-lg bg-cyan-600 px-3 py-2 text-white" href="{{ route('hrm.job-positions.create') }}">ایجاد</a>
    @endcan
</div>

<div class="ys-panel-card">
    <div class="ys-table-wrap">
        <table class="ys-admin-table">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>دپارتمان</th>
                    <th>وضعیت</th>
                    <th>رزومه</th>
                    <th>بازدید کل</th>
                    <th>بازدید امروز</th>
                    <th>نرخ تبدیل</th>
                    <th>آخرین بروزرسانی</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobPositions as $job)
                    @php
                        $rate = $job->conversion_rate ?? 0;
                        $rateClass = $rate > 5 ? 'ys-badge--success' : ($rate >= 1 ? 'ys-badge--info' : 'ys-badge--warning');
                    @endphp
                    <tr>
                        <td><strong>{{ $job->title }}</strong></td>
                        <td>{{ $job->department?->name ?? '—' }}</td>
                        <td>
                            <span class="ys-badge {{ $job->status === 'published' ? 'ys-badge--success' : 'ys-badge--info' }}">
                                {{ $statusLabels[$job->status] ?? $job->status }}
                            </span>
                        </td>
                        <td>{{ number_format($job->applications_count) }}</td>
                        <td>{{ number_format($job->views_total ?? 0) }}</td>
                        <td>{{ number_format($job->views_today ?? 0) }}</td>
                        <td><span class="ys-badge {{ $rateClass }}">{{ $rate }}٪</span></td>
                        <td>{{ optional($job->updated_at)->format('Y/m/d H:i') }}</td>
                        <td>
                            <div class="flex flex-wrap items-center gap-3">
                                <a class="ys-admin-link" href="{{ route('hrm.job-positions.show', $job) }}">مشاهده</a>
                                @can('update', $job)
                                    <a class="ys-admin-link" href="{{ route('hrm.job-positions.edit', $job) }}">ویرایش</a>
                                @endcan
                                @can('delete', $job)
                                    <form method="post" action="{{ route('hrm.job-positions.destroy', $job) }}" onsubmit="return confirm('موقعیت شغلی حذف شود؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-700">حذف</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $jobPositions->links() }}</div>
@endsection
