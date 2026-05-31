@extends('layouts.hrm', ['title' => 'ویرایش قالب پیامک'])
@section('content')
<div class="card p-6">@include('hrm.sms_templates.form', ['template' => $template, 'action' => route('hrm.sms-templates.update', $template), 'method' => 'PUT'])</div>
@endsection