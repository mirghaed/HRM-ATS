<x-guest-layout>
    <div class="grid gap-8 lg:grid-cols-2 lg:items-stretch">
        <div class="hidden rounded-3xl bg-slate-950 p-8 text-white lg:block">
            <p class="text-xs text-cyan-300">{{ $hrmSettings['company.name'] ?? 'Yada' }} HRM / ATS</p>
            <h2 class="mt-4 text-3xl font-black leading-tight">ورود به پنل مدیریت جذب و استخدام</h2>
            <p class="mt-4 text-sm text-slate-300">وضعیت رزومه‌ها، مصاحبه‌ها، گزارش‌ها و فرآیند جذب را به‌صورت یکپارچه مدیریت کنید.</p>
            <div class="mt-8 space-y-3 text-sm">
                <div class="rounded-xl border border-white/10 bg-white/5 p-3">پیگیری کامل تایم‌لاین درخواست‌ها</div>
                <div class="rounded-xl border border-white/10 bg-white/5 p-3">مدیریت هوشمند وضعیت‌ها و مصاحبه‌ها</div>
                <div class="rounded-xl border border-white/10 bg-white/5 p-3">ارسال پیامک و گزارش‌گیری عملیاتی</div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h1 class="text-2xl font-black text-slate-900">ورود به پنل</h1>
                <p class="mt-1 text-sm text-slate-500">برای ادامه ایمیل و رمز عبور خود را وارد کنید.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form
                method="POST"
                action="{{ route('login') }}"
                class="space-y-4"
                x-data="{
                    captchaSrc: '{{ route('careers.captcha.image') }}?v={{ time() }}',
                    refreshCaptcha() {
                        this.captchaSrc = '{{ route('careers.captcha.image') }}?v=' + Date.now();
                    },
                }"
            >
                @csrf

                <div>
                    <x-input-label for="email" value="ایمیل" />
                    <x-text-input id="email" class="mt-1 block w-full rounded-xl border-slate-300" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="رمز عبور" />
                    <x-text-input id="password" class="mt-1 block w-full rounded-xl border-slate-300" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="ys-captcha">
                    <x-input-label for="captcha" value="کد امنیتی" />
                    <div class="ys-captcha__row mt-1">
                        <img :src="captchaSrc" alt="کد امنیتی" class="ys-captcha__image">
                        <input
                            id="captcha"
                            name="captcha"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9۰-۹٠-٩]{5}"
                            maxlength="5"
                            placeholder="کد ۵ رقمی"
                            value="{{ old('captcha') }}"
                            required
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                        >
                        <button type="button" class="ys-captcha__refresh" @click.stop="refreshCaptcha()" aria-label="بارگذاری مجدد کد امنیتی" title="بارگذاری مجدد کد امنیتی">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path d="M20.33 8.67A8 8 0 1 0 20 13h-2.5" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                <path d="M20 5v6h-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">کد عددی داخل تصویر را وارد کنید.</p>
                    <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between gap-4">
                    <label for="remember_me" class="inline-flex items-center text-sm text-slate-600">
                        <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500" name="remember">
                        <span class="ms-2">مرا به خاطر بسپار</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-cyan-700 hover:text-cyan-800" href="{{ route('password.request') }}">فراموشی رمز عبور</a>
                    @endif
                </div>

                <x-primary-button class="w-full justify-center rounded-xl bg-cyan-600 py-3 text-white hover:bg-cyan-700 focus:bg-cyan-700 active:bg-cyan-700">
                    ورود به پنل
                </x-primary-button>
            </form>
        </div>
    </div>
</x-guest-layout>
