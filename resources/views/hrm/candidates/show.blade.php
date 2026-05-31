@extends('layouts.hrm', ['title' => $candidate->full_name])
@section('content')
<div class="card p-6"><h2 class="text-xl font-bold">{{ $candidate->full_name }}</h2><p class="mt-2 text-slate-600">{{ $candidate->mobile }} - {{ $candidate->email }}</p><a class="mt-4 inline-flex rounded-xl bg-cyan-600 px-4 py-2 text-white" href="{{ route('hrm.candidates.edit', $candidate) }}">ویرایش</a></div>
<div class="card mt-6 p-6"><h3 class="font-bold">درخواست‌ها</h3><div class="mt-3 space-y-2">@foreach($candidate->applications as $app)<a class="block rounded-lg border border-slate-200 p-3" href="{{ route('hrm.applications.show', $app) }}">{{ $app->jobPosition?->title }} - {{ $app->created_at }}</a>@endforeach</div></div>
@endsection