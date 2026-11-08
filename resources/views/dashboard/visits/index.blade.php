<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Agenda de visitas</h2></x-slot>
    <div class="py-10"><div class="max-w-7xl mx-auto px-4 space-y-4">
        @forelse($visits as $visit)
            <article class="bg-white shadow rounded-lg p-5 space-y-4">
                <div>
                    <h3 class="font-semibold">{{ $visit->property->title }} · {{ $visit->property->city }}</h3>
                    <p class="text-sm text-gray-600">Cliente: {{ $visit->client->name }} ({{ $visit->client->email }})</p>
                    <p class="text-sm text-gray-600">Solicitó: {{ $visit->requested_at->format('d/m/Y H:i') }} · Estado: {{ ['pending' => 'Pendiente', 'proposed' => 'Hora propuesta', 'confirmed' => 'Confirmada', 'declined' => 'Rechazada', 'cancelled' => 'Cancelada'][$visit->status] ?? $visit->status }}</p>
                    @if($visit->scheduled_at)<p class="text-sm font-medium text-indigo-700">Hora agendada/propuesta: {{ $visit->scheduled_at->format('d/m/Y H:i') }}</p>@endif
                    @if($visit->client_message)<p class="mt-2 text-sm">Mensaje del cliente: {{ $visit->client_message }}</p>@endif
                    @if($visit->agent_note)<p class="mt-1 text-sm text-gray-600">Nota al cliente: {{ $visit->agent_note }}</p>@endif
                </div>

                @if($visit->status === 'pending')
                    <div class="flex flex-col gap-3 border-t pt-4 sm:flex-row sm:items-end">
                        <form method="POST" action="{{ route('dashboard.visits.update', $visit) }}" class="flex flex-wrap items-end gap-2">
                            @csrf @method('PATCH')<input type="hidden" name="action" value="confirm_requested">
                            <input name="agent_note" placeholder="Nota opcional" class="rounded-md border-gray-300 text-sm">
                            <button class="rounded-md bg-green-600 px-3 py-2 text-sm text-white">Confirmar hora solicitada</button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.visits.update', $visit) }}" class="flex flex-wrap items-end gap-2">
                            @csrf @method('PATCH')<input type="hidden" name="action" value="propose_time">
                            <label class="text-xs text-gray-600">Proponer otra hora<input type="datetime-local" name="scheduled_at" min="{{ now()->format('Y-m-d\TH:i') }}" required class="mt-1 block rounded-md border-gray-300 text-sm"></label>
                            <input name="agent_note" placeholder="Mensaje para el cliente" class="rounded-md border-gray-300 text-sm">
                            <button class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white">Enviar propuesta</button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.visits.update', $visit) }}">
                            @csrf @method('PATCH')<input type="hidden" name="action" value="reject">
                            <button class="rounded-md border border-red-300 px-3 py-2 text-sm text-red-700">Rechazar solicitud</button>
                        </form>
                    </div>
                @elseif(in_array($visit->status, ['confirmed', 'proposed'], true))
                    <form method="POST" action="{{ route('dashboard.visits.update', $visit) }}" class="border-t pt-4">
                        @csrf @method('PATCH')<input type="hidden" name="action" value="cancel">
                        <button class="rounded-md border border-red-300 px-3 py-2 text-sm text-red-700">{{ $visit->status === 'confirmed' ? 'Cancelar compromiso' : 'Retirar propuesta' }}</button>
                    </form>
                @endif
            </article>
        @empty
            <div class="bg-white rounded-lg p-8 text-center text-gray-600">No hay solicitudes de visita.</div>
        @endforelse
        {{ $visits->links() }}
    </div></div>
</x-app-layout>
