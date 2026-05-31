@php $reason = $reason ?? null; @endphp

<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold">عنوان دلیل رد</label>
        <input class="w-full rounded-xl border-slate-300" type="text" name="title" value="{{ old('title', $reason?->title) }}" required>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold">توضیحات</label>
        <textarea class="w-full rounded-xl border-slate-300" rows="4" name="description">{{ old('description', $reason?->description) }}</textarea>
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">اولویت نمایش</label>
        <input class="w-full rounded-xl border-slate-300" type="number" min="0" name="sort_order" value="{{ old('sort_order', $reason?->sort_order ?? 0) }}">
    </div>

    <div class="flex items-end">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" class="rounded border-slate-300 text-cyan-600" name="is_active" value="1" @checked((bool) old('is_active', $reason?->is_active ?? true))>
            فعال باشد
        </label>
    </div>
</div>
