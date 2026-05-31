<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'YadaHRM') }}</title>

        @include('layouts.partials.vite')
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(8,145,178,0.18),transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(15,23,42,0.14),transparent_35%)]"></div>
            <div class="relative mx-auto flex min-h-screen w-full max-w-6xl items-center px-4 py-10">
                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>