<!doctype html>
<html lang="fa" dir="rtl" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $layoutCompanyName = $hrmSettings['company.name'] ?? config('app.name', 'Brand');
    @endphp

    <title>{{ $title ?? ('فرصت‌های شغلی ' . $layoutCompanyName) }}</title>
    <meta name="description" content="{{ $description ?? ('فرصت‌های شغلی ' . $layoutCompanyName . ' را ببینید و رزومه خود را برای تیم‌های فعال ارسال کنید.') }}">
    @stack('meta')

    <script>
        (function () {
            try {
                const saved = localStorage.getItem('yadak-theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved === 'dark' || (!saved && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {
                // Ignore theme persistence errors.
            }
        })();
    </script>

    @include('layouts.partials.vite')
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-full">
        @if(session('success'))
            <div class="fixed left-0 right-0 top-4 z-[90] mx-auto w-full max-w-3xl px-4">
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm dark:border-emerald-700/40 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="fixed left-0 right-0 top-4 z-[90] mx-auto w-full max-w-3xl px-4">
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm dark:border-rose-700/40 dark:bg-rose-950/40 dark:text-rose-200">
                    <p class="font-bold">لطفا خطاهای فرم را بررسی کنید:</p>
                    <ul class="mt-2 list-disc space-y-1 pr-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <main>
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    @stack('structured-data')
</body>
</html>
