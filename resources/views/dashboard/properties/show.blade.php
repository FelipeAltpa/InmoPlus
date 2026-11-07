<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $property->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.properties.edit', $property) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Editar
                </a>
                <a href="{{ route('dashboard.properties.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-2 space-y-6">
                                        <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($property->operation_type === 'venta')
                                bg-blue-100 text-blue-800
                            @else
                                bg-green-100 text-green-800
                            @endif
                        ">
                            {{ ucfirst($property->operation_type) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            {{ $property->propertyType->name }}
                        </span>
                    </div>

                                        <x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" class="text-3xl font-bold text-gray-900" />

                                        <div class="flex items-center text-gray-600">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $property->address }}
                    </div>

                                        <div class="prose max-w-none">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Descripción</h3>
                        <p class="text-gray-600 whitespace-pre-wrap">{{ $property->description }}</p>
                    </div>

                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 rounded-lg p-6">
                        <div>
                            <p class="text-sm text-gray-500">Dormitorios</p>
                            <p class="text-lg font-medium text-gray-900">{{ $property->bedrooms }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Baños</p>
                            <p class="text-lg font-medium text-gray-900">{{ $property->bathrooms }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Área (m²)</p>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($property->area, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Precio/m²</p>
                            <x-price :amount="$property->area ? $property->price / $property->area : 0" :unit="$property->operation_type === 'alquiler' ? ' /m²/mes' : ' /m²'" class="text-lg font-medium text-gray-900" />
                        </div>
                    </div>

                                        @if($property->features && count($property->features) > 0)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Características</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($property->features as $feature)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $feature }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                                        @if($property->latitude && $property->longitude)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Ubicación</h3>
                        <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center">
                            <p class="text-gray-500">Mapa: {{ $property->latitude }}, {{ $property->longitude }}</p>
                        </div>
                    </div>
                    @endif

                                        @if($property->main_image)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Imágenes</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                                <div class="space-y-6">
                                        <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Agente</h3>
                        <div class="flex items-center">
                            @if($property->user->profile_photo)
                                <img src="{{ asset('storage/' . $property->user->profile_photo) }}" alt="{{ $property->user->name }}" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-2xl font-medium text-indigo-600">{{ strtoupper($property->user->name[0]) }}</span>
                                </div>
                            @endif
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ $property->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $property->user->email }}</p>
                                @if($property->user->phone)
                                    <p class="text-sm text-gray-500">{{ $property->user->phone }}</p>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($property->user->isAdmin())
                                        bg-purple-100 text-purple-800
                                    @else
                                        bg-blue-100 text-blue-800
                                    @endif
                                ">
                                    {{ $property->user->isAdmin() ? 'Administrador' : 'Agente' }}
                                </span>
                            </div>
                        </div>
                    </div>

                                        <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-gray-500">Creada</dt>
                                <dd class="text-sm text-gray-900">{{ $property->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Actualizada</dt>
                                <dd class="text-sm text-gray-900">{{ $property->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">ID</dt>
                                <dd class="text-sm text-gray-900 font-mono">{{ $property->id }}</dd>
                            </div>
                        </dl>
                    </div>

                                        <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones</h3>
                        <div class="space-y-2">
                            <a href="{{ route('dashboard.properties.edit', $property) }}" class="block w-full text-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Editar Propiedad
                            </a>
                            <form method="POST" action="{{ route('dashboard.properties.destroy', $property) }}" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta propiedad?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100">
                                    Eliminar Propiedad
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
