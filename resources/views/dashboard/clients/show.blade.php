<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $client->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Editar
                </a>
                <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h3>
                    <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($client->status === 'active')
                            bg-green-100 text-green-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif
                    ">
                        {{ $client->status === 'active' ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm text-gray-500">Correo electrónico</dt>
                        <dd class="mt-1 text-gray-900">{{ $client->email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Teléfono</dt>
                        <dd class="mt-1 text-gray-900">{{ $client->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Identificación</dt>
                        <dd class="mt-1 text-gray-900">{{ $client->identification ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Ciudad</dt>
                        <dd class="mt-1 text-gray-900">{{ $client->city ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Dirección</dt>
                        <dd class="mt-1 text-gray-900">{{ $client->address ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $client->id }}</dd>
                    </div>
                </dl>

                <div class="border-t border-gray-200 pt-6 mt-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Creado</dt>
                            <dd class="text-sm text-gray-900">{{ $client->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Actualizado</dt>
                            <dd class="text-sm text-gray-900">{{ $client->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

                        @if($client->user)
            <div class="mt-6 bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cuenta de acceso</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Nombre de usuario</dt>
                        <dd class="mt-1 text-gray-900">{{ $client->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Correo de acceso</dt>
                        <dd class="mt-1 text-gray-900">{{ $client->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Rol</dt>
                        <dd class="mt-1 text-gray-900">{{ ucfirst($client->user->role) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Registrado</dt>
                        <dd class="mt-1 text-gray-900">{{ $client->user->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>