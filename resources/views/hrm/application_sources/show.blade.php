@extends('layouts.hrm', ['title' => $source->name])

@section('content')
<div class="space-y-4">
    <div class="card p-6">
        <h2 class="text-xl font-black text-slate-900">{{ $source->name }}</h2>
        <p class="mt-2 text-sm text-slate-600">کلید منبع: {{ $source->key }}</p>
    </div>

    <div class="card p-6">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-900">اتصالات این منبع</h3>
            @can('application_sources.create')
                <a href="{{ route('hrm.source-connectors.create') }}" class="rounded-xl border border-cyan-200 px-3 py-1.5 text-sm text-cyan-700 hover:bg-cyan-50">ایجاد اتصال</a>
            @endcan
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-right">Driver</th>
                        <th class="px-4 py-3 text-right">Mode</th>
                        <th class="px-4 py-3 text-right">Status</th>
                        <th class="px-4 py-3 text-right">آخرین Sync</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($source->connectors as $connector)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $connector->driver }}</td>
                        <td class="px-4 py-3">{{ $connector->mode }}</td>
                        <td class="px-4 py-3">{{ $connector->status }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $connector->last_sync_at?->diffForHumans() ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">اتصالی ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection