@extends('layouts.hrm', ['title' => 'قالب‌های پیامک'])
@section('content')
<div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-bold">قالب‌های پیامک</h2><a class="rounded-lg bg-cyan-600 px-3 py-2 text-white" href="{{ route('hrm.sms-templates.create') }}">ایجاد</a></div>
<div class="card overflow-hidden"><table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">عنوان</th><th class="px-4 py-3 text-right">کلید</th><th class="px-4 py-3 text-right">عملیات</th></tr></thead><tbody>@foreach($templates as $template)<tr class="border-t"><td class="px-4 py-3">{{ $template->title }}</td><td class="px-4 py-3">{{ $template->key }}</td><td class="px-4 py-3"><a class="text-cyan-700" href="{{ route('hrm.sms-templates.edit', $template) }}">ویرایش</a></td></tr>@endforeach</tbody></table></div>
<div class="mt-4">{{ $templates->links() }}</div>
@endsection