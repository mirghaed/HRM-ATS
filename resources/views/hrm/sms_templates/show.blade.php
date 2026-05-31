@extends('layouts.hrm', ['title' => $template->title])
@section('content')
<div class="card p-6"><h2 class="text-xl font-bold">{{ $template->title }}</h2><pre class="mt-3 whitespace-pre-wrap text-sm">{{ $template->body_preview }}</pre></div>
@endsection