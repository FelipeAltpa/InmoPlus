<x-public-layout :title="$property->title">
    <x-slot name="header">
        <section class="mx-auto max-w-7xl px-6 py-8 lg:px-10">
            <a href="{{ route('public.properties.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">← Volver a propiedades disponibles</a>
            <div class="mt-4 flex flex-wrap items-end justify-between gap-5">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $property->title }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $property->city }} · {{ $property->neighborhood }} · {{ $property->propertyType->name }}</p>
                    <div class="mt-3 flex flex-wrap gap-2"><span class="rounded-full bg-blue-200/70 px-3 py-1 text-xs font-medium text-slate-900">{{ ucfirst($property->operation_type) }}</span><span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-700">{{ ucfirst($property->status) }}</span></div>
                </div>
                <div><p class="text-xs text-slate-500">{{ number_format($property->area, 0, ',', '.') }} m²</p><x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" class="text-2xl sm:text-3xl" /></div>
            </div>
        </section>
    </x-slot>

    <section class="mx-auto max-w-7xl px-6 pb-12 lg:px-10">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <main class="space-y-8 lg:col-span-2">
                <div class="relative">
                    <x-property-gallery :property="$property" />
                    @guest<a href="{{ route('login') }}" aria-label="Inicia sesión para guardar esta propiedad" class="absolute right-4 top-4 grid h-11 w-11 place-items-center rounded-full bg-white/85 text-slate-700 shadow-sm backdrop-blur hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 10-8.8 10s-8.8-4.8-8.8-10A4.8 4.8 0 0 1 12 6a4.8 4.8 0 0 1 8.8 2.8Z" stroke="currentColor" stroke-width="1.7"/></svg></a>@endguest
                </div>

                @if($property->operation_type === 'venta')<x-loan-simulator :property="$property" />@endif

                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Descripción</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $property->description }}</p>
                </section>

                @if($property->features && count($property->features) > 0)
                    <section class="rounded-3xl bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold tracking-tight text-slate-900">Características</h2>
                        <ul class="mt-4 grid gap-3 sm:grid-cols-2">@foreach($property->features as $feature)<li class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600">{{ $feature }}</li>@endforeach</ul>
                    </section>
                @endif

                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Detalles</h2>
                    <dl class="mt-5 grid grid-cols-2 gap-5 sm:grid-cols-4">
                        <div><dt class="text-xs text-slate-500">Habitaciones</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $property->bedrooms }}</dd></div>
                        <div><dt class="text-xs text-slate-500">Baños</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $property->bathrooms }}</dd></div>
                        <div><dt class="text-xs text-slate-500">Área</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($property->area, 0, ',', '.') }} m²</dd></div>
                        <div><dt class="text-xs text-slate-500">Precio / m²</dt><dd class="mt-1 text-lg font-semibold text-slate-900"><x-price :amount="$property->area ? $property->price / $property->area : 0" :unit="$property->operation_type === 'alquiler' ? ' /m²/mes' : ' /m²'" /></dd></div>
                    </dl>
                    <div class="mt-5 border-t border-slate-200/60 pt-5"><p class="text-xs text-slate-500">Dirección</p><p class="mt-1 text-sm text-slate-700">{{ $property->address }}</p></div>
                </section>

                @if($property->latitude && $property->longitude)
                    <section class="rounded-3xl bg-white p-6 shadow-sm"><h2 class="text-lg font-semibold text-slate-900">Ubicación</h2><div class="mt-4 rounded-2xl bg-slate-50 p-5 text-sm text-slate-600">{{ $property->city }} · {{ $property->neighborhood }}<span class="mt-1 block text-xs text-slate-400">Lat: {{ $property->latitude }}, Lng: {{ $property->longitude }}</span></div></section>
                @endif
            </main>

            <aside class="h-fit space-y-5 lg:sticky lg:top-24">
                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Precio</p>
                    <x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" class="mt-1 text-2xl" />
                    <p class="mt-1 text-xs text-slate-500"><x-price :amount="$property->area ? $property->price / $property->area : 0" :unit="$property->operation_type === 'alquiler' ? ' /m²/mes' : ' /m²'" /></p>
                    <div class="mt-5 border-t border-slate-200/60 pt-5">
                        <h2 class="font-semibold text-slate-900">Agente inmobiliario</h2>
                        <div class="mt-3 flex items-center gap-3">
                            @if($property->user->profile_photo)<img src="{{ asset('storage/' . $property->user->profile_photo) }}" alt="{{ $property->user->name }}" class="h-11 w-11 rounded-full object-cover">@else<div class="grid h-11 w-11 place-items-center rounded-full bg-blue-100 font-semibold text-blue-700">{{ strtoupper(substr($property->user->name, 0, 1)) }}</div>@endif
                            <div><p class="text-sm font-medium text-slate-900">{{ $property->user->name }}</p><p class="text-xs text-slate-500">{{ $property->user->isAdmin() ? 'Administrador' : 'Agente inmobiliario' }}</p></div>
                        </div>
                        @if($property->user->phone)<p class="mt-4 text-sm text-slate-600">{{ $property->user->phone }}</p>@endif
                        <p class="mt-1 break-all text-sm text-slate-500">{{ $property->user->email }}</p>
                        <a href="mailto:{{ $property->user->email }}?subject=Consulta sobre {{ $property->title }}" class="mt-4 inline-flex h-10 w-full items-center justify-center rounded-full bg-blue-500 px-4 text-sm font-semibold text-white hover:bg-blue-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Contactar agente</a>
                    </div>
                </section>

                @auth
                    @if(auth()->user()?->role === 'client')
                        <section class="rounded-3xl bg-white p-6 shadow-sm"><h2 class="font-semibold text-slate-900">Tus intereses</h2><p class="mt-2 text-sm text-slate-500">Guarda esta propiedad para revisarla después.</p>
                            @if($isInterested)<form method="POST" action="{{ route('client.properties.uninterest', $property) }}" class="mt-4">@csrf @method('DELETE')<button class="h-10 w-full rounded-full border border-slate-200/70 text-sm font-medium text-slate-700 hover:bg-slate-50">Quitar de mis intereses</button></form>
                            @else<form method="POST" action="{{ route('client.properties.interest', $property) }}" class="mt-4">@csrf<button class="h-10 w-full rounded-full bg-blue-200/70 text-sm font-medium text-slate-900 hover:bg-blue-200">Me interesa</button></form>@endif
                            <a href="{{ route('client.interests') }}" class="mt-3 block text-center text-sm text-slate-500 hover:text-slate-900">Ver mis intereses</a>
                        </section>
                    @endif
                @endauth

                <section class="rounded-3xl bg-white p-6 shadow-sm"><h2 class="font-semibold text-slate-900">Información</h2><dl class="mt-4 space-y-3"><div class="flex justify-between gap-4"><dt class="text-sm text-slate-500">Referencia</dt><dd class="text-sm text-slate-700">#{{ $property->id }}</dd></div><div class="flex justify-between gap-4"><dt class="text-sm text-slate-500">Publicado</dt><dd class="text-sm text-slate-700">{{ $property->created_at->format('d/m/Y') }}</dd></div><div class="flex justify-between gap-4"><dt class="text-sm text-slate-500">Actualizado</dt><dd class="text-sm text-slate-700">{{ $property->updated_at->format('d/m/Y') }}</dd></div></dl><button type="button" onclick="shareProperty()" class="mt-5 h-10 w-full rounded-full border border-slate-200/70 text-sm font-medium text-slate-700 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Compartir propiedad</button></section>
            </aside>
        </div>

        @if($relatedProperties && $relatedProperties->count() > 0)
            <section class="mt-14"><h2 class="text-xl font-semibold tracking-tight text-slate-900">Propiedades similares</h2><div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($relatedProperties as $related)
                    <article class="group min-w-0"><a href="{{ route('public.properties.show', $related) }}" class="block"><div class="aspect-[4/5] overflow-hidden rounded-3xl bg-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.08)]">@if($related->main_image)<img src="{{ asset('storage/' . $related->main_image) }}" alt="{{ $related->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">@else<div class="grid h-full place-items-center text-slate-300"><svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg></div>@endif</div><h3 class="mt-4 truncate text-base font-semibold text-slate-900">{{ $related->title }}</h3><p class="mt-1 truncate text-sm text-slate-500">{{ $related->city }} · {{ $related->neighborhood }}</p><p class="mt-2 text-sm text-slate-500">Desde <x-price :amount="$related->price" :unit="$related->operation_type === 'alquiler' ? ' /mes' : ''" /></p></a></article>
                @endforeach
            </div></section>
        @endif
    </section>
</x-public-layout>

@php($shareText = $property->title . ' - ' . number_format($property->price, 0, ',', '.'))
<script>
    function shareProperty() {
        if (navigator.share) {
            navigator.share({ title: @json($property->title), text: @json($shareText), url: window.location.href }).catch(() => {});
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(window.location.href).then(() => alert('Enlace copiado al portapapeles'));
        }
    }
</script>
