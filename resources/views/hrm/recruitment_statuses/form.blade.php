@php $status = $status ?? null; @endphp

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-semibold">کلید</label>
        <input class="w-full rounded-xl border-slate-300" type="text" name="key" value="{{ old('key', $status?->key) }}" required>
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">عنوان</label>
        <input class="w-full rounded-xl border-slate-300" type="text" name="title" value="{{ old('title', $status?->title) }}" required>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold">توضیحات</label>
        <textarea class="w-full rounded-xl border-slate-300" rows="3" name="description">{{ old('description', $status?->description) }}</textarea>
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">رنگ (HEX)</label>
        <input class="w-full rounded-xl border-slate-300" type="text" name="color" value="{{ old('color', $status?->color) }}" placeholder="#0ea5e9">
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">ترتیب</label>
        <input class="w-full rounded-xl border-slate-300" type="number" min="0" name="sort_order" value="{{ old('sort_order', $status?->sort_order ?? 0) }}">
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold">SMS Template مرتبط (اختیاری)</label>
        <select class="w-full rounded-xl border-slate-300" name="sms_template_id">
            <option value="">ندارد</option>
            @foreach($smsTemplates as $template)
                <option value="{{ $template->id }}" @selected((string) old('sms_template_id', $status?->sms_template_id) === (string) $template->id)>{{ $template->title }}</option>
            @endforeach
        </select>
    </div>

    @foreach([
        'is_default' => 'وضعیت پیش‌فرض',
        'is_terminal' => 'وضعیت نهایی',
        'is_success' => 'پایان موفق',
        'requires_note' => 'الزام ثبت توضیح',
        'can_schedule_interview' => 'امکان زمان‌بندی مصاحبه',
        'notify_candidate' => 'اعلان به کارجو',
        'notify_department_manager' => 'اعلان به مدیر دپارتمان',
        'notify_interviewer' => 'اعلان به مصاحبه‌گر',
    ] as $field => $label)
        <div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input type="hidden" name="{{ $field }}" value="0">
                <input type="checkbox" class="rounded border-slate-300 text-cyan-600" name="{{ $field }}" value="1" @checked((bool) old($field, $status?->{$field} ?? false))>
                {{ $label }}
            </label>
        </div>
    @endforeach
</div>
