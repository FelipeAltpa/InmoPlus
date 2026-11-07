<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                                        <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Perfil</label>
                        <div class="flex items-center gap-4">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="h-20 w-20 rounded-full object-cover">
                            @else
                                <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-2xl font-medium text-indigo-600">{{ strtoupper(Auth::user()->name[0]) }}</span>
                                </div>
                            @endif
                            <div>
                                <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Formatos: JPG, PNG, WEBP. Máx. 2MB. Mín. 100×100px.</p>
                            </div>
                        </div>
                    </div>

                                        <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="mb-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror" placeholder="+54 11 1234-5678">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                        <div class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if(Auth::user()->isAdmin())
                                    bg-purple-100 text-purple-800
                                @elseif(Auth::user()->isAgent())
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ Auth::user()->isAdmin() ? 'Administrador' : (Auth::user()->isAgent() ? 'Agente' : ucfirst(Auth::user()->role)) }}
                            </span>
                            <p class="mt-1 text-xs text-gray-500">El rol solo puede ser modificado por un administrador.</p>
                        </div>
                    </div>

                                        @if(!Auth::user()->hasVerifiedEmail())
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Email no verificado</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Tu dirección de email no ha sido verificada. Haz clic en el botón para enviar un enlace de verificación.</p>
                                    <div class="mt-2">
                                        <form method="POST" action="{{ route('verification.send') }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                Reenviar email de verificación
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar Cambios
                        </button>
                    </div>
                </form>

                @if(Auth::user()->profile_photo)
                    <form method="POST" action="{{ route('profile.photo.destroy') }}" onsubmit="return confirm('¿Eliminar tu foto de perfil? Se restaurará el avatar inicial.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100">
                            Eliminar foto
                        </button>
                    </form>
                @endif
            </div>

                        <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-1">Actualizar Contraseña</h3>
                <p class="text-sm text-gray-600 mb-4">Usa una contraseña larga y aleatoria para mantener tu cuenta segura.</p>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña Actual <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" id="current_password" autocomplete="current-password" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('current_password', 'updatePassword') border-red-500 @enderror">
                        @error('current_password', 'updatePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" autocomplete="new-password" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password', 'updatePassword') border-red-500 @enderror">
                        @error('password', 'updatePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password_confirmation', 'updatePassword') border-red-500 @enderror">
                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-green-600 font-medium">¡Contraseña actualizada!</p>
                    @endif

                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>

                        <div class="bg-white shadow rounded-lg p-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-red-800 mb-2">Eliminar Cuenta</h3>
                    <p class="text-red-700 mb-4">Una vez que se elimina la cuenta, todos sus recursos y datos se eliminarán permanentemente. Esta acción es irreversible.</p>
                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción es irreversible.')">
                        @csrf
                        @method('DELETE')
                        <div class="flex items-center gap-4">
                            <input type="password" name="password" placeholder="Confirmar contraseña" required class="flex-1 px-3 py-2 border border-red-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Eliminar Cuenta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>