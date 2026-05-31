@extends('layouts.hrm', ['title' => 'جزئیات مصاحبه'])
@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="card p-6 lg:col-span-2">
        <h2 class="text-xl font-bold">{{ $interview->candidate?->full_name }}</h2>
        <p class="mt-2 text-slate-600">{{ $interview->start_at?->format('Y-m-d H:i') }} - {{ $interview->interviewer?->name }}</p>
        <div class="mt-4 grid gap-3 md:grid-cols-2 text-sm">
            <div><span class="font-semibold">نوع:</span> {{ $interview->type }}</div>
            <div><span class="font-semibold">وضعیت:</span> {{ $interview->status }}</div>
            <div><span class="font-semibold">امتیاز:</span> {{ $interview->score ?? '-' }}</div>
            <div><span class="font-semibold">لینک جلسه:</span> {{ $interview->online_meeting_url ?? '-' }}</div>
        </div>

        @if($interview->result_note)
            <div class="mt-4 rounded-xl border border-slate-200 p-3 text-sm">
                <p class="font-semibold">نتیجه مصاحبه</p>
                <p class="mt-2 whitespace-pre-line">{{ $interview->result_note }}</p>
            </div>
        @endif

        <a class="mt-4 inline-flex rounded-xl bg-cyan-600 px-4 py-2 text-white" href="{{ route('hrm.interviews.edit', $interview) }}">ویرایش</a>
    </div>

    <div class="card p-5">
        <h3 class="font-bold">یادآورها</h3>
        <div class="mt-3 space-y-2 text-sm">
            @forelse($interview->reminders as $reminder)
                <div class="rounded-lg border border-slate-100 p-2">
                    <div>زمان ارسال: {{ $reminder->send_at }}</div>
                    <div>وضعیت: {{ $reminder->status }}</div>
                </div>
            @empty
                <div class="text-slate-500">یادآوری ثبت نشده است.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
