@extends('layouts.hrm', ['title' => 'ویرایش موقعیت'])
@push('head')
    <link rel="stylesheet" href="{{ asset('vendor/trix/trix.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('vendor/trix/trix.umd.min.js') }}"></script>
    <script>
        document.addEventListener('trix-file-accept', function (event) {
            event.preventDefault();
        });

        document.addEventListener('trix-initialize', function (event) {
            const editor = event.target;
            editor.addEventListener('pointerdown', function () {
                editor.focus();
            });
        });
    </script>
@endpush

@section('content')
<div class="card p-6">@include('hrm.job_positions.form', ['jobPosition' => $jobPosition, 'action' => route('hrm.job-positions.update', $jobPosition), 'method' => 'PUT'])</div>
@endsection
