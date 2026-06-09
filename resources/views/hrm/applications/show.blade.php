@extends('layouts.hrm', ['title' => 'جزئیات رزومه'])
@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <div class="card p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold">{{ $application->candidate?->full_name }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $application->jobPosition?->title ?? 'رزومه عمومی' }} @if($application->department?->name) - {{ $application->department->name }} @endif</p>
                </div>
                <div class="text-left">
                    <span class="badge bg-slate-100 text-slate-700">کد رهگیری: {{ $application->tracking_code }}</span>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <span class="badge bg-cyan-100 text-cyan-700">وضعیت: {{ $application->currentStatus?->title }}</span>
                <span class="badge bg-amber-100 text-amber-700">امتیاز کل: {{ $application->overall_score }}</span>
                @can('delete', $application)
                    <form method="post" action="{{ route('hrm.applications.destroy', $application) }}" onsubmit="return confirm('رزومه حذف شود؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="badge bg-rose-100 text-rose-700">حذف رزومه</button>
                    </form>
                @endcan
            </div>

            <div class="ys-application-contact mt-4 grid gap-3 text-sm md:grid-cols-2">
                @if($application->candidate?->mobile)
                    <div class="ys-application-contact__item">
                        <span>موبایل</span>
                        <strong class="ys-ltr-input">{{ $application->candidate->mobile }}</strong>
                    </div>
                @endif
                @if($application->candidate?->email)
                    <div class="ys-application-contact__item">
                        <span>ایمیل</span>
                        <strong class="ys-ltr-input">{{ $application->candidate->email }}</strong>
                    </div>
                @endif
                @if($application->candidate?->portfolio_url)
                    <div class="ys-application-contact__item md:col-span-2">
                        <span>لینک نمونه‌کار</span>
                        <a href="{{ $application->candidate->portfolio_url }}" target="_blank" rel="noopener" class="ys-ltr-input text-cyan-700">{{ $application->candidate->portfolio_url }}</a>
                    </div>
                @endif
            </div>

            @if(filled(trim((string) $application->cover_letter)))
                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <h3 class="mb-2 text-sm font-bold text-slate-800">توضیح کوتاه</h3>
                    <p class="whitespace-pre-line text-sm text-slate-700">{{ $application->cover_letter }}</p>
                </div>
            @endif
        </div>

        @include('hrm.applications.partials.resume-viewer', ['application' => $application])

        <div class="card p-5">
            <form class="grid gap-2 md:grid-cols-4" method="post" action="{{ route('hrm.applications.change-status', $application) }}">
                @csrf
                <select class="rounded-xl border-slate-300 md:col-span-2" name="status_id" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->title }}</option>
                    @endforeach
                </select>
                <input class="rounded-xl border-slate-300 md:col-span-2" name="note" placeholder="توضیح تغییر وضعیت">
                <button class="rounded-xl bg-slate-900 px-4 py-2 text-white md:col-span-4">تغییر وضعیت</button>
            </form>

            <form class="mt-4 grid gap-2 md:grid-cols-4" method="post" action="{{ route('hrm.applications.notes.store', $application) }}">
                @csrf
                <input type="hidden" name="type" value="internal_note">
                <input type="hidden" name="visibility" value="hr_only">
                <textarea class="rounded-xl border-slate-300 md:col-span-4" name="body" rows="3" placeholder="افزودن یادداشت" required></textarea>
                <button class="rounded-xl bg-cyan-600 px-4 py-2 text-white md:col-span-4">ثبت یادداشت</button>
            </form>

            <form class="mt-4 grid gap-2 md:grid-cols-4" method="post" action="{{ route('hrm.applications.send-sms', $application) }}">
                @csrf
                <select class="rounded-xl border-slate-300 md:col-span-3" name="template_id" required>
                    <option value="">انتخاب قالب پیامک</option>
                    @foreach($smsTemplates as $template)
                        <option value="{{ $template->id }}">{{ $template->title }}</option>
                    @endforeach
                </select>
                <button class="rounded-xl border border-cyan-300 bg-cyan-50 px-4 py-2 text-cyan-700">ارسال پیامک</button>
            </form>
        </div>
    </div>

    <div class="space-y-4">
        <div class="card p-5">
            <h3 class="font-bold">اطلاعات درخواست</h3>
            <dl class="mt-3 space-y-2 text-sm">
                <div class="flex justify-between gap-3"><dt class="text-slate-500">منبع</dt><dd>{{ $application->source?->name ?? '---' }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-500">تاریخ ارسال</dt><dd>{{ optional($application->applied_at)->format('Y/m/d H:i') }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-500">حقوق مورد انتظار</dt><dd>@if($application->expected_salary_min){{ number_format($application->expected_salary_min) }}@else---@endif</dd></div>
            </dl>
        </div>

        <div class="card p-5">
            <h3 class="font-bold">تایم‌لاین</h3>
            <div class="mt-3 space-y-2 text-sm">
                @foreach($application->activities->take(20) as $activity)
                    <div class="rounded-lg border border-slate-100 p-2">
                        <p class="font-semibold">{{ $activity->title }}</p>
                        <p class="text-slate-500">{{ $activity->created_at }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card p-5">
            <h3 class="font-bold">یادداشت‌ها</h3>
            <div class="mt-3 space-y-2 text-sm">
                @foreach($application->notes->take(10) as $note)
                    <div class="rounded-lg border border-slate-100 p-2">
                        <p>{{ $note->body }}</p>
                        <p class="text-slate-500">{{ $note->created_at }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card p-5">
            <h3 class="font-bold">تاریخچه وضعیت</h3>
            <div class="mt-3 space-y-2 text-sm">
                @foreach($application->statusHistories->take(10) as $history)
                    <div class="rounded-lg border border-slate-100 p-2">
                        <p>{{ $history->fromStatus?->title ?? '---' }} → {{ $history->toStatus?->title }}</p>
                        <p class="text-slate-500">{{ $history->created_at }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
