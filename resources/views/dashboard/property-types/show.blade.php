<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $propertyType->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('property-types.edit', $propertyType) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Editar
                </a>
                <a href="{{ route('property-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $propertyType->name }}</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <dt class="text-sm text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($propertyType->status === 'active')
                                    bg-green-100 text-green-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ $propertyType->status === 'active' ? 'Activo' : 'Inactivo' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Propiedades asociadas</dt>
                        <dd class="mt-1 text-lg font-medium text-gray-900">{{ $propertyType->properties_count ?? 0 }}</dd>
                    </div>
                </div>

                @if($propertyType->description)
                <div class="mb-6">
                    <dt class="text-sm text-gray-500">Descripción</dt>
                    <dd class="mt-1 text-gray-900">{{ $propertyType->description }}</dd>
                </div>
                @endif

                <div class="border-t border-gray-200 pt-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Creado</dt>
                            <dd class="text-sm text-gray-900">{{ $propertyType->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Actualizado</dt>
                            <dd class="text-sm text-gray-900">{{ $propertyType->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">ID</dt>
                            <dd class="text-sm text-gray-900 font-mono">{{ $propertyType->id }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

                        @if($propertyType->properties_count > 0)
            <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Propiedades de este tipo</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propiedad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operación</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agente</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($propertyType->properties()->with('user')->latest()->take(10)->get() as $property)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $property->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $property->address }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" /></td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $property->user->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($propertyType->properties_count > 10)
                <div class="px-6 py-4 border-t border-gray-200 text-center">
                    <a href="{{ route('dashboard.properties.index', ['property_type_id' => $propertyType->id]) }}" class="text-indigo-600 hover:text-indigo-900">Ver todas las {{ $propertyType->properties_count }} propiedades</a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
