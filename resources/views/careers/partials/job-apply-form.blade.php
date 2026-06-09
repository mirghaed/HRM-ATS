<section class="ys-job-view__widget" id="apply-form">
    <div class="ys-job-view__widget-header">از اینجا شروع کنید</div>
    <div class="ys-job-view__widget-body">
        <p class="ys-job-view__widget-lead">رزومه خود را برای «{{ $jobPosition->title }}» ارسال کنید.</p>

        <form class="ys-job-view__form ys-form" method="post" enctype="multipart/form-data" action="{{ route('careers.jobs.apply', $jobPosition) }}" novalidate @submit="handleGeneralSubmit($event)">
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

            <div class="ys-job-view__form-fields">
                <label>
                    <span>نام و نام خانوادگی *</span>
                    <input name="full_name" value="{{ old('full_name') }}" required autocomplete="name">
                    @error('full_name')<em>{{ $message }}</em>@enderror
                </label>
                <label>
                    <span>موبایل *</span>
                    <input name="mobile" value="{{ old('mobile') }}" required inputmode="tel" autocomplete="tel" class="ys-ltr-input">
                    @error('mobile')<em>{{ $message }}</em>@enderror
                </label>
                <label>
                    <span>ایمیل</span>
                    <input name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="ys-ltr-input">
                    @error('email')<em>{{ $message }}</em>@enderror
                </label>
                <label>
                    <span>حقوق مورد انتظار (اختیاری)</span>
                    <input name="expected_salary" type="number" min="0" value="{{ old('expected_salary') }}" placeholder="مثال: 35000000" class="ys-ltr-input">
                    @error('expected_salary')<em>{{ $message }}</em>@enderror
                </label>
                <label class="ys-job-view__form-full">
                    <span>توضیح کوتاه</span>
                    <textarea name="cover_letter" rows="3" placeholder="در صورت تمایل، توضیح کوتاهی درباره خودتان بنویسید.">{{ old('cover_letter') }}</textarea>
                    @error('cover_letter')<em>{{ $message }}</em>@enderror
                </label>
                <label class="ys-job-view__form-full ys-upload">
                    <span>فایل رزومه (فقط PDF) *</span>
                    <input id="resume" type="file" name="resume" accept=".pdf,application/pdf" required @change="setResumeFileName($event)">
                    <div class="ys-upload__box ys-job-view__upload-box">
                        <strong x-text="resumeFileName || 'فایل PDF رزومه را انتخاب کنید'"></strong>
                    </div>
                    <em x-show="resumeClientError" x-text="resumeClientError" x-cloak></em>
                    @error('resume')<em>{{ $message }}</em>@enderror
                </label>

                @foreach($jobPosition->questions as $question)
                    <label class="ys-job-view__form-full">
                        <span>{{ $question->question }}</span>
                        <input name="answers[{{ $question->id }}]" value="{{ old('answers.' . $question->id) }}">
                    </label>
                @endforeach

                <div class="ys-job-view__form-full ys-captcha">
                    <span>کد امنیتی *</span>
                    <div class="ys-captcha__row">
                        <img :src="captchaSrc" alt="کد امنیتی" class="ys-captcha__image">
                        <input name="captcha" inputmode="numeric" pattern="[0-9۰-۹٠-٩]{5}" maxlength="5" placeholder="کد ۵ رقمی" required class="ys-ltr-input">
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

            <button type="submit" class="ys-job-view__submit" :disabled="submitting" :class="submitting ? 'is-loading' : ''">
                <span x-show="!submitting">ارسال رزومه</span>
                <span x-show="submitting">در حال ارسال...</span>
            </button>
        </form>
    </div>
</section>
