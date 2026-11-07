<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'InmoPlus') }}{{ isset($title) ? ' - '.$title : '' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="min-h-screen bg-slate-50 font-sans antialiased text-slate-900 has-display" x-data="{ mobileMenuOpen: false }">
        @include('components.navbar')

        <main class="min-h-[70vh]">
            @isset($header)
                {{ $header }}
            @endisset
            @include('components.alerts')
            {{ $slot }}
        </main>

        <footer class="mt-12 border-t border-slate-200/60 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-5 px-6 py-8 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-semibold text-slate-900 has-display">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-500 text-white" aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                    </span>
                    <span>InmoPlus</span>
                </a>
                <nav class="flex flex-wrap gap-x-6 gap-y-2" aria-label="Enlaces del pie de página">
                    <a href="{{ route('public.properties.index') }}" class="transition hover:text-slate-900 has-display">Propiedades</a>
                    <a href="{{ route('public.properties.index', ['operation_type' => 'venta']) }}" class="transition hover:text-slate-900 has-display">En venta</a>
                    <a href="{{ route('public.properties.index', ['operation_type' => 'alquiler']) }}" class="transition hover:text-slate-900 has-display">En alquiler</a>
                    <a href="{{ url('/docs/api') }}" class="transition hover:text-slate-900 has-display">API (Swagger)</a>
                    @guest
                        <a href="{{ route('login') }}" class="transition hover:text-slate-900 has-display">Iniciar sesión</a>
                        @if (Route::has('register'))<a href="{{ route('register') }}" class="transition hover:text-slate-900 has-display">Crear cuenta</a>@endif
                    @else
                        @if(auth()->user()?->role === 'client')
                            <a href="{{ route('client.interests') }}" class="transition hover:text-slate-900 has-display">Mis intereses</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="transition hover:text-slate-900 has-display">Mi panel</a>
                        @endif
                    @endguest
                </nav>
                <p>© {{ date('Y') }} InmoPlus</p>
            </div>
        </footer>
        @stack('scripts')
    </body>
</html>
