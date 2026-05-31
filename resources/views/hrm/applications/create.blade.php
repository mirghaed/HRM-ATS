@extends('layouts.hrm', ['title' => 'ثبت دستی رزومه'])
@section('content')
<div class="card p-6">@include('hrm.applications.form', ['application' => null, 'action' => route('hrm.applications.store'), 'method' => 'POST'])</div>
@endsection