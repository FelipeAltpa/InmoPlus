<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Acceso' }} | InmoPlus</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased has-display">
    <main class="relative isolate flex min-h-screen items-center justify-center overflow-hidden px-4 py-12 sm:px-6">
        <div class="absolute inset-x-0 top-0 -z-10 h-80 bg-blue-100/60"></div>

        <div class="w-full max-w-md">
            <a href="{{ route('home') }}" class="mb-7 flex items-center justify-center gap-3 text-slate-900">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-500 text-white shadow-sm">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10Z"/></svg>
                </span>
                <span><span class="block font-display text-2xl font-semibold tracking-tight text-blue-900">InmoPlus</span><span class="block text-sm text-slate-500">Tu próximo hogar empieza aquí</span></span>
            </a>

            <section class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-[0_24px_60px_-28px_rgb(16_53_46/0.45)] sm:p-8">
                {{ $slot }}
            </section>

            <p class="mt-6 text-center text-xs text-slate-500">© {{ date('Y') }} InmoPlus · Inmobiliaria</p>
        </div>
    </main>
</body>
</html>
