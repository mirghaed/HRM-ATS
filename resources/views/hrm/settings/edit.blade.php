@extends('layouts.hrm', ['title' => 'تنظیمات'])

@section('content')
<div class="space-y-6" x-data="{ tab: 'general' }">
    <div class="card p-6">
        <h2 class="text-xl font-black text-slate-900">تنظیمات سیستم</h2>
        <p class="mt-2 text-sm text-slate-500">تغییرات این صفحه بلافاصله در پنل و لندینگ اعمال می‌شوند.</p>
    </div>

    <div class="card p-4">
        <div class="flex flex-wrap gap-2 text-sm">
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='general' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='general'">عمومی</button>
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='recruitment' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='recruitment'">جذب</button>
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='sources' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='sources'">منابع رزومه</button>
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='sms' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='sms'">SMS</button>
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='landing' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='landing'">لندینگ</button>
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='process' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='process'">فرایند</button>
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='file' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='file'">فایل</button>
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='notifications' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='notifications'">اعلان‌ها</button>
            <button type="button" class="rounded-xl px-3 py-2" :class="tab==='security' ? 'bg-cyan-600 text-white' : 'bg-slate-100'" @click="tab='security'">امنیت</button>
        </div>
        <p class="mt-3 text-xs text-slate-500">وضعیت‌ها، دلایل رد، قالب‌های پیامک و منابع را از منوهای تخصصی پنل مدیریت کنید.</p>
    </div>

    <form method="post" action="{{ route('hrm.settings.update') }}" class="space-y-6">
        @csrf
        @php $idx = 0; @endphp

        @foreach($settings as $group => $items)
            <div class="card p-6" x-show="tab === '{{ $group }}'" x-cloak>
                <h3 class="mb-4 text-lg font-black text-slate-900">{{ match($group) {
                    'general' => 'تنظیمات عمومی',
                    'recruitment' => 'تنظیمات جذب',
                    'sources' => 'منابع رزومه',
                    'sms' => 'تنظیمات پیامک',
                    'landing' => 'لندینگ و تجربه کارجو',
                    'process' => 'فرایند استخدام',
                    'file' => 'فایل و آپلود',
                    'notifications' => 'اعلان‌ها',
                    'security' => 'امنیت',
                    default => $group,
                } }}</h3>

                <div class="grid gap-4 md:grid-cols-2">
                    @foreach($items as $item)
                        <div class="rounded-2xl border border-slate-200 p-4 md:col-span-{{ in_array($item['input'], ['textarea', 'gallery', 'logo', 'logo_dark'], true) ? '2' : '1' }}">
                            <input type="hidden" name="settings[{{ $idx }}][key]" value="{{ $item['key'] }}">
                            <input type="hidden" name="settings[{{ $idx }}][type]" value="{{ $item['type'] }}">
                            <input type="hidden" name="settings[{{ $idx }}][group]" value="{{ $item['group'] }}">
                            <input type="hidden" name="settings[{{ $idx }}][is_public]" value="0">

                            <label class="mb-1 block text-sm font-bold text-slate-800">{{ $item['label'] }}</label>
                            <p class="mb-3 text-xs text-slate-500">{{ $item['help'] }}</p>

                            @if($item['input'] === 'textarea')
                                <textarea class="w-full rounded-xl border-slate-300" rows="3" name="settings[{{ $idx }}][value]">{{ old("settings.$idx.value", $item['value']) }}</textarea>
                            @elseif($item['input'] === 'gallery')
                                @php
                                    $galleryRows = old("settings.$idx.value");
                                    if (is_string($galleryRows)) {
                                        $decodedSlides = json_decode($galleryRows, true);
                                        $galleryRows = is_array($decodedSlides) ? $decodedSlides : null;
                                    }
                                    if (!is_array($galleryRows)) {
                                        $galleryRows = is_array($item['value']) ? $item['value'] : [];
                                    }
                                @endphp
                                <div class="space-y-3" x-data="gallerySettingEditor(@js($galleryRows), '{{ route('hrm.settings.gallery-upload') }}', '{{ csrf_token() }}')">
                                    <input type="hidden" name="settings[{{ $idx }}][value]" :value="jsonValue">

                                    <template x-if="slides.length === 0">
                                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-xs text-slate-600">
                                            اسلایدی ثبت نشده است. با دکمه «افزودن اسلاید» شروع کنید.
                                        </div>
                                    </template>

                                    <template x-for="(slide, slideIndex) in slides" :key="slide.uuid">
                                        <div class="rounded-2xl border border-slate-200 p-3">
                                            <div class="mb-3 grid gap-3 md:grid-cols-12">
                                                <div class="md:col-span-6">
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">مسیر تصویر</label>
                                                    <input type="text" class="w-full rounded-xl border-slate-300" x-model.trim="slide.image" placeholder="/media/careers/gallery/gallery-01.jpg">
                                                </div>
                                                <div class="md:col-span-4">
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">متن جایگزین (Alt)</label>
                                                    <input type="text" class="w-full rounded-xl border-slate-300" x-model.trim="slide.alt" placeholder="توضیح تصویر">
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">ترتیب</label>
                                                    <input type="number" min="0" class="w-full rounded-xl border-slate-300" x-model.number="slide.sort_order">
                                                </div>
                                            </div>

                                            <div class="mb-3 grid gap-3 md:grid-cols-12">
                                                <div class="md:col-span-8">
                                                    <label class="mb-1 block text-xs font-semibold text-slate-600">آپلود از سیستم</label>
                                                    <input type="file" accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border-slate-300 text-xs" @change="setSlideFile($event, slideIndex)">
                                                </div>
                                                <div class="md:col-span-4 flex items-end gap-2">
                                                    <button type="button" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700" @click="uploadSlideFile(slideIndex)" :disabled="uploadingIndex === slideIndex">
                                                        <span x-show="uploadingIndex !== slideIndex">آپلود تصویر</span>
                                                        <span x-show="uploadingIndex === slideIndex" x-cloak>در حال آپلود...</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <p class="mb-2 text-xs text-rose-600" x-show="slide.error" x-text="slide.error" x-cloak></p>
                                            <img x-show="slide.image" :src="slide.image" alt="" class="mb-2 h-20 w-auto rounded-lg border border-slate-200 bg-slate-50 object-cover" x-cloak>

                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <span class="text-xs text-slate-500" x-text="'اسلاید ' + (slideIndex + 1)"></span>
                                                <div class="flex items-center gap-2">
                                                    <button type="button" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700" @click="moveUp(slideIndex)" :disabled="slideIndex === 0">بالا</button>
                                                    <button type="button" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700" @click="moveDown(slideIndex)" :disabled="slideIndex === slides.length - 1">پایین</button>
                                                    <button type="button" class="rounded-lg border border-rose-200 bg-rose-50 px-2 py-1 text-xs text-rose-700" @click="removeSlide(slideIndex)">حذف</button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <div>
                                        <button type="button" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700" @click="addSlide()">افزودن اسلاید</button>
                                    </div>
                                </div>
                            @elseif(in_array($item['input'], ['logo', 'logo_dark'], true))
                                @php
                                    $logoPath = old("settings.$idx.value", $item['value']);
                                    $logoField = $item['input'] === 'logo_dark' ? 'logo_dark' : 'logo';
                                    $logoPlaceholder = $item['input'] === 'logo_dark'
                                        ? '/media/brand/logos-dark/logo-dark.png'
                                        : '/media/brand/logos/logo.png';
                                    $logoPreviewClass = $item['input'] === 'logo_dark'
                                        ? 'h-16 w-auto rounded-lg border border-slate-700 bg-slate-900 object-contain p-2'
                                        : 'h-16 w-auto rounded-lg border border-slate-200 bg-slate-50 object-contain p-2';
                                @endphp
                                <div class="space-y-3" x-data="logoSettingEditor(@js((string) $logoPath), '{{ route('hrm.settings.gallery-upload') }}', '{{ csrf_token() }}', @js($logoField))">
                                    <div>
                                        <label class="mb-1 block text-xs font-semibold text-slate-600">مسیر یا آدرس لوگو</label>
                                        <input type="text" class="w-full rounded-xl border-slate-300" name="settings[{{ $idx }}][value]" x-model.trim="logoPath" placeholder="{{ $logoPlaceholder }}">
                                    </div>

                                    <div class="grid gap-3 md:grid-cols-12">
                                        <div class="md:col-span-8">
                                            <label class="mb-1 block text-xs font-semibold text-slate-600">آپلود از سیستم</label>
                                            <input type="file" accept=".svg,.png,.jpg,.jpeg,.webp,image/svg+xml,image/png,image/jpeg,image/webp" class="w-full rounded-xl border-slate-300 text-xs" @change="setLogoFile($event)">
                                        </div>
                                        <div class="md:col-span-4 flex items-end">
                                            <button type="button" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700" @click="uploadLogo()" :disabled="uploading">
                                                <span x-show="!uploading">آپلود لوگو</span>
                                                <span x-show="uploading" x-cloak>در حال آپلود...</span>
                                            </button>
                                        </div>
                                    </div>

                                    <p class="text-xs text-rose-600" x-show="error" x-text="error" x-cloak></p>
                                    <p class="text-xs text-slate-500">فرمت‌های مجاز: SVG، PNG، JPG، WEBP — حداکثر ۲ مگابایت</p>

                                    <img x-show="previewUrl" :src="previewUrl" alt="پیش‌نمایش لوگو" class="{{ $logoPreviewClass }}" x-cloak>
                                </div>
                            @elseif($item['input'] === 'boolean')
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input type="hidden" name="settings[{{ $idx }}][value]" value="0">
                                    <input type="checkbox" class="rounded border-slate-300 text-cyan-600" name="settings[{{ $idx }}][value]" value="1" @checked((bool) old("settings.$idx.value", $item['value']))>
                                    فعال باشد
                                </label>
                            @else
                                <input type="{{ $item['input'] === 'number' ? 'number' : 'text' }}" class="w-full rounded-xl border-slate-300" name="settings[{{ $idx }}][value]" value="{{ old("settings.$idx.value", $item['value']) }}">
                            @endif
                        </div>
                        @php $idx++; @endphp
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button class="rounded-xl bg-cyan-600 px-5 py-2.5 font-semibold text-white hover:bg-cyan-700">ذخیره تنظیمات</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function gallerySettingEditor(initialSlides, uploadUrl, csrfToken) {
        return {
            uploadUrl,
            csrfToken,
            selectedFiles: {},
            uploadingIndex: null,
            slides: Array.isArray(initialSlides)
                ? initialSlides.map((item, index) => ({
                    uuid: crypto.randomUUID ? crypto.randomUUID() : `slide-${Date.now()}-${index}`,
                    image: (item && item.image) ? String(item.image) : '',
                    alt: (item && item.alt) ? String(item.alt) : '',
                    sort_order: Number(item && item.sort_order ? item.sort_order : ((index + 1) * 10)),
                    error: '',
                }))
                : [],
            addSlide() {
                const nextOrder = this.slides.length
                    ? Math.max(...this.slides.map((item) => Number(item.sort_order) || 0)) + 10
                    : 10;

                this.slides.push({
                    uuid: crypto.randomUUID ? crypto.randomUUID() : `slide-${Date.now()}-${this.slides.length}`,
                    image: '',
                    alt: '',
                    sort_order: nextOrder,
                    error: '',
                });
            },
            removeSlide(index) {
                this.slides.splice(index, 1);
                delete this.selectedFiles[index];
            },
            moveUp(index) {
                if (index <= 0) {
                    return;
                }
                const item = this.slides.splice(index, 1)[0];
                this.slides.splice(index - 1, 0, item);
                this.selectedFiles = {};
            },
            moveDown(index) {
                if (index >= this.slides.length - 1) {
                    return;
                }
                const item = this.slides.splice(index, 1)[0];
                this.slides.splice(index + 1, 0, item);
                this.selectedFiles = {};
            },
            setSlideFile(event, index) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                this.selectedFiles[index] = file || null;
                if (this.slides[index]) {
                    this.slides[index].error = '';
                }
            },
            async uploadSlideFile(index) {
                const slide = this.slides[index];
                const file = this.selectedFiles[index];

                if (!slide) {
                    return;
                }

                slide.error = '';

                if (!file) {
                    slide.error = 'لطفا ابتدا فایل تصویر را انتخاب کنید.';
                    return;
                }

                const allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
                const lowerName = String(file.name || '').toLowerCase();
                const allowedExt = ['.jpg', '.jpeg', '.png', '.webp', '.avif'];
                const hasAllowedExt = allowedExt.some((ext) => lowerName.endsWith(ext));

                if (!(allowedMime.includes(file.type) || hasAllowedExt)) {
                    slide.error = 'فرمت فایل باید JPG، PNG، WEBP یا AVIF باشد.';
                    return;
                }

                if (file.size > (5 * 1024 * 1024)) {
                    slide.error = 'حجم تصویر نباید بیشتر از ۵ مگابایت باشد.';
                    return;
                }

                this.uploadingIndex = index;

                try {
                    const formData = new FormData();
                    formData.append('image', file);

                    const response = await fetch(this.uploadUrl, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const isJson = (response.headers.get('content-type') || '').includes('application/json');
                    const payload = isJson ? await response.json() : null;

                    if (!response.ok || !payload || payload.status !== 'ok') {
                        if (response.status === 422 && payload?.errors) {
                            const imageErrors = payload.errors.image || payload.errors.logo || payload.errors.logo_dark;
                            slide.error = Array.isArray(imageErrors) ? imageErrors[0] : (payload.message || 'آپلود تصویر انجام نشد. دوباره تلاش کنید.');
                        } else {
                            slide.error = payload?.message || 'آپلود تصویر انجام نشد. دوباره تلاش کنید.';
                        }
                        return;
                    }

                    slide.image = payload.path || payload.url || '';
                    this.selectedFiles[index] = null;
                } catch (error) {
                    slide.error = 'ارتباط با سرور برقرار نشد. دوباره تلاش کنید.';
                } finally {
                    this.uploadingIndex = null;
                }
            },
            get jsonValue() {
                const normalized = this.slides
                    .map((item, index) => ({
                        image: String(item.image || '').trim(),
                        alt: String(item.alt || '').trim(),
                        sort_order: Number(item.sort_order || ((index + 1) * 10)),
                    }))
                    .filter((item) => item.image.length > 0);

                return JSON.stringify(normalized);
            },
        };
    }

    function logoSettingEditor(initialPath, uploadUrl, csrfToken, fieldName = 'logo') {
        return {
            uploadUrl,
            csrfToken,
            fieldName,
            logoPath: String(initialPath || ''),
            selectedFile: null,
            uploading: false,
            error: '',
            get previewUrl() {
                const path = this.logoPath.trim();
                if (!path) {
                    return '';
                }

                if (path.startsWith('http://') || path.startsWith('https://')) {
                    return path;
                }

                return path.startsWith('/') ? path : `/${path}`;
            },
            setLogoFile(event) {
                this.selectedFile = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                this.error = '';
            },
            async uploadLogo() {
                this.error = '';

                if (!this.selectedFile) {
                    this.error = 'لطفاً ابتدا فایل لوگو را انتخاب کنید.';
                    return;
                }

                const allowedMime = ['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'];
                const lowerName = String(this.selectedFile.name || '').toLowerCase();
                const allowedExt = ['.svg', '.png', '.jpg', '.jpeg', '.webp'];
                const hasAllowedExt = allowedExt.some((ext) => lowerName.endsWith(ext));

                if (!(allowedMime.includes(this.selectedFile.type) || hasAllowedExt)) {
                    this.error = 'فرمت فایل باید SVG، PNG، JPG یا WEBP باشد.';
                    return;
                }

                if (this.selectedFile.size > (2 * 1024 * 1024)) {
                    this.error = 'حجم لوگو نباید بیشتر از ۲ مگابایت باشد.';
                    return;
                }

                this.uploading = true;

                try {
                    const formData = new FormData();
                    formData.append(this.fieldName, this.selectedFile);

                    const response = await fetch(this.uploadUrl, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const isJson = (response.headers.get('content-type') || '').includes('application/json');
                    const payload = isJson ? await response.json() : null;

                    if (!response.ok || !payload || payload.status !== 'ok') {
                        this.error = payload?.message || 'آپلود لوگو انجام نشد. دوباره تلاش کنید.';
                        return;
                    }

                    this.logoPath = payload.path || payload.url || '';
                    this.selectedFile = null;
                } catch (error) {
                    this.error = 'ارتباط با سرور برقرار نشد. دوباره تلاش کنید.';
                } finally {
                    this.uploading = false;
                }
            },
        };
    }
</script>
@endpush
