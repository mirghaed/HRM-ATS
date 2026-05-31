@extends('layouts.hrm', ['title' => 'رزومه‌ها'])
@section('content')
<div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-bold">رزومه‌ها</h2><a class="rounded-lg bg-cyan-600 px-3 py-2 text-white" href="{{ route('hrm.applications.create') }}">ثبت دستی رزومه</a></div>
<form class="mb-4 grid gap-3 md:grid-cols-4" method="get">
    <input class="rounded-xl border-slate-300" name="q" value="{{ request('q') }}" placeholder="جستجو">
    <select class="rounded-xl border-slate-300" name="department_id"><option value="">همه دپارتمان‌ها</option>@foreach($departments as $department)<option value="{{ $department->id }}" @selected(request('department_id')==$department->id)>{{ $department->name }}</option>@endforeach</select>
    <select class="rounded-xl border-slate-300" name="status_id"><option value="">همه وضعیت‌ها</option>@foreach($statuses as $status)<option value="{{ $status->id }}" @selected(request('status_id')==$status->id)>{{ $status->title }}</option>@endforeach</select>
    <button class="rounded-xl bg-slate-800 px-3 py-2 text-white">فیلتر</button>
</form>
<div class="card overflow-hidden"><table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">کارجو</th><th class="px-4 py-3 text-right">موقعیت</th><th class="px-4 py-3 text-right">وضعیت</th><th class="px-4 py-3 text-right">امتیاز</th><th class="px-4 py-3 text-right">عملیات</th></tr></thead><tbody>@foreach($applications as $app)<tr class="border-t"><td class="px-4 py-3">{{ $app->candidate?->full_name }}</td><td class="px-4 py-3">{{ $app->jobPosition?->title }}</td><td class="px-4 py-3">{{ $app->currentStatus?->title }}</td><td class="px-4 py-3">{{ $app->overall_score }}</td><td class="px-4 py-3"><div class="flex items-center gap-3"><a class="text-cyan-700" href="{{ route('hrm.applications.show', $app) }}">مشاهده</a>@can('delete', $app)<form method="post" action="{{ route('hrm.applications.destroy', $app) }}" onsubmit="return confirm('رزومه حذف شود؟');">@csrf @method('DELETE')<button type="submit" class="text-rose-700">حذف</button></form>@endcan</div></td></tr>@endforeach</tbody></table></div>
<div class="mt-4">{{ $applications->links() }}</div>
@endsection
