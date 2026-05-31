@extends('layouts.hrm', ['title' => 'لاگ پیامک'])
@section('content')
<div class="card overflow-hidden"><table class="min-w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">موبایل</th><th class="px-4 py-3 text-right">وضعیت</th><th class="px-4 py-3 text-right">قالب</th><th class="px-4 py-3 text-right">زمان</th></tr></thead><tbody>@foreach($smsLogs as $log)<tr class="border-t"><td class="px-4 py-3">{{ $log->mobile }}</td><td class="px-4 py-3">{{ $log->status }}</td><td class="px-4 py-3">{{ $log->template?->title }}</td><td class="px-4 py-3">{{ $log->created_at }}</td></tr>@endforeach</tbody></table></div>
<div class="mt-4">{{ $smsLogs->links() }}</div>
@endsection