@extends('layouts.hrm', ['title' => 'ویرایش وضعیت استخدام'])

@section('content')
<div class="space-y-6">
    <div class="card p-6">
        <h2 class="mb-4 text-xl font-black text-slate-900">ویرایش وضعیت استخدام</h2>
        <form method="post" action="{{ route('hrm.recruitment-statuses.update', $status) }}" class="space-y-4">
            @csrf
            @method('PATCH')
            @include('hrm.recruitment_statuses.form', ['status' => $status])
            <button class="rounded-xl bg-cyan-600 px-4 py-2 font-semibold text-white hover:bg-cyan-700">بروزرسانی</button>
        </form>
    </div>

    <div class="card p-6">
        <h3 class="mb-4 text-lg font-black text-slate-900">Transitionهای خروجی از این وضعیت</h3>

        <div class="mb-5 overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-right">به وضعیت</th>
                    <th class="px-4 py-3 text-right">Roleهای مجاز</th>
                    <th class="px-4 py-3 text-right">قوانین</th>
                    <th class="px-4 py-3 text-right">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($status->transitionsFrom as $transition)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $transition->toStatus?->title ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $transition->allowed_roles ? implode(', ', $transition->allowed_roles) : 'همه نقش‌ها' }}</td>
                        <td class="px-4 py-3 text-xs">
                            <div class="flex flex-wrap gap-1">
                                @if($transition->requires_note)<span class="rounded-full bg-amber-100 px-2 py-1 text-amber-700">نیاز به توضیح</span>@endif
                                @if($transition->requires_interview)<span class="rounded-full bg-violet-100 px-2 py-1 text-violet-700">نیاز به مصاحبه</span>@endif
                                @if(!$transition->is_active)<span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">غیرفعال</span>@endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <form method="post" action="{{ route('hrm.recruitment-status-transitions.destroy', $transition) }}" onsubmit="return confirm('حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-rose-200 px-2 py-1 text-rose-700 hover:bg-rose-50">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Transitionی ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <h4 class="mb-3 text-sm font-bold text-slate-800">افزودن Transition جدید</h4>
        <form method="post" action="{{ route('hrm.recruitment-status-transitions.store') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <input type="hidden" name="from_status_id" value="{{ $status->id }}">

            <div>
                <label class="mb-1 block text-sm font-semibold">از وضعیت</label>
                <input class="w-full rounded-xl border-slate-300 bg-slate-50" value="{{ $status->title }}" readonly>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">به وضعیت</label>
                <select class="w-full rounded-xl border-slate-300" name="to_status_id" required>
                    <option value="">انتخاب کنید</option>
                    @foreach($allStatuses as $candidateStatus)
                        @if($candidateStatus->id !== $status->id)
                            <option value="{{ $candidateStatus->id }}">{{ $candidateStatus->title }} ({{ $candidateStatus->key }})</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold">Roleهای مجاز (comma-separated)</label>
                <input class="w-full rounded-xl border-slate-300" type="text" name="allowed_roles" placeholder="Super Admin, HR Manager, HR Staff / Recruiter">
            </div>

            <div class="flex flex-wrap items-center gap-4 md:col-span-2">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="hidden" name="requires_note" value="0">
                    <input class="rounded border-slate-300 text-cyan-600" type="checkbox" name="requires_note" value="1">
                    نیاز به توضیح
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="hidden" name="requires_interview" value="0">
                    <input class="rounded border-slate-300 text-cyan-600" type="checkbox" name="requires_interview" value="1">
                    نیاز به مصاحبه
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="hidden" name="is_active" value="0">
                    <input class="rounded border-slate-300 text-cyan-600" type="checkbox" name="is_active" value="1" checked>
                    فعال
                </label>
            </div>

            <div class="md:col-span-2">
                <button class="rounded-xl bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700">افزودن Transition</button>
            </div>
        </form>
    </div>
</div>
@endsection
