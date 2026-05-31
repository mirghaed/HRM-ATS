@extends('layouts.hrm', ['title' => 'وضعیت‌های استخدام'])

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-black text-slate-900">وضعیت‌های استخدام</h2>
        @can('settings.manage')
            <a href="{{ route('hrm.recruitment-statuses.create') }}" class="rounded-xl bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700">وضعیت جدید</a>
        @endcan
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-right">عنوان</th>
                    <th class="px-4 py-3 text-right">کلید</th>
                    <th class="px-4 py-3 text-right">ترنزیشن</th>
                    <th class="px-4 py-3 text-right">Application</th>
                    <th class="px-4 py-3 text-right">ویژگی‌ها</th>
                    <th class="px-4 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($statuses as $status)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full" style="background: {{ $status->color ?: '#94a3b8' }}"></span>
                            <span class="font-semibold text-slate-900">{{ $status->title }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $status->key }}</td>
                    <td class="px-4 py-3">{{ $status->transitions_from_count }}</td>
                    <td class="px-4 py-3">{{ $status->applications_count }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1 text-xs">
                            @if($status->is_default)<span class="rounded-full bg-cyan-100 px-2 py-1 text-cyan-700">پیش‌فرض</span>@endif
                            @if($status->is_terminal)<span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">نهایی</span>@endif
                            @if($status->is_success)<span class="rounded-full bg-emerald-100 px-2 py-1 text-emerald-700">موفق</span>@endif
                            @if($status->requires_note)<span class="rounded-full bg-amber-100 px-2 py-1 text-amber-700">نیاز به توضیح</span>@endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @can('settings.manage')
                                <a class="rounded-lg border border-slate-200 px-2 py-1 hover:bg-slate-50" href="{{ route('hrm.recruitment-statuses.edit', $status) }}">ویرایش</a>
                                <form method="post" action="{{ route('hrm.recruitment-statuses.destroy', $status) }}" onsubmit="return confirm('حذف شود؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-rose-200 px-2 py-1 text-rose-700 hover:bg-rose-50">حذف</button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">وضعیتی ثبت نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
