@extends('layouts.hrm', ['title' => 'دلایل رد'])

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-black text-slate-900">دلایل رد</h2>
        @can('settings.manage')
            <a href="{{ route('hrm.rejection-reasons.create') }}" class="rounded-xl bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700">دلیل جدید</a>
        @endcan
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
            <tr>
                <th class="px-4 py-3 text-right">عنوان</th>
                <th class="px-4 py-3 text-right">اولویت</th>
                <th class="px-4 py-3 text-right">وضعیت</th>
                <th class="px-4 py-3 text-right">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($reasons as $reason)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-900">{{ $reason->title }}</p>
                        @if($reason->description)
                            <p class="text-xs text-slate-500">{{ $reason->description }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $reason->sort_order }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-1 text-xs {{ $reason->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                            {{ $reason->is_active ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @can('settings.manage')
                                <a class="rounded-lg border border-slate-200 px-2 py-1 hover:bg-slate-50" href="{{ route('hrm.rejection-reasons.edit', $reason) }}">ویرایش</a>
                                <form method="post" action="{{ route('hrm.rejection-reasons.destroy', $reason) }}" onsubmit="return confirm('حذف شود؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-rose-200 px-2 py-1 text-rose-700 hover:bg-rose-50">حذف</button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">دلیلی ثبت نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $reasons->links() }}</div>
</div>
@endsection
