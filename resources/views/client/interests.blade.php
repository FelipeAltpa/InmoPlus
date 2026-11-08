<x-public-layout :title="'Mis propiedades de interés'">
    <x-slot name="header">
        <section class="mx-auto max-w-7xl px-6 py-8 lg:px-10 lg:py-12">
            <a href="{{ route('client.properties.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">← Explorar propiedades</a>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Mis propiedades de interés</h1>
            <p class="mt-2 text-slate-500">Las propiedades que guardaste para revisar después.</p>
        </section>
    </x-slot>

    <section class="mx-auto max-w-7xl px-6 pb-12 lg:px-10">
        @if($properties->isEmpty())
            <div class="rounded-3xl bg-white px-6 py-16 text-center shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Aún no tienes propiedades guardadas</h2>
                <p class="mt-2 text-sm text-slate-500">Explora las opciones disponibles y guarda las que te interesen.</p>
                <a href="{{ route('client.properties.index') }}" class="mt-5 inline-flex h-10 items-center rounded-full bg-blue-500 px-4 text-sm font-semibold text-white hover:bg-blue-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Ver propiedades</a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($properties as $property)
                    <article class="group min-w-0">
                        <a href="{{ route('client.properties.show', $property) }}" class="block">
                            <div class="relative aspect-[4/5] overflow-hidden rounded-3xl bg-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                                @if($property->main_image)
                                    <img src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="grid h-full place-items-center bg-slate-100 text-slate-300"><svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg></div>
                                @endif
                                <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-medium text-slate-700 backdrop-blur">{{ ucfirst($property->operation_type) }}</span>
                            </div>
                            <div class="pt-4">
                                <h2 class="truncate text-base font-semibold tracking-tight text-slate-900">{{ $property->title }}</h2>
                                <p class="mt-1 truncate text-sm text-slate-500">{{ $property->city }} · {{ $property->neighborhood }}</p>
                                <p class="mt-2 text-sm text-slate-500">Desde <x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" /></p>
                                <p class="mt-2 text-xs text-slate-400">{{ $property->bedrooms }} hab. · {{ $property->bathrooms }} baños · {{ number_format($property->area, 0, ',', '.') }} m²</p>
                            </div>
                        </a>
                        <form method="POST" action="{{ route('client.properties.uninterest', $property) }}" onsubmit="return confirm('¿Quitar esta propiedad de tus intereses?')" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-slate-500 hover:text-rose-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Quitar de mis intereses</button>
                        </form>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</x-public-layout>
