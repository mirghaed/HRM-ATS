@extends('layouts.hrm', ['title' => 'دپارتمان‌ها'])
@section('content')
<div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-bold">دپارتمان‌ها</h2><a class="rounded-lg bg-cyan-600 px-3 py-2 text-white" href="{{ route('hrm.departments.create') }}">ایجاد</a></div>
<div class="card overflow-hidden">
<table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">نام</th><th class="px-4 py-3 text-right">مدیر</th><th class="px-4 py-3 text-right">عملیات</th></tr></thead><tbody>
@foreach($departments as $department)
<tr class="border-t"><td class="px-4 py-3">{{ $department->name }}</td><td class="px-4 py-3">{{ $department->manager?->name }}</td><td class="px-4 py-3 flex gap-3"><a class="text-cyan-700" href="{{ route('hrm.departments.show', $department) }}">مشاهده</a><a class="text-amber-700" href="{{ route('hrm.departments.edit', $department) }}">ویرایش</a></td></tr>
@endforeach
</tbody></table></div>
<div class="mt-4">{{ $departments->links() }}</div>
@endsection