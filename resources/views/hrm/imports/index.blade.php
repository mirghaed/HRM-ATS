@extends('layouts.hrm', ['title' => 'ایمپورت‌ها'])
@section('content')
<div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-bold">ایمپورت رزومه</h2><a class="rounded-lg bg-cyan-600 px-3 py-2 text-white" href="{{ route('hrm.imports.create') }}">ایمپورت جدید</a></div>
<div class="card overflow-hidden"><table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">منبع</th><th class="px-4 py-3 text-right">وضعیت</th><th class="px-4 py-3 text-right">تعداد</th><th class="px-4 py-3 text-right">جزئیات</th></tr></thead><tbody>@foreach($runs as $run)<tr class="border-t"><td class="px-4 py-3">{{ $run->source?->name }}</td><td class="px-4 py-3">{{ $run->status }}</td><td class="px-4 py-3">{{ $run->total_items }}</td><td class="px-4 py-3"><a class="text-cyan-700" href="{{ route('hrm.imports.show', $run) }}">مشاهده</a></td></tr>@endforeach</tbody></table></div>
<div class="mt-4">{{ $runs->links() }}</div>
@endsection