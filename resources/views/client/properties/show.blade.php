<x-public-layout :title="$property->title">
    <x-slot name="header">
        <section class="mx-auto max-w-7xl px-6 py-8 lg:px-10">
            <a href="{{ route('client.properties.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">← Volver a propiedades</a>
            <div class="mt-4 flex flex-wrap items-end justify-between gap-5">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $property->title }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $property->city }} · {{ $property->neighborhood }} · {{ $property->propertyType->name }}</p>
                </div>
                <div><p class="text-xs text-slate-500">{{ ucfirst($property->operation_type) }}</p><x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" class="text-2xl" /></div>
            </div>
        </section>
    </x-slot>

    <section class="mx-auto max-w-7xl px-6 pb-12 lg:px-10">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <main class="space-y-8 lg:col-span-2">
                <x-property-gallery :property="$property" />

                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Descripción</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $property->description }}</p>
                </section>

                @if($property->operation_type === 'venta')<x-loan-simulator :property="$property" />@endif

                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Detalles de la propiedad</h2>
                    <dl class="mt-5 grid grid-cols-2 gap-5 sm:grid-cols-4">
                        <div><dt class="text-xs text-slate-500">Habitaciones</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $property->bedrooms }}</dd></div>
                        <div><dt class="text-xs text-slate-500">Baños</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $property->bathrooms }}</dd></div>
                        <div><dt class="text-xs text-slate-500">Área</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ number_format($property->area, 0, ',', '.') }} m²</dd></div>
                        <div><dt class="text-xs text-slate-500">Ciudad</dt><dd class="mt-1 text-lg font-semibold text-slate-900">{{ $property->city }}</dd></div>
                    </dl>
                    <p class="mt-5 border-t border-slate-200/60 pt-5 text-sm text-slate-600">{{ $property->address }}</p>
                </section>
            </main>

            <aside class="h-fit space-y-5 lg:sticky lg:top-24">
                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Precio</p>
                    <x-price :amount="$property->price" :unit="$property->operation_type === 'alquiler' ? ' /mes' : ''" class="mt-1 text-2xl" />
                    <div class="mt-5 border-t border-slate-200/60 pt-5">
                        <h2 class="font-semibold text-slate-900">Solicitar visita</h2>
                        <form method="POST" action="{{ route('client.visits.store', $property) }}" class="mt-4 space-y-3">
                            @csrf
                            <label class="block text-sm text-slate-600">Fecha y hora preferida<input type="datetime-local" name="requested_at" min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" required class="mt-1 h-11 w-full rounded-xl border border-slate-200/70 px-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></label>
                            <textarea name="client_message" rows="3" maxlength="1500" placeholder="Mensaje opcional" class="w-full rounded-xl border border-slate-200/70 p-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"></textarea>
                            <button class="h-11 w-full rounded-full bg-blue-500 px-4 text-sm font-semibold text-white hover:bg-blue-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Enviar solicitud</button>
                        </form>
                        <a href="{{ route('client.visits.index') }}" class="mt-3 block text-center text-sm font-medium text-slate-600 hover:text-slate-900">Ver mis visitas</a>
                    </div>
                </section>

                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-slate-900">Agente inmobiliario</h2>
                    <p class="mt-2 text-sm text-slate-700">{{ $property->user->name }}</p>
                    <p class="text-sm text-slate-500">{{ $property->user->email }}</p>
                    @if($property->user->phone)<p class="mt-1 text-sm text-slate-500">{{ $property->user->phone }}</p>@endif
                </section>

                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-slate-900">Tus intereses</h2>
                    @if($isInterested)
                        <form method="POST" action="{{ route('client.properties.uninterest', $property) }}" class="mt-4">@csrf @method('DELETE')<button class="h-10 w-full rounded-full border border-slate-200/70 text-sm font-medium text-slate-700 hover:bg-slate-50">Quitar de mis intereses</button></form>
                    @else
                        <form method="POST" action="{{ route('client.properties.interest', $property) }}" class="mt-4">@csrf<button class="h-10 w-full rounded-full bg-blue-200/70 text-sm font-medium text-slate-900 hover:bg-blue-200">Guardar propiedad</button></form>
                    @endif
                    <a href="{{ route('client.interests') }}" class="mt-3 block text-center text-sm text-slate-500 hover:text-slate-900">Ver mis intereses</a>
                </section>
            </aside>
        </div>
    </section>
</x-public-layout>
