<x-guest-layout>
    <div class="mb-7 text-center">
        <h1 class="text-3xl text-blue-900">Inicia sesión</h1>
        <p class="mt-2 text-sm text-slate-500">Accede para continuar con InmoPlus.</p>
    </div>

    <x-auth-session-status class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <x-input-label for="email" value="Correo electrónico" class="mb-1.5 text-slate-700" />
            <x-text-input id="email" class="block w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nombre@correo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="mb-1.5 flex items-center justify-between gap-3">
                <x-input-label for="password" value="Contraseña" class="text-slate-700" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-blue-600 hover:text-blue-700" href="{{ route('password.request') }}">¿La olvidaste?</a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" type="password" name="password" required autocomplete="current-password" placeholder="Tu contraseña" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex items-center gap-2 text-sm text-slate-600">
            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
            <span>Recordar mi sesión</span>
        </label>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-500 px-4 py-3 font-semibold text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">Entrar a mi cuenta</button>
    </form>

    @if (Route::has('register'))
        <p class="mt-6 border-t border-slate-100 pt-5 text-center text-sm text-slate-600">¿Aún no tienes cuenta? <a class="font-semibold text-blue-600 hover:text-blue-700" href="{{ route('register') }}">Crear cuenta</a></p>
    @endif
</x-guest-layout>
