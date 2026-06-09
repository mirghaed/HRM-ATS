<section id="general-apply" class="ys-section ys-section--alt">
    <div class="ys-container">
        <div class="ys-general-apply">
            <div>
                <span class="ys-kicker">ارسال رزومه عمومی</span>
                <h2>موقعیت مناسب خودت را پیدا نکردی؟</h2>
                <p>رزومه‌ات را ثبت کن تا وقتی فرصت مناسب در تیم‌های ما ایجاد شد، سریع‌تر بررسی‌ات کنیم.</p>

                @if(session('success'))
                    <div class="ys-inline-success" role="status" aria-live="polite">
                        رزومه شما با موفقیت ثبت شد. تیم جذب {{ $companyName }} رزومه شما را بررسی می‌کند.
                    </div>
                @endif

                <figure class="ys-general-apply__visual">
                    <x-career-picture
                        name="resume-upload"
                        alt="ارسال رزومه برای فرصت‌های شغلی"
                        width="1100"
                        height="760"
                        loading="lazy"
                        sizes="(max-width: 1024px) 100vw, 42vw"
                        class="ys-section-illustration"
                    />
                </figure>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ route('careers.general.apply') }}" class="ys-form" novalidate @submit="handleGeneralSubmit($event)">
                @csrf
                <div class="ys-form-feedback ys-form-feedback--success" x-show="submitState === 'success'" x-cloak>
                    <p x-text="submitMessage"></p>
                </div>
                <div class="ys-form-feedback ys-form-feedback--error" x-show="submitState === 'error'" x-cloak>
                    <p x-text="submitMessage"></p>
                    <ul x-show="submitErrors.length" class="ys-form-feedback__list">
                        <template x-for="(item, idx) in submitErrors" :key="idx">
                            <li x-text="item"></li>
                        </template>
                    </ul>
                </div>
                <div class="ys-form-grid">
                    <label>
                        <span>نام و نام خانوادگی *</span>
                        <input id="full_name" name="full_name" type="text" minlength="3" maxlength="120" value="{{ old('full_name') }}" required aria-describedby="full_name_help">
                        <small id="full_name_help">حداقل ۳ کاراکتر</small>
                        @error('full_name')<em>{{ $message }}</em>@enderror
                    </label>

                    <label>
                        <span>موبایل *</span>
                        <input id="mobile" name="mobile" type="tel" value="{{ old('mobile') }}" pattern="^09\d{9}$" inputmode="numeric" placeholder="09xxxxxxxxx" required>
                        <small>فرمت معتبر: 09xxxxxxxxx</small>
                        @error('mobile')<em>{{ $message }}</em>@enderror
                    </label>

                    <label>
                        <span>ایمیل</span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@example.com" class="ys-ltr-input">
                        @error('email')<em>{{ $message }}</em>@enderror
                    </label>

                    <label>
                        <span>تیم مورد علاقه</span>
                        <select name="preferred_department_id">
                            <option value="">انتخاب تیم</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected((string) old('preferred_department_id') === (string) $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('preferred_department_id')<em>{{ $message }}</em>@enderror
                    </label>

                    <label>
                        <span>عنوان شغلی موردنظر</span>
                        <input name="preferred_job_title" type="text" value="{{ old('preferred_job_title') }}" placeholder="مثال: کارشناس فروش قطعات" maxlength="150">
                        @error('preferred_job_title')<em>{{ $message }}</em>@enderror
                    </label>

                    <label>
                        <span>حقوق مورد انتظار (اختیاری)</span>
                        <input name="expected_salary" type="number" min="0" value="{{ old('expected_salary') }}" placeholder="مثال: 35000000">
                        <small>تومان</small>
                        @error('expected_salary')<em>{{ $message }}</em>@enderror
                    </label>

                    <label>
                        <span>لینک نمونه‌کار / LinkedIn</span>
                        <input name="portfolio_url" type="url" value="{{ old('portfolio_url') }}" placeholder="https://..." class="ys-ltr-input">
                        @error('portfolio_url')<em>{{ $message }}</em>@enderror
                    </label>

                    <label class="ys-form-grid__full">
                        <span>توضیح کوتاه</span>
                        <textarea name="cover_letter" rows="4" maxlength="1000">{{ old('cover_letter') }}</textarea>
                        <small>حداکثر ۱۰۰۰ کاراکتر</small>
                        @error('cover_letter')<em>{{ $message }}</em>@enderror
                    </label>

                    <label class="ys-form-grid__full ys-upload">
                        <span>فایل رزومه PDF *</span>
                        <input id="resume" name="resume" type="file" accept=".pdf,application/pdf" required @change="setResumeFileName($event)">
                        <div class="ys-upload__box">
                            <strong x-text="resumeFileName || 'فایل رزومه را انتخاب کنید (فقط PDF)'"></strong>
                            <small>حداکثر {{ (int) ($hrmSettings['upload.max_resume_size_kb'] ?? 10240) / 1024 }} مگابایت</small>
                        </div>
                        <em x-show="resumeClientError" x-text="resumeClientError" x-cloak></em>
                        @error('resume')<em>{{ $message }}</em>@enderror
                    </label>

                    <div class="ys-form-grid__full ys-captcha">
                        <span>کد امنیتی *</span>
                        <div class="ys-captcha__row">
                            <img :src="captchaSrc" alt="کد امنیتی" class="ys-captcha__image">
                            <input name="captcha" inputmode="numeric" pattern="[0-9۰-۹٠-٩]{5}" maxlength="5" placeholder="کد ۵ رقمی" required>
                            <button type="button" class="ys-captcha__refresh" @click.stop="refreshCaptcha()" aria-label="بارگذاری مجدد کد امنیتی" title="بارگذاری مجدد کد امنیتی">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                    <path d="M20.33 8.67A8 8 0 1 0 20 13h-2.5" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                    <path d="M20 5v6h-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                </svg>
                            </button>
                        </div>
                        <small>کد عددی داخل تصویر را وارد کنید.</small>
                        @error('captcha')<em>{{ $message }}</em>@enderror
                    </div>
                </div>

                <button type="submit" class="ys-btn ys-btn--primary ys-form__submit" :disabled="submitting" :class="submitting ? 'is-loading' : ''">
                    <span x-show="!submitting">ثبت رزومه</span>
                    <span x-show="submitting">در حال ارسال...</span>
                </button>
            </form>
        </div>
    </div>
</section>
