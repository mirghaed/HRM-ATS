@extends('layouts.hrm', ['title' => 'ایجاد وضعیت استخدام'])

@section('content')
<div class="card p-6">
    <h2 class="mb-4 text-xl font-black text-slate-900">ایجاد وضعیت استخدام</h2>
    <form method="post" action="{{ route('hrm.recruitment-statuses.store') }}" class="space-y-4">
        @csrf
        @include('hrm.recruitment_statuses.form')
        <button class="rounded-xl bg-cyan-600 px-4 py-2 font-semibold text-white hover:bg-cyan-700">ذخیره</button>
    </form>
</div>
@endsection
