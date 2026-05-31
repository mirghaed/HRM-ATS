@extends('layouts.hrm', ['title' => 'ویرایش دپارتمان'])
@section('content')
<div class="card p-6">@include('hrm.departments.form', ['department' => $department, 'action' => route('hrm.departments.update', $department), 'method' => 'PUT'])</div>
@endsection