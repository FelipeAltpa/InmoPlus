<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Propiedades') }}
            </h2>
            <a href="{{ route('dashboard.properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Propiedad
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($properties->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay propiedades</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza creando tu primera propiedad.</p>
                        <div class="mt-6">
                            <a href="{{ route('dashboard.properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Crear Propiedad
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table id="properties-table" class="min-w-full divide-y divide-gray-200 display responsive nowrap">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ciudad</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operación</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($properties as $property)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $property->id }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        @if($property->main_image)
                                            <img src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->title }}" class="h-12 w-16 object-cover rounded">
                                        @else
                                            <div class="h-12 w-16 bg-gray-100 rounded flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $property->title }}</div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $property->address }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-900">{{ $property->propertyType->name }}</td>
                                    <td class="px-3 py-3 text-sm text-gray-900">{{ $property->city }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" /></td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($property->operation_type === 'venta')
                                                bg-blue-100 text-blue-800
                                            @else
                                                bg-green-100 text-green-800
                                            @endif
                                        ">
                                            {{ ucfirst($property->operation_type) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($property->status === 'disponible')
                                                bg-green-100 text-green-800
                                            @elseif($property->status === 'reservado')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($property->status === 'vendido')
                                                bg-red-100 text-red-800
                                            @else
                                                bg-blue-100 text-blue-800
                                            @endif
                                        ">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('dashboard.properties.show', $property) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                        <a href="{{ route('dashboard.properties.edit', $property) }}" class="text-gray-600 hover:text-gray-900 mr-3">Editar</a>
                                        <form method="POST" action="{{ route('dashboard.properties.destroy', $property) }}" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta propiedad?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
