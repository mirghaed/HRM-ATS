@extends('layouts.hrm', ['title' => 'ویرایش کاربر پنل'])
@section('content')
<div class="card p-6">@include('hrm.users.form', ['action' => route('hrm.users.update', $user), 'method' => 'PUT'])</div>
@endsection

