<form
    method="post"
    action="{{ $action }}"
    class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]"
    x-data="{
        questions: @js(old('questions', $jobPosition?->questions?->toArray() ?? [])),
        slugTouched: @js(!empty(old('slug', $jobPosition?->slug))),
        normalizeSlug(value) {
            return (value || '')
                .toLowerCase()
                .replace(/[^\u0600-\u06FF\w\s-]/g, '')
                .trim()
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        },
        syncSlugFromTitle(event) {
            if (this.slugTouched) return;
            this.$refs.slug.value = this.normalizeSlug(event.target.value);
        }
    }"
>
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="space-y-4">
        <label class="block">
            <span class="mb-1 block text-sm font-semibold text-slate-700">عنوان موقعیت</span>
            <input
                class="w-full rounded-xl border-slate-300"
                name="title"
                value="{{ old('title', $jobPosition?->title) }}"
                placeholder="مثال: کارشناس فروش (خانم - Call Center)"
                required
                @input="syncSlugFromTitle($event)"
            >
        </label>

        <label class="block">
            <span class="mb-1 block text-sm font-semibold text-slate-700">اسلاگ (اختیاری)</span>
            <input
                x-ref="slug"
                class="w-full rounded-xl border-slate-300"
                name="slug"
                value="{{ old('slug', $jobPosition?->slug) }}"
                placeholder="اگر خالی بماند از عنوان ساخته می‌شود"
                @input="slugTouched = (($event.target.value || '').trim().length > 0)"
            >
        </label>

        <div class="block">
            <label for="description-editor" class="mb-1 block text-sm font-semibold text-slate-700">شرح موقعیت</label>
            <input id="description" type="hidden" name="description" value="{{ old('description', $jobPosition?->description) }}">
            <trix-editor id="description-editor" input="description" class="ys-trix"></trix-editor>
        </div>

        <div class="block">
            <label for="requirements-editor" class="mb-1 block text-sm font-semibold text-slate-700">نیازمندی‌ها</label>
            <input id="requirements" type="hidden" name="requirements" value="{{ old('requirements', $jobPosition?->requirements) }}">
            <trix-editor id="requirements-editor" input="requirements" class="ys-trix"></trix-editor>
        </div>

        <div class="space-y-3 rounded-2xl border border-slate-200 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-800">سوالات فرم درخواست</h3>
                <button type="button" class="rounded-xl border border-slate-300 px-3 py-2 text-xs" @click="questions.push({question:'',type:'text',is_required:false,sort_order:questions.length})">افزودن سوال</button>
            </div>

            <template x-if="questions.length === 0">
                <p class="text-xs text-slate-500">هنوز سوالی اضافه نشده است.</p>
            </template>

            <template x-for="(item, index) in questions" :key="index">
                <div class="rounded-xl border border-slate-200 p-3">
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <strong class="text-xs text-slate-700" x-text="'سوال #' + (index + 1)"></strong>
                        <button type="button" class="text-xs font-semibold text-rose-700" @click="questions.splice(index, 1)">حذف</button>
                    </div>
                    <input class="mb-2 w-full rounded-xl border-slate-300" :name="`questions[${index}][question]`" x-model="item.question" placeholder="متن سوال">
                    <div class="grid gap-2 md:grid-cols-2">
                        <select class="rounded-xl border-slate-300" :name="`questions[${index}][type]`" x-model="item.type">
                            <option value="text">متن</option>
                            <option value="textarea">توضیحی</option>
                            <option value="number">عدد</option>
                        </select>
                        <label class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700">
                            <input type="checkbox" :name="`questions[${index}][is_required]`" value="1" x-model="item.is_required">
                            اجباری
                        </label>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <aside class="space-y-4">
        <div class="sticky top-24 space-y-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-600">دپارتمان</label>
                <select class="w-full rounded-xl border-slate-300" name="department_id" required>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id', $jobPosition?->department_id) == $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-600">نوع همکاری و مدل کار</label>
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-1">
                    <select class="rounded-xl border-slate-300" name="employment_type">
                        <option value="full_time" @selected(old('employment_type', $jobPosition?->employment_type ?? 'full_time') === 'full_time')>تمام وقت</option>
                        <option value="part_time" @selected(old('employment_type', $jobPosition?->employment_type) === 'part_time')>پاره وقت</option>
                        <option value="project_based" @selected(old('employment_type', $jobPosition?->employment_type) === 'project_based')>پروژه‌ای</option>
                        <option value="internship" @selected(old('employment_type', $jobPosition?->employment_type) === 'internship')>کارآموزی</option>
                        <option value="contract" @selected(old('employment_type', $jobPosition?->employment_type) === 'contract')>قراردادی</option>
                    </select>

                    <select class="rounded-xl border-slate-300" name="work_mode">
                        <option value="onsite" @selected(old('work_mode', $jobPosition?->work_mode ?? 'onsite') === 'onsite')>حضوری</option>
                        <option value="remote" @selected(old('work_mode', $jobPosition?->work_mode) === 'remote')>دورکار</option>
                        <option value="hybrid" @selected(old('work_mode', $jobPosition?->work_mode) === 'hybrid')>هیبرید</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-600">بازه حقوق (اختیاری)</label>
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-1">
                    <input type="number" class="rounded-xl border-slate-300" name="salary_min" value="{{ old('salary_min', $jobPosition?->salary_min) }}" placeholder="حداقل حقوق">
                    <input type="number" class="rounded-xl border-slate-300" name="salary_max" value="{{ old('salary_max', $jobPosition?->salary_max) }}" placeholder="حداکثر حقوق">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-600">وضعیت انتشار</label>
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-1">
                    <select class="rounded-xl border-slate-300" name="status">
                        <option value="draft" @selected(old('status', $jobPosition?->status ?? 'draft') === 'draft')>پیش‌نویس</option>
                        <option value="published" @selected(old('status', $jobPosition?->status) === 'published')>منتشر شده</option>
                        <option value="paused" @selected(old('status', $jobPosition?->status) === 'paused')>متوقف شده</option>
                        <option value="closed" @selected(old('status', $jobPosition?->status) === 'closed')>بسته شده</option>
                        <option value="archived" @selected(old('status', $jobPosition?->status) === 'archived')>آرشیو</option>
                    </select>
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="is_public" value="1" @checked((bool) old('is_public', $jobPosition?->is_public))>
                        نمایش در لندینگ
                    </label>
                </div>
            </div>

            <button class="rounded-xl bg-cyan-600 px-4 py-2 text-white">ذخیره</button>
        </div>
    </aside>
</form>
