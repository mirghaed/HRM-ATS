@extends('layouts.hrm', ['title' => 'ایجاد منبع'])
@section('content')
<div class="card p-6">@include('hrm.application_sources.form', ['source' => null, 'action' => route('hrm.application-sources.store'), 'method' => 'POST'])</div>
@endsection