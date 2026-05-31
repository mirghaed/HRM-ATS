<form method="post" action="{{ $action }}" class="grid gap-4">
@csrf
@if($method !== 'POST') @method($method) @endif
<input class="rounded-xl border-slate-300" name="title" value="{{ old('title', $template?->title) }}" placeholder="عنوان" required>
<input class="rounded-xl border-slate-300" name="key" value="{{ old('key', $template?->key) }}" placeholder="کلید" required>
<input class="rounded-xl border-slate-300" name="provider_template_id" value="{{ old('provider_template_id', $template?->provider_template_id) }}" placeholder="شناسه قالب provider">
<textarea class="rounded-xl border-slate-300" name="body_preview" rows="4" placeholder="پیش‌نمایش متن">{{ old('body_preview', $template?->body_preview) }}</textarea>
<button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">ذخیره</button>
</form>