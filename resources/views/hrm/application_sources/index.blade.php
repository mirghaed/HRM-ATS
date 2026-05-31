@extends('layouts.hrm', ['title' => 'منابع رزومه'])
@section('content')
<div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-bold">منابع رزومه</h2><a class="rounded-lg bg-cyan-600 px-3 py-2 text-white" href="{{ route('hrm.application-sources.create') }}">ایجاد</a></div>
<div class="card overflow-hidden"><table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">نام</th><th class="px-4 py-3 text-right">کلید</th><th class="px-4 py-3 text-right">نوع</th><th class="px-4 py-3 text-right">عملیات</th></tr></thead><tbody>@foreach($sources as $source)<tr class="border-t"><td class="px-4 py-3">{{ $source->name }}</td><td class="px-4 py-3">{{ $source->key }}</td><td class="px-4 py-3">{{ $source->type }}</td><td class="px-4 py-3"><a class="text-cyan-700" href="{{ route('hrm.application-sources.edit', $source) }}">ویرایش</a></td></tr>@endforeach</tbody></table></div>
<div class="mt-4">{{ $sources->links() }}</div>
@endsection