<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-end"><a href="{{ route('users.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-white">Crear usuario</a></div>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($users->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay usuarios</h3>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verificado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propiedades</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado / acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($user->profile_photo)
                                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-indigo-600">{{ strtoupper($user->name[0]) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->phone ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($user->isAdmin())
                                                bg-purple-100 text-purple-800
                                            @elseif($user->isAgent())
                                                bg-blue-100 text-blue-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            {{ $user->isAdmin() ? 'Administrador' : ($user->isAgent() ? 'Agente' : ucfirst($user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Sí
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->properties_count ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="flex items-center gap-3"><span class="text-xs {{ $user->is_active ? 'text-green-700' : 'text-red-700' }}">{{ $user->is_active ? 'Activo' : 'Inactivo' }}</span><a class="text-indigo-700 text-sm" href="{{ route('users.edit',$user) }}">Editar</a>@if(!$user->is($authUser = auth()->user()))<form method="POST" action="{{ route('users.toggle',$user) }}">@csrf @method('PATCH')<button class="text-sm text-red-700">{{ $user->is_active ? 'Desactivar' : 'Activar' }}</button></form>@endif</div></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                                        <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
