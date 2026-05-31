<form method="post" action="{{ $action }}" class="grid gap-4">
@csrf
@if($method !== 'POST') @method($method) @endif

@if(!$interview)
<select class="rounded-xl border-slate-300" name="application_id" required>
@foreach($applications as $application)
<option value="{{ $application->id }}">{{ $application->candidate?->full_name }} - {{ $application->jobPosition?->title }}</option>
@endforeach
</select>
@else
<input type="hidden" name="application_id" value="{{ $interview->application_id }}">
@endif

<select class="rounded-xl border-slate-300" name="interviewer_id" required>
@foreach($interviewers as $interviewer)
<option value="{{ $interviewer->id }}" @selected(old('interviewer_id', $interview?->interviewer_id)==$interviewer->id)>{{ $interviewer->name }}</option>
@endforeach
</select>

<select class="rounded-xl border-slate-300" name="type" required>
<option value="online" @selected(old('type', $interview?->type) === 'online')>آنلاین</option>
<option value="onsite" @selected(old('type', $interview?->type) === 'onsite')>حضوری</option>
<option value="phone" @selected(old('type', $interview?->type) === 'phone')>تلفنی</option>
</select>

<div class="grid gap-3 md:grid-cols-2">
<input type="datetime-local" class="rounded-xl border-slate-300" name="start_at" value="{{ old('start_at', optional($interview?->start_at)->format('Y-m-d\\TH:i')) }}" required>
<input type="datetime-local" class="rounded-xl border-slate-300" name="end_at" value="{{ old('end_at', optional($interview?->end_at)->format('Y-m-d\\TH:i')) }}">
</div>

<input class="rounded-xl border-slate-300" name="location_title" value="{{ old('location_title', $interview?->location_title) }}" placeholder="عنوان محل مصاحبه">
<textarea class="rounded-xl border-slate-300" name="address" rows="2" placeholder="آدرس محل">{{ old('address', $interview?->address) }}</textarea>
<input class="rounded-xl border-slate-300" name="online_meeting_url" value="{{ old('online_meeting_url', $interview?->online_meeting_url) }}" placeholder="لینک جلسه آنلاین">
<textarea class="rounded-xl border-slate-300" name="description" rows="3" placeholder="توضیحات">{{ old('description', $interview?->description) }}</textarea>

<div class="grid gap-3 md:grid-cols-3">
<select class="rounded-xl border-slate-300" name="status">
<option value="scheduled" @selected(old('status', $interview?->status ?? 'scheduled') === 'scheduled')>زمان‌بندی‌شده</option>
<option value="completed" @selected(old('status', $interview?->status) === 'completed')>تکمیل‌شده</option>
<option value="rescheduled" @selected(old('status', $interview?->status) === 'rescheduled')>زمان‌بندی مجدد</option>
<option value="no_show" @selected(old('status', $interview?->status) === 'no_show')>عدم حضور</option>
<option value="cancelled" @selected(old('status', $interview?->status) === 'cancelled')>لغو</option>
</select>
<input type="number" min="0" max="100" class="rounded-xl border-slate-300" name="score" value="{{ old('score', $interview?->score) }}" placeholder="امتیاز (0 تا 100)">
<label class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700">
<input type="hidden" name="send_sms_to_candidate" value="0">
<input type="checkbox" name="send_sms_to_candidate" value="1" @checked(old('send_sms_to_candidate', $interview?->send_sms_to_candidate))>
ارسال پیامک به کارجو
</label>
</div>

<textarea class="rounded-xl border-slate-300" name="result_note" rows="3" placeholder="نتیجه مصاحبه">{{ old('result_note', $interview?->result_note) }}</textarea>

<button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">ذخیره</button>
</form>
