@extends('layouts.hrm', ['title' => 'جزئیات ایمپورت'])
@section('content')
<div class="card p-6">
    <h2 class="text-xl font-bold">وضعیت: {{ $run->status }}</h2>
    <p class="mt-2 text-slate-600">کل: {{ $run->total_items }} | ایجاد: {{ $run->created_items }} | خطا: {{ $run->failed_items }}</p>

    @if(in_array($run->status, ['failed', 'partial'], true) && auth()->user()?->can('imports.retry'))
        <form method="post" action="{{ route('hrm.imports.retry', $run) }}" class="mt-4">
            @csrf
            <button class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-2 text-amber-800">تلاش مجدد پردازش</button>
        </form>
    @endif
</div>

<div class="card mt-6 overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50">
        <tr>
            <th class="px-4 py-3 text-right">ردیف</th>
            <th class="px-4 py-3 text-right">وضعیت</th>
            <th class="px-4 py-3 text-right">درخواست</th>
            <th class="px-4 py-3 text-right">خطا</th>
        </tr>
        </thead>
        <tbody>
        @foreach($run->items as $item)
            <tr class="border-t">
                <td class="px-4 py-3">{{ $item->row_number }}</td>
                <td class="px-4 py-3">{{ $item->status }}</td>
                <td class="px-4 py-3">{{ $item->application_id }}</td>
                <td class="px-4 py-3">{{ $item->error_message }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
