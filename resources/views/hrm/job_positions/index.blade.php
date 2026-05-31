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
    <a class="rounded-lg bg-cyan-600 px-3 py-2 text-white" href="{{ route('hrm.job-positions.create') }}">ایجاد</a>
</div>

<div class="card overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-right">عنوان</th>
                <th class="px-4 py-3 text-right">دپارتمان</th>
                <th class="px-4 py-3 text-right">وضعیت</th>
                <th class="px-4 py-3 text-right">عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobPositions as $job)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $job->title }}</td>
                    <td class="px-4 py-3">{{ $job->department?->name }}</td>
                    <td class="px-4 py-3">{{ $statusLabels[$job->status] ?? $job->status }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a class="text-cyan-700" href="{{ route('hrm.job-positions.show', $job) }}">مشاهده</a>
                            @can('update', $job)
                                <a class="text-amber-700" href="{{ route('hrm.job-positions.edit', $job) }}">ویرایش</a>
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

<div class="mt-4">{{ $jobPositions->links() }}</div>
@endsection