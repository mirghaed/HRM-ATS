@extends('layouts.hrm', ['title' => 'ایمپورت جدید'])
@section('content')
<div class="card p-6"><form method="post" enctype="multipart/form-data" action="{{ route('hrm.imports.store') }}" class="grid gap-4">@csrf<select class="rounded-xl border-slate-300" name="source_id" required>@foreach($sources as $source)<option value="{{ $source->id }}">{{ $source->name }}</option>@endforeach</select><input type="file" class="rounded-xl border-slate-300" name="file" required><button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">شروع ایمپورت</button></form></div>
@endsection