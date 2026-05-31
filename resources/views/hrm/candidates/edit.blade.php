@extends('layouts.hrm', ['title' => 'ویرایش کارجو'])
@section('content')
<div class="card p-6">
<form method="post" action="{{ route('hrm.candidates.update', $candidate) }}" class="grid gap-4">
@csrf @method('PUT')
<input class="rounded-xl border-slate-300" name="full_name" value="{{ old('full_name', $candidate->full_name) }}" required>
<div class="grid gap-3 md:grid-cols-2"><input class="rounded-xl border-slate-300" name="mobile" value="{{ old('mobile', $candidate->mobile) }}"><input class="rounded-xl border-slate-300" name="email" value="{{ old('email', $candidate->email) }}"></div>
<button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">ذخیره</button>
</form>
</div>
@endsection
