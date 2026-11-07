@php($action = $action ?? route('public.properties.index'))
<form method="GET" action="{{ $action }}" class="bg-white shadow rounded-lg p-5 mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Título, descripción o ubicación" class="rounded-md border-gray-300 text-sm">
    <select name="operation_type" class="rounded-md border-gray-300 text-sm"><option value="">Venta y alquiler</option><option value="venta" @selected(request('operation_type')==='venta')>Venta</option><option value="alquiler" @selected(request('operation_type')==='alquiler')>Alquiler</option></select>
    <select name="property_type_id" class="rounded-md border-gray-300 text-sm"><option value="">Todos los tipos</option>@foreach($propertyTypes as $type)<option value="{{ $type->id }}" @selected(request('property_type_id')==$type->id)>{{ $type->name }}</option>@endforeach</select>
    <input name="city" value="{{ request('city') }}" placeholder="Ciudad" class="rounded-md border-gray-300 text-sm">
    <input name="neighborhood" value="{{ request('neighborhood') }}" placeholder="Barrio" class="rounded-md border-gray-300 text-sm">
    <input type="number" min="0" name="min_price" value="{{ request('min_price') }}" placeholder="Precio mínimo COP" class="rounded-md border-gray-300 text-sm">
    <input type="number" min="0" name="max_price" value="{{ request('max_price') }}" placeholder="Precio máximo COP" class="rounded-md border-gray-300 text-sm">
    <input type="number" min="0" name="bedrooms" value="{{ request('bedrooms') }}" placeholder="Habitaciones mín." class="rounded-md border-gray-300 text-sm">
    <input type="number" min="0" name="bathrooms" value="{{ request('bathrooms') }}" placeholder="Baños mín." class="rounded-md border-gray-300 text-sm">
    <input type="number" min="0" step="0.1" name="min_area" value="{{ request('min_area') }}" placeholder="Área mínima m²" class="rounded-md border-gray-300 text-sm">
    <select name="sort" class="rounded-md border-gray-300 text-sm"><option value="recent">Más recientes</option><option value="price_asc" @selected(request('sort')==='price_asc')>Menor precio</option><option value="price_desc" @selected(request('sort')==='price_desc')>Mayor precio</option><option value="area_desc" @selected(request('sort')==='area_desc')>Mayor área</option></select>
    <div class="flex gap-2"><button class="rounded-md bg-indigo-600 px-4 py-2 text-white text-sm">Buscar</button><a href="{{ $action }}" class="rounded-md border px-4 py-2 text-sm">Limpiar</a></div>
</form>
