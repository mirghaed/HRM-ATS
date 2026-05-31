@extends('layouts.hrm', ['title' => 'جزئیات رزومه'])
@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="card p-5 lg:col-span-2">
        <h2 class="text-xl font-bold">{{ $application->candidate?->full_name }}</h2>
        <p class="mt-1 text-sm text-slate-600">{{ $application->jobPosition?->title }} - {{ $application->department?->name }}</p>

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

        <p class="mt-4 whitespace-pre-line text-sm">{{ $application->cover_letter }}</p>

        <form class="mt-6 grid gap-2 md:grid-cols-4" method="post" action="{{ route('hrm.applications.change-status', $application) }}">
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

    <div class="space-y-4">
        <div class="card p-5">
            <h3 class="font-bold">فایل‌های رزومه</h3>
            <div class="mt-3 space-y-2 text-sm">
                @forelse($application->files as $file)
                    <div class="rounded-lg border border-slate-100 p-2">
                        <p class="font-semibold">{{ $file->original_name ?? basename($file->path) }}</p>
                        <a class="mt-1 inline-block text-cyan-700" href="{{ route('hrm.applications.files.download', [$application, $file]) }}">دانلود</a>
                    </div>
                @empty
                    <p class="text-slate-500">فایلی ثبت نشده است.</p>
                @endforelse
            </div>
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
