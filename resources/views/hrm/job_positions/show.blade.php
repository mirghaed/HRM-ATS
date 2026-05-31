@extends('layouts.hrm', ['title' => $jobPosition->title])
@section('content')
<div class="card p-6">
    <h2 class="text-xl font-bold">{{ $jobPosition->title }}</h2>
    <p class="mt-2 text-slate-600">{{ $jobPosition->department?->name }}</p>
    <div class="mt-4 ys-rich-content">{!! $jobPosition->description ?: '<p>شرحی ثبت نشده است.</p>' !!}</div>
    <h3 class="mt-5 text-base font-extrabold text-slate-800">نیازمندی‌ها</h3>
    <div class="mt-2 ys-rich-content">{!! $jobPosition->requirements ?: '<p>نیازمندی‌ای ثبت نشده است.</p>' !!}</div>
</div>
@endsection
