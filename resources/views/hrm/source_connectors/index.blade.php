@extends('layouts.hrm', ['title' => 'اتصالات منابع رزومه'])

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-black text-slate-900">اتصالات منابع رزومه</h2>
        @can('application_sources.create')
            <a href="{{ route('hrm.source-connectors.create') }}" class="rounded-xl bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700">اتصال جدید</a>
        @endcan
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-right">منبع</th>
                    <th class="px-4 py-3 text-right">Driver</th>
                    <th class="px-4 py-3 text-right">Mode</th>
                    <th class="px-4 py-3 text-right">Status</th>
                    <th class="px-4 py-3 text-right">آخرین Sync</th>
                    <th class="px-4 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($connectors as $connector)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $connector->source?->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $connector->driver }}</td>
                    <td class="px-4 py-3">{{ $connector->mode }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-1 text-xs {{ $connector->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($connector->status === 'error' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700') }}">
                            {{ $connector->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $connector->last_sync_at?->diffForHumans() ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @can('application_sources.update')
                                <a class="rounded-lg border border-slate-200 px-2 py-1 hover:bg-slate-50" href="{{ route('hrm.source-connectors.edit', $connector) }}">ویرایش</a>
                            @endcan
                            @can('application_sources.delete')
                                <form method="post" action="{{ route('hrm.source-connectors.destroy', $connector) }}" onsubmit="return confirm('حذف شود؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-rose-200 px-2 py-1 text-rose-700 hover:bg-rose-50">حذف</button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">هنوز اتصالی ثبت نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $connectors->links() }}</div>
</div>
@endsection
