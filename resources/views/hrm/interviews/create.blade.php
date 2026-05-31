@extends('layouts.hrm', ['title' => 'تنظیم مصاحبه'])
@section('content')
<div class="card p-6">@include('hrm.interviews.form', ['interview' => null, 'action' => route('hrm.interviews.store'), 'method' => 'POST'])</div>
@endsection