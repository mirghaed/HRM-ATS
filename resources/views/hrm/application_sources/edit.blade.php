@extends('layouts.hrm', ['title' => 'ویرایش منبع'])
@section('content')
<div class="card p-6">@include('hrm.application_sources.form', ['source' => $source, 'action' => route('hrm.application-sources.update', $source), 'method' => 'PUT'])</div>
@endsection