@extends('layouts.hrm', ['title' => 'ویرایش مصاحبه'])
@section('content')
<div class="card p-6">@include('hrm.interviews.form', ['interview' => $interview, 'action' => route('hrm.interviews.update', $interview), 'method' => 'PUT'])</div>
@endsection