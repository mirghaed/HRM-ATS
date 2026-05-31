<form method="post" action="{{ $action }}" class="grid gap-4">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <input class="rounded-xl border-slate-300" name="name" value="{{ old('name', $user?->name) }}" placeholder="نام کامل" required>
    <input class="rounded-xl border-slate-300" name="email" type="email" value="{{ old('email', $user?->email) }}" placeholder="ایمیل" required>
    <input class="rounded-xl border-slate-300" name="mobile" value="{{ old('mobile', $user?->mobile) }}" placeholder="موبایل">

    <div class="grid gap-3 md:grid-cols-2">
        <input class="rounded-xl border-slate-300" name="password" type="password" placeholder="{{ $method === 'POST' ? 'رمز عبور (حداقل ۸ کاراکتر)' : 'رمز عبور جدید (اختیاری)' }}" {{ $method === 'POST' ? 'required' : '' }}>
        <input class="rounded-xl border-slate-300" name="password_confirmation" type="password" placeholder="تکرار رمز عبور" {{ $method === 'POST' ? 'required' : '' }}>
    </div>

    <div class="grid gap-3 md:grid-cols-2">
        <select class="rounded-xl border-slate-300" name="role" required>
            <option value="">انتخاب نقش</option>
            @foreach($roles as $roleName)
                <option value="{{ $roleName }}" @selected(old('role', $user?->roles->first()?->name) === $roleName)>{{ $roleName }}</option>
            @endforeach
        </select>

        <select class="rounded-xl border-slate-300" name="status" required>
            <option value="active" @selected(old('status', $user?->status ?? 'active') === 'active')>فعال</option>
            <option value="inactive" @selected(old('status', $user?->status) === 'inactive')>غیرفعال</option>
        </select>
    </div>

    @php
        $selectedDepartmentIds = collect(old('department_ids', $user?->departments?->pluck('id')->all() ?? []))->map(fn($id) => (int) $id)->all();
    @endphp
    <label>
        <span class="mb-2 block text-sm font-semibold text-slate-700">دپارتمان‌های دسترسی</span>
        <select class="min-h-36 w-full rounded-xl border-slate-300" name="department_ids[]" multiple>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" @selected(in_array((int) $department->id, $selectedDepartmentIds, true))>{{ $department->name }}</option>
            @endforeach
        </select>
        <small class="mt-1 block text-xs text-slate-500">برای انتخاب چند مورد، کلید Ctrl را نگه دارید.</small>
    </label>

    <button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">ذخیره کاربر</button>
</form>

