<form method="post" action="{{ $action }}" class="grid gap-4">
@csrf
@if($method !== 'POST') @method($method) @endif
<input class="rounded-xl border-slate-300" name="name" value="{{ old('name', $source?->name) }}" placeholder="نام" required>
<input class="rounded-xl border-slate-300" name="key" value="{{ old('key', $source?->key) }}" placeholder="کلید" required>
<select class="rounded-xl border-slate-300" name="type"><option value="manual">manual</option><option value="website_form">website_form</option><option value="job_board">job_board</option><option value="referral">referral</option><option value="email">email</option><option value="social">social</option><option value="import">import</option><option value="api">api</option><option value="other">other</option></select>
<label class="text-sm"><input type="checkbox" name="supports_auto_import" value="1" @checked(old('supports_auto_import', $source?->supports_auto_import))> پشتیبانی از ایمپورت خودکار</label>
<label class="text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $source?->is_active ?? true))> فعال</label>
<button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">ذخیره</button>
</form>