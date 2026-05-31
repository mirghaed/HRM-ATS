@extends('layouts.hrm', ['title' => 'ویرایش رزومه'])
@section('content')
<div class="card p-6">@include('hrm.applications.form', ['application' => $application, 'action' => route('hrm.applications.update', $application), 'method' => 'PUT'])</div>
@endsection