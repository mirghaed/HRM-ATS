@extends('layouts.hrm', ['title' => 'داشبورد'])

@section('content')
<div class="ys-admin-dashboard">
    @include('hrm.dashboard.partials.hero')
    @include('hrm.dashboard.partials.kpis')
    @include('hrm.dashboard.partials.analytics')
    @include('hrm.dashboard.partials.job-position-views-table')
    @include('hrm.dashboard.partials.latest-applications')
</div>
@endsection
