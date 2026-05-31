@extends('layouts.hrm', ['title' => 'ایجاد قالب پیامک'])
@section('content')
<div class="card p-6">@include('hrm.sms_templates.form', ['template' => null, 'action' => route('hrm.sms-templates.store'), 'method' => 'POST'])</div>
@endsection