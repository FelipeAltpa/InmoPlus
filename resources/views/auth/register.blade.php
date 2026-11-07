<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-3xl text-blue-900">Crea tu cuenta</h1>
        <p class="mt-2 text-sm text-slate-500">Regístrate para gestionar tu experiencia inmobiliaria.</p>
    </div>

    <x-auth-session-status class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="name" value="Nombre completo" class="mb-1.5 text-slate-700" />
            <x-text-input id="name" class="block w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Tu nombre" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Correo electrónico" class="mb-1.5 text-slate-700" />
            <x-text-input id="email" class="block w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nombre@correo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="phone" value="Teléfono (opcional)" class="mb-1.5 text-slate-700" />
            <x-text-input id="phone" class="block w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" type="tel" name="phone" :value="old('phone')" autocomplete="tel" placeholder="+57 300 000 0000" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Contraseña" class="mb-1.5 text-slate-700" />
            <x-text-input id="password" class="block w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" type="password" name="password" required autocomplete="new-password" placeholder="Crea una contraseña segura" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmar contraseña" class="mb-1.5 text-slate-700" />
            <x-text-input id="password_confirmation" class="block w-full rounded-xl border-slate-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Escríbela nuevamente" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-500 px-4 py-3 font-semibold text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">Crear mi cuenta</button>
    </form>

    <p class="mt-6 border-t border-slate-100 pt-5 text-center text-sm text-slate-600">¿Ya tienes cuenta? <a class="font-semibold text-blue-600 hover:text-blue-700" href="{{ route('login') }}">Inicia sesión</a></p>
</x-guest-layout>
