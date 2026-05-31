@extends('layouts.hrm', ['title' => 'ایجاد کاربر پنل'])
@section('content')
<div class="card p-6">@include('hrm.users.form', ['action' => route('hrm.users.store'), 'method' => 'POST'])</div>
@endsection

