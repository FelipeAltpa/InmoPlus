<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Propiedad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('dashboard.properties.update', $property) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                                        @if($property->main_image)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen Actual</label>
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->title }}" class="max-w-xs h-auto rounded-lg border border-gray-200">
                        </div>
                        <p class="text-sm text-gray-500">Deja vacío para conservar la imagen actual, o sube una nueva para reemplazarla.</p>
                    </div>
                    @endif

                                        <div class="mb-6">
                        <label for="main_image" class="block text-sm font-medium text-gray-700 mb-1">Nueva Imagen Principal</label>
                        <input type="file" name="main_image" id="main_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('main_image') border-red-500 @enderror">
                        @error('main_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fotos adicionales (máximo 3)</label>
                        @if($property->galleryImages->isNotEmpty())
                            <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                @foreach($property->galleryImages as $image)
                                    <label class="rounded-lg border border-gray-200 p-2">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Foto adicional de {{ $property->title }}" class="mb-2 aspect-video w-full rounded object-cover">
                                        <span class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="remove_gallery_images[]" value="{{ $image->id }}" class="rounded border-gray-300 text-red-600"> Eliminar foto</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="mb-3 text-sm text-gray-500">Todavía no hay fotos adicionales.</p>
                        @endif
                        <label for="gallery_images" class="mb-1 block text-sm text-gray-700">Agregar o reemplazar fotos</label>
                        <input type="file" name="gallery_images[]" id="gallery_images" accept="image/jpeg,image/png,image/webp" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700">
                        <p class="mt-1 text-xs text-gray-500">Marca las fotos que quieras quitar y selecciona otras para reemplazarlas. Hasta 3 en total; 2 MB por imagen.</p>
                        @error('gallery_images')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        @error('gallery_images.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                                        <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="4" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $property->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                        <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="mb-6">
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ciudad <span class="text-red-500">*</span></label>
                        <input type="text" name="city" id="city" value="{{ old('city', $property->city) }}" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="mb-6">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio (COP; alquiler mensual) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" id="price" value="{{ old('price', $property->price) }}" step="0.01" min="0" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="operation_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Operación <span class="text-red-500">*</span></label>
                            <select name="operation_type" id="operation_type" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('operation_type') border-red-500 @enderror">
                                @foreach($operationTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('operation_type', $property->operation_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('operation_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="property_type_id" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Propiedad <span class="text-red-500">*</span></label>
                            <select name="property_type_id" id="property_type_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('property_type_id') border-red-500 @enderror">
                                @foreach($propertyTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('property_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Dormitorios <span class="text-red-500">*</span></label>
                            <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('bedrooms') border-red-500 @enderror">
                            @error('bedrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">Baños <span class="text-red-500">*</span></label>
                            <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('bathrooms') border-red-500 @enderror">
                            @error('bathrooms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="area" class="block text-sm font-medium text-gray-700 mb-1">Área (m²) <span class="text-red-500">*</span></label>
                            <input type="number" name="area" id="area" value="{{ old('area', $property->area) }}" step="0.01" min="0" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('area') border-red-500 @enderror">
                            @error('area')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                                        <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $property->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Características</label>
                        <div id="features-container">
                            @foreach(old('features', $property->features ?? []) as $index => $feature)
                                <div class="flex gap-2 mb-2">
                                    <input type="text" name="features[]" value="{{ $feature }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Ej: jardín, garaje, piscina">
                                    <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Eliminar</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addFeature()" class="text-sm text-indigo-600 hover:text-indigo-900">+ Agregar característica</button>
                    </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitud</label>
                            <input type="number" name="latitude" id="latitude" value="{{ old('latitude', $property->latitude) }}" step="any" min="-90" max="90" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('latitude') border-red-500 @enderror">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitud</label>
                            <input type="number" name="longitude" id="longitude" value="{{ old('longitude', $property->longitude) }}" step="any" min="-180" max="180" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('longitude') border-red-500 @enderror">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                                        <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('dashboard.properties.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Actualizar Propiedad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function addFeature() {
        const container = document.getElementById('features-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2 mb-2';
        div.innerHTML = `
            <input type="text" name="features[]" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Ej: jardín, garaje, piscina">
            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Eliminar</button>
        `;
        container.appendChild(div);
    }

</script>
