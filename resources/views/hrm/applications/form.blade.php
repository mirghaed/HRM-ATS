<form method="post" action="{{ $action }}" enctype="multipart/form-data" class="grid gap-4">
@csrf
@if($method !== 'POST') @method($method) @endif
<input class="rounded-xl border-slate-300" name="full_name" value="{{ old('full_name', $application?->candidate?->full_name) }}" placeholder="نام کارجو" required>
<div class="grid gap-3 md:grid-cols-2"><input class="rounded-xl border-slate-300" name="mobile" value="{{ old('mobile', $application?->candidate?->mobile) }}" placeholder="موبایل"><input class="rounded-xl border-slate-300" name="email" value="{{ old('email', $application?->candidate?->email) }}" placeholder="ایمیل"></div>
<select class="rounded-xl border-slate-300" name="source_id" required>@foreach($sources as $source)<option value="{{ $source->id }}" @selected(old('source_id', $application?->source_id)==$source->id)>{{ $source->name }}</option>@endforeach</select>
<select class="rounded-xl border-slate-300" name="department_id"><option value="">بدون دپارتمان</option>@foreach($departments as $department)<option value="{{ $department->id }}" @selected(old('department_id', $application?->department_id)==$department->id)>{{ $department->name }}</option>@endforeach</select>
<select class="rounded-xl border-slate-300" name="job_position_id"><option value="">بدون موقعیت</option>@foreach($jobPositions as $job)<option value="{{ $job->id }}" @selected(old('job_position_id', $application?->job_position_id)==$job->id)>{{ $job->title }}</option>@endforeach</select>
<div class="grid gap-3 md:grid-cols-2"><input type="number" class="rounded-xl border-slate-300" name="expected_salary_min" value="{{ old('expected_salary_min', $application?->expected_salary_min) }}" placeholder="حداقل حقوق"><input type="number" class="rounded-xl border-slate-300" name="expected_salary_max" value="{{ old('expected_salary_max', $application?->expected_salary_max) }}" placeholder="حداکثر حقوق"></div>
<textarea class="rounded-xl border-slate-300" name="cover_letter" rows="4" placeholder="توضیح">{{ old('cover_letter', $application?->cover_letter) }}</textarea>
<input type="file" class="rounded-xl border-slate-300" name="resume" {{ $application ? '' : 'required' }}>
<button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">ذخیره</button>
</form>