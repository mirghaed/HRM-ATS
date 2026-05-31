@extends('layouts.hrm', ['title' => 'تقویم مصاحبه‌ها'])
@section('content')
<div class="card p-6"><h2 class="text-xl font-bold">تقویم</h2><div class="mt-4 space-y-2">@foreach($interviews as $interview)<div class="rounded-lg border border-slate-200 p-3">{{ $interview->start_at }} - {{ $interview->candidate?->full_name }} - {{ $interview->interviewer?->name }}</div>@endforeach</div></div>
@endsection