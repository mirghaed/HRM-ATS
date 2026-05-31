@extends('layouts.hrm', ['title' => 'گزارش‌ها'])
@section('content')
<div class="grid gap-4 md:grid-cols-5">
    <div class="card p-5"><p class="text-sm text-slate-500">کل رزومه‌ها</p><p class="mt-2 text-2xl font-bold">{{ $totalApplications }}</p></div>
    <div class="card p-5"><p class="text-sm text-slate-500">موقعیت باز</p><p class="mt-2 text-2xl font-bold">{{ $totalOpenPositions }}</p></div>
    <div class="card p-5"><p class="text-sm text-slate-500">استخدام‌شده</p><p class="mt-2 text-2xl font-bold">{{ $hiredCount }}</p></div>
    <div class="card p-5"><p class="text-sm text-slate-500">نرخ رزومه→مصاحبه</p><p class="mt-2 text-2xl font-bold">{{ $conversionToInterview }}%</p></div>
    <div class="card p-5"><p class="text-sm text-slate-500">نرخ مصاحبه→استخدام</p><p class="mt-2 text-2xl font-bold">{{ $conversionToHired }}%</p></div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="card overflow-hidden">
        <div class="border-b px-4 py-3 font-bold">رزومه‌ها بر اساس وضعیت</div>
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">وضعیت</th><th class="px-4 py-3 text-right">تعداد</th></tr></thead>
            <tbody>@foreach($statusStats as $status)<tr class="border-t"><td class="px-4 py-3">{{ $status->title }}</td><td class="px-4 py-3">{{ $status->applications_count }}</td></tr>@endforeach</tbody>
        </table>
    </div>

    <div class="card overflow-hidden">
        <div class="border-b px-4 py-3 font-bold">رزومه‌ها بر اساس منبع</div>
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">منبع</th><th class="px-4 py-3 text-right">تعداد</th></tr></thead>
            <tbody>@foreach($sourceStats as $row)<tr class="border-t"><td class="px-4 py-3">{{ $row->source?->name ?? 'نامشخص' }}</td><td class="px-4 py-3">{{ $row->total }}</td></tr>@endforeach</tbody>
        </table>
    </div>

    <div class="card overflow-hidden">
        <div class="border-b px-4 py-3 font-bold">رزومه‌ها بر اساس دپارتمان</div>
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">دپارتمان</th><th class="px-4 py-3 text-right">تعداد</th></tr></thead>
            <tbody>@foreach($departmentStats as $row)<tr class="border-t"><td class="px-4 py-3">{{ $row->department?->name ?? 'نامشخص' }}</td><td class="px-4 py-3">{{ $row->total }}</td></tr>@endforeach</tbody>
        </table>
    </div>

    <div class="card overflow-hidden">
        <div class="border-b px-4 py-3 font-bold">عملکرد مصاحبه‌گرها</div>
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">مصاحبه‌گر</th><th class="px-4 py-3 text-right">کل مصاحبه</th><th class="px-4 py-3 text-right">تکمیل‌شده</th></tr></thead>
            <tbody>@foreach($interviewStats as $row)<tr class="border-t"><td class="px-4 py-3">{{ $row->interviewer?->name ?? 'نامشخص' }}</td><td class="px-4 py-3">{{ $row->total }}</td><td class="px-4 py-3">{{ $row->completed_total }}</td></tr>@endforeach</tbody>
        </table>
    </div>
</div>
@endsection
