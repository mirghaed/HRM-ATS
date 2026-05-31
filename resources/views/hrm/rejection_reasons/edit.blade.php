@extends('layouts.hrm', ['title' => 'ویرایش دلیل رد'])

@section('content')
<div class="card p-6">
    <h2 class="mb-4 text-xl font-black text-slate-900">ویرایش دلیل رد</h2>
    <form method="post" action="{{ route('hrm.rejection-reasons.update', $reason) }}" class="space-y-4">
        @csrf
        @method('PATCH')
        @include('hrm.rejection_reasons.form', ['reason' => $reason])
        <button class="rounded-xl bg-cyan-600 px-4 py-2 font-semibold text-white hover:bg-cyan-700">بروزرسانی</button>
    </form>
</div>
@endsection
