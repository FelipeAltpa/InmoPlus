<x-public-layout :title="'Inicio'">
    <x-slot name="header">
        <section class="border-b border-slate-200/60 bg-blue-50/60">
            <div class="mx-auto grid max-w-7xl items-center gap-10 px-6 py-10 lg:grid-cols-[1.1fr_.9fr] lg:px-10 lg:py-16">
                <div>
                    <h1 class="max-w-2xl text-5xl leading-[1.05] text-blue-900 sm:text-6xl">Encuentra un lugar que se sienta como tuyo.</h1>
                    <p class="mt-5 max-w-xl text-base leading-7 text-slate-600">Explora propiedades para comprar o arrendar, compara opciones y contacta directamente con su agente.</p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('public.properties.index', ['operation_type' => 'venta']) }}" class="inline-flex h-11 items-center rounded-full bg-blue-700 px-6 text-sm font-semibold text-white transition hover:bg-blue-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Explorar en venta</a>
                        <a href="{{ route('public.properties.index', ['operation_type' => 'alquiler']) }}" class="inline-flex h-11 items-center rounded-full border border-slate-200/70 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Ver alquileres</a>
                    </div>
                    <p class="mt-10 max-w-lg border-t border-blue-900/10 pt-5 text-sm text-slate-600">
                        @foreach($stats['value'] as $stat)<strong class="font-semibold text-blue-900 tabular-nums">{{ $stat['value'] }}</strong> {{ strtolower($stat['label']) }}{{ $loop->last ? '' : ' · ' }}@endforeach
                    </p>
                </div>

                <div class="rounded-3xl bg-white p-5 shadow-[0_18px_50px_-20px_rgb(16_53_46/0.35)] ring-1 ring-blue-900/5 sm:p-7">
                    <div class="mb-5"><h2 class="text-2xl text-blue-900">Encuentra tu próxima propiedad</h2></div>
                    <form action="{{ route('public.properties.index') }}" method="GET" class="grid gap-4 sm:grid-cols-2">
                        <label class="text-sm font-medium text-slate-700">Operación
                            <select name="operation_type" class="mt-1.5 h-11 w-full rounded-xl border border-slate-200/70 bg-white px-3 text-sm text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">
                                <option value="">Compra o alquiler</option><option value="venta" @selected(request('operation_type') === 'venta')>Venta</option><option value="alquiler" @selected(request('operation_type') === 'alquiler')>Alquiler</option>
                            </select>
                        </label>
                        <label class="text-sm font-medium text-slate-700">Tipo de propiedad
                            <select name="property_type_id" class="mt-1.5 h-11 w-full rounded-xl border border-slate-200/70 bg-white px-3 text-sm text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">
                                <option value="">Todos los tipos</option>@foreach($propertyTypes as $type)<option value="{{ $type->id }}" @selected(request('property_type_id') == $type->id)>{{ $type->name }}</option>@endforeach
                            </select>
                        </label>
                        <label class="text-sm font-medium text-slate-700">Precio mínimo (COP)<input type="number" min="0" name="min_price" value="{{ request('min_price') }}" placeholder="Sin mínimo" class="mt-1.5 h-11 w-full rounded-xl border border-slate-200/70 bg-white px-3 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                        <label class="text-sm font-medium text-slate-700">Precio máximo (COP)<input type="number" min="0" name="max_price" value="{{ request('max_price') }}" placeholder="Sin máximo" class="mt-1.5 h-11 w-full rounded-xl border border-slate-200/70 bg-white px-3 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                        <label class="text-sm font-medium text-slate-700 sm:col-span-2">Palabras clave o ubicación<input type="search" name="search" value="{{ request('search') }}" placeholder="Ciudad, barrio o nombre de la propiedad" class="mt-1.5 h-11 w-full rounded-xl border border-slate-200/70 bg-white px-3 text-sm text-slate-900 placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                        @if(request()->filled('page'))<input type="hidden" name="page" value="{{ request('page') }}">@endif
                        <button class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-blue-500 px-5 text-sm font-semibold text-white transition hover:bg-blue-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200 sm:col-span-2">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Buscar propiedades
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </x-slot>

    <section class="mx-auto max-w-7xl px-6 py-8 lg:px-10 lg:py-12">
        <div class="mb-7 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div><h2 class="text-3xl text-blue-900">Propiedades destacadas</h2><p class="mt-1 text-sm text-slate-500">Opciones disponibles para explorar.</p></div>
            <a href="{{ route('public.properties.index') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Ver todas las propiedades <span aria-hidden="true">→</span></a>
        </div>

        @if($featuredProperties->isEmpty())
            <div class="rounded-3xl bg-white px-6 py-16 text-center shadow-sm"><p class="text-sm font-medium text-slate-700">Todavía no hay propiedades destacadas.</p><p class="mt-1 text-sm text-slate-500">Vuelve pronto para ver nuevas opciones.</p></div>
        @else
            <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($featuredProperties as $property)
                    @php($isInterested = in_array($property->id, $interestedIds, true))
                    <article class="group min-w-0">
                        <div class="relative aspect-[4/5] overflow-hidden rounded-3xl bg-blue-100 shadow-[0_10px_30px_-12px_rgb(16_53_46/0.35)]">
                            @if($property->main_image)
                                <img src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="grid h-full w-full place-items-center bg-gradient-to-b from-blue-100 to-blue-200/70 text-blue-700/40"><svg class="h-20 w-20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round"/></svg></div>
                            @endif
                            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-slate-700 backdrop-blur">{{ $property->operation_type === 'alquiler' ? 'Alquiler' : 'Venta' }}</span>
                            @guest
                                <a href="{{ route('login') }}" aria-label="Inicia sesión para guardar esta propiedad" class="absolute right-3 top-3 grid h-10 w-10 place-items-center rounded-full bg-white/85 text-slate-700 shadow-sm backdrop-blur transition hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 10-8.8 10s-8.8-4.8-8.8-10A4.8 4.8 0 0 1 12 6a4.8 4.8 0 0 1 8.8 2.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></a>
                            @elseif(auth()->user()->isClient())
                                <form method="POST" action="{{ route($isInterested ? 'client.properties.uninterest' : 'client.properties.interest', $property) }}" class="absolute right-3 top-3">@csrf @if($isInterested)@method('DELETE')@endif<button type="submit" aria-label="{{ $isInterested ? 'Quitar de mis intereses' : 'Agregar a mis intereses' }}" class="grid h-10 w-10 place-items-center rounded-full bg-white/85 text-slate-700 shadow-sm backdrop-blur transition hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"><svg class="h-5 w-5 {{ $isInterested ? 'fill-rose-500 text-rose-500' : '' }}" viewBox="0 0 24 24" fill="{{ $isInterested ? 'currentColor' : 'none' }}" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 10-8.8 10s-8.8-4.8-8.8-10A4.8 4.8 0 0 1 12 6a4.8 4.8 0 0 1 8.8 2.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></button></form>
                            @endauth
                        </div>
                        <div class="pt-4">
                            <div class="flex items-start justify-between gap-3"><h3 class="truncate text-base font-semibold tracking-tight text-slate-900">{{ $property->title }}</h3><span class="shrink-0 text-xs text-slate-400">{{ $property->propertyType->name }}</span></div>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $property->city }} · {{ $property->neighborhood }}</p>
                            <p class="mt-2 text-xs text-slate-400">{{ $property->bedrooms }} hab. · {{ $property->bathrooms }} baños · {{ number_format($property->area, 0, ',', '.') }} m²</p>
                            <p class="mt-2 text-sm text-slate-500">Desde <x-price class="!text-clay-600" :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" /></p>
                            <a href="{{ route('public.properties.show', $property) }}" class="mt-3 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Ver propiedad <span class="ml-1" aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="border-y border-slate-200/60 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-6 py-8 sm:flex-row sm:items-center sm:justify-between lg:px-10">
            <div><h2 class="text-xl font-semibold tracking-tight text-slate-900">¿Buscas algo específico?</h2><p class="mt-1 text-sm text-slate-500">Explora por operación y encuentra más opciones.</p></div>
            <div class="flex flex-wrap gap-2">@foreach(['venta' => 'Propiedades en venta', 'alquiler' => 'Propiedades en alquiler'] as $type => $label)<a href="{{ route('public.properties.index', ['operation_type' => $type]) }}" class="rounded-full bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-blue-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">{{ $label }}</a>@endforeach</div>
            @guest
                @if(Route::has('register'))<a href="{{ route('register') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Crear cuenta</a>@endif
            @else
                @if(auth()->user()->isAgent() || auth()->user()->isAdmin())<a href="{{ route('dashboard.properties.create') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Publicar propiedad</a>@endif
            @endguest
        </div>
    </section>
</x-public-layout>
