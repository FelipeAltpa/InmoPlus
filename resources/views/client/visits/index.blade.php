<x-public-layout :title="'Mis visitas'">
    <x-slot name="header"><div class="bg-gradient-to-br from-indigo-600 to-purple-700 py-10 text-white"><div class="mx-auto max-w-7xl px-4"><h1 class="text-3xl font-bold">Mis solicitudes de visita</h1></div></div></x-slot>
    <div class="mx-auto max-w-5xl space-y-4 px-4 py-10">
        @forelse($visits as $visit)
            <article class="flex flex-col gap-4 rounded-lg bg-white p-5 shadow sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="font-semibold">{{ $visit->property->title }}</h2>
                    <p class="text-sm text-gray-600">Hora que solicitaste: {{ $visit->requested_at->format('d/m/Y H:i') }}</p>
                    <p class="text-sm text-gray-600">Estado: {{ ['pending' => 'Pendiente', 'proposed' => 'El agente propone otra hora', 'confirmed' => 'Confirmada', 'declined' => 'Rechazada', 'cancelled' => 'Cancelada'][$visit->status] ?? $visit->status }}</p>
                    @if($visit->scheduled_at)<p class="mt-1 text-sm font-medium text-indigo-700">Hora propuesta/confirmada: {{ $visit->scheduled_at->format('d/m/Y H:i') }}</p>@endif
                    @if($visit->agent_note)<p class="mt-1 text-sm text-gray-600">Mensaje del agente: {{ $visit->agent_note }}</p>@endif
                </div>

                @if($visit->status === 'proposed')
                    <div class="flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('client.visits.proposal.accept', $visit) }}">@csrf @method('PATCH')<button class="rounded-md bg-green-600 px-3 py-2 text-sm text-white">Aceptar hora propuesta</button></form>
                        <form method="POST" action="{{ route('client.visits.proposal.decline', $visit) }}">@csrf @method('PATCH')<button class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700">No aceptar</button></form>
                    </div>
                @endif

                @if(in_array($visit->status, ['pending', 'proposed', 'confirmed'], true))
                    <form method="POST" action="{{ route('client.visits.cancel', $visit) }}">@csrf @method('PATCH')<button class="text-sm text-red-700">{{ $visit->status === 'confirmed' ? 'Cancelar visita confirmada' : 'Cancelar solicitud' }}</button></form>
                @endif
            </article>
        @empty
            <div class="rounded-lg bg-white p-8 text-center text-gray-600">Aún no tienes visitas solicitadas.</div>
        @endforelse
        {{ $visits->links() }}
    </div>
</x-public-layout>
