@extends('layouts.hrm', ['title' => $department->name])
@section('content')
<div class="card p-6"><h2 class="text-xl font-bold">{{ $department->name }}</h2><p class="mt-2 text-slate-600">{{ $department->description }}</p></div>
@endsection