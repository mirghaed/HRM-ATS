@extends('layouts.hrm', ['title' => 'ایجاد دپارتمان'])
@section('content')
<div class="card p-6">@include('hrm.departments.form', ['department' => null, 'action' => route('hrm.departments.store'), 'method' => 'POST'])</div>
@endsection