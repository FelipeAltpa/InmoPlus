<x-public-layout :title="'Propiedades disponibles'">
    <x-slot name="header">
        <section class="mx-auto max-w-7xl px-6 py-8 lg:px-10 lg:py-12">
            <p class="text-sm font-medium text-blue-600">Espacio del cliente</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Encuentra tu próximo hogar</h1>
            <p class="mt-2 text-slate-500">Explora y guarda las propiedades que te interesan.</p>
        </section>
    </x-slot>

    <section class="mx-auto max-w-7xl px-6 pb-12 lg:px-10">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-slate-500">{{ $properties->total() }} {{ $properties->total() === 1 ? 'propiedad' : 'propiedades' }}</p>
            <a href="{{ route('client.interests') }}" class="inline-flex h-10 items-center rounded-full border border-slate-200/70 bg-white px-4 text-sm font-medium text-slate-700 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Mis propiedades de interés</a>
        </div>

        <details class="group rounded-2xl bg-white shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between px-5 py-4 text-sm font-medium text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"><span>Filtros de búsqueda</span><span class="text-slate-400 group-open:rotate-180">⌄</span></summary>
            <form method="GET" action="{{ route('client.properties.index') }}" class="grid gap-3 border-t border-slate-200/60 p-5 sm:grid-cols-2 lg:grid-cols-4">
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                <label class="text-xs font-medium text-slate-600">Buscar<input name="search" value="{{ request('search') }}" class="mt-1 h-10 w-full rounded-xl border border-slate-200/70 px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                <label class="text-xs font-medium text-slate-600">Operación<select name="operation_type" class="mt-1 h-10 w-full rounded-xl border border-slate-200/70 bg-white px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"><option value="">Venta y alquiler</option><option value="venta" @selected(request('operation_type')==='venta')>Venta</option><option value="alquiler" @selected(request('operation_type')==='alquiler')>Alquiler</option></select></label>
                <label class="text-xs font-medium text-slate-600">Tipo de inmueble<select name="property_type_id" class="mt-1 h-10 w-full rounded-xl border border-slate-200/70 bg-white px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"><option value="">Todos los tipos</option>@foreach($propertyTypes as $type)<option value="{{ $type->id }}" @selected(request('property_type_id')==$type->id)>{{ $type->name }}</option>@endforeach</select></label>
                <label class="text-xs font-medium text-slate-600">Precio mínimo<input type="number" min="0" name="min_price" value="{{ request('min_price') }}" class="mt-1 h-10 w-full rounded-xl border border-slate-200/70 px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                <label class="text-xs font-medium text-slate-600">Precio máximo<input type="number" min="0" name="max_price" value="{{ request('max_price') }}" class="mt-1 h-10 w-full rounded-xl border border-slate-200/70 px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                <label class="text-xs font-medium text-slate-600">Ciudad<input name="city" value="{{ request('city') }}" class="mt-1 h-10 w-full rounded-xl border border-slate-200/70 px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                <label class="text-xs font-medium text-slate-600">Barrio<input name="neighborhood" value="{{ request('neighborhood') }}" class="mt-1 h-10 w-full rounded-xl border border-slate-200/70 px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                <div class="flex items-end gap-2"><button class="h-10 rounded-full bg-blue-500 px-5 text-sm font-semibold text-white hover:bg-blue-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Aplicar</button><a href="{{ route('client.properties.index') }}" class="inline-flex h-10 items-center rounded-full px-4 text-sm text-slate-600 hover:bg-slate-100">Limpiar</a></div>
            </form>
        </details>

        @if($properties->isEmpty())
            <div class="mt-8 rounded-3xl bg-white px-6 py-16 text-center shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">No hay propiedades disponibles</h2>
                <p class="mt-2 text-sm text-slate-500">Prueba otros filtros o vuelve más tarde.</p>
            </div>
        @else
            <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($properties as $property)
                    @php($isInterested = in_array($property->id, $interestedIds, true))
                    <article class="group min-w-0">
                        <div class="relative aspect-[4/5] overflow-hidden rounded-3xl bg-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                                @if($property->main_image)
                                    <img src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="grid h-full place-items-center bg-slate-100 text-slate-300" aria-label="Imagen no disponible"><svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg></div>
                                @endif
                                <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-slate-700 backdrop-blur">{{ ucfirst($property->operation_type) }}</span>
                                @if($isInterested)<span class="absolute bottom-3 left-3 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-slate-900 backdrop-blur">En tus intereses</span>@endif
                                <form method="POST" action="{{ route($isInterested ? 'client.properties.uninterest' : 'client.properties.interest', $property) }}" class="absolute right-3 top-3">@csrf @if($isInterested)@method('DELETE')@endif<button type="submit" aria-label="{{ $isInterested ? 'Quitar de mis intereses' : 'Agregar a mis intereses' }}" class="grid h-10 w-10 place-items-center rounded-full bg-white/85 text-slate-700 shadow-sm backdrop-blur hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"><svg class="h-5 w-5 {{ $isInterested ? 'fill-rose-500 text-rose-500' : '' }}" viewBox="0 0 24 24" fill="{{ $isInterested ? 'currentColor' : 'none' }}" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 10-8.8 10s-8.8-4.8-8.8-10A4.8 4.8 0 0 1 12 6a4.8 4.8 0 0 1 8.8 2.8Z" stroke="currentColor" stroke-width="1.7"/></svg></button></form>
                        </div>
                        <a href="{{ route('client.properties.show', $property) }}" class="block">
                            <div class="pt-4">
                                <h2 class="truncate text-base font-semibold tracking-tight text-slate-900">{{ $property->title }}</h2>
                                <p class="mt-1 truncate text-sm text-slate-500">{{ $property->city }} · {{ $property->neighborhood }}</p>
                                <p class="mt-2 text-sm text-slate-500">Desde <x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" /></p>
                                <p class="mt-2 text-xs text-slate-400">{{ $property->bedrooms }} hab. · {{ $property->bathrooms }} baños · {{ number_format($property->area, 0, ',', '.') }} m²</p>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
            <div class="mt-10">{{ $properties->links() }}</div>
        @endif
    </section>
</x-public-layout>
