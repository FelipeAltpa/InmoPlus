<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyVisitController extends Controller
{
    public function clientIndex(Request $request): View
    {
        $visits = PropertyVisit::with('property')->where('client_user_id', $request->user()->id)->latest()->paginate(15);

        return view('client.visits.index', compact('visits'));
    }

    public function store(Request $request, Property $property): RedirectResponse
    {
        abort_unless($property->status === 'disponible', 404);
        $data = $request->validate(['requested_at' => ['required', 'date', 'after:now'], 'client_message' => ['nullable', 'string', 'max:1500']]);
        PropertyVisit::create($data + ['property_id' => $property->id, 'client_user_id' => $request->user()->id, 'agent_user_id' => $property->user_id, 'status' => 'pending']);

        return back()->with('success', 'Solicitud de visita enviada al agente.');
    }

    public function agenda(Request $request): View
    {
        $query = PropertyVisit::with(['property', 'client', 'agent'])->latest('requested_at');
        if (! $request->user()->isAdmin()) {
            $query->where('agent_user_id', $request->user()->id);
        }
        $visits = $query->paginate(20);

        return view('dashboard.visits.index', compact('visits'));
    }

    public function update(Request $request, PropertyVisit $visit): RedirectResponse
    {
        abort_unless($request->user()->isAdmin() || $visit->agent_user_id === $request->user()->id, 403);
        $data = $request->validate([
            'action' => ['required', 'in:confirm_requested,propose_time,reject,cancel'],
            'scheduled_at' => ['nullable', 'required_if:action,propose_time', 'date', 'after:now'],
            'agent_note' => ['nullable', 'string', 'max:1500'],
        ]);

        if ($data['action'] === 'confirm_requested') {
            abort_unless($visit->status === 'pending', 422);
            $visit->update([
                'status' => 'confirmed',
                'scheduled_at' => $visit->requested_at,
                'agent_note' => $data['agent_note'] ?? null,
            ]);

            return back()->with('success', 'La hora solicitada por el cliente quedó confirmada.');
        }

        if ($data['action'] === 'propose_time') {
            abort_unless($visit->status === 'pending', 422);
            $visit->update([
                'status' => 'proposed',
                'scheduled_at' => $data['scheduled_at'],
                'agent_note' => $data['agent_note'] ?? null,
            ]);

            return back()->with('success', 'La nueva hora quedó pendiente de respuesta del cliente.');
        }

        if ($data['action'] === 'reject') {
            abort_unless($visit->status === 'pending', 422);
            $visit->update(['status' => 'declined', 'agent_note' => $data['agent_note'] ?? null]);

            return back()->with('success', 'La solicitud fue rechazada.');
        }

        abort_unless(in_array($visit->status, ['confirmed', 'proposed'], true), 422);
        $visit->update(['status' => 'cancelled', 'agent_note' => $data['agent_note'] ?? null]);

        return back()->with('success', 'El compromiso de visita fue cancelado.');
    }

    public function acceptProposal(Request $request, PropertyVisit $visit): RedirectResponse
    {
        abort_unless($visit->client_user_id === $request->user()->id, 403);
        abort_unless($visit->status === 'proposed' && $visit->scheduled_at?->isFuture(), 422);
        $visit->update(['status' => 'confirmed']);

        return back()->with('success', 'Aceptaste la hora propuesta. La visita quedó confirmada.');
    }

    public function declineProposal(Request $request, PropertyVisit $visit): RedirectResponse
    {
        abort_unless($visit->client_user_id === $request->user()->id, 403);
        abort_unless($visit->status === 'proposed', 422);
        $visit->update(['status' => 'pending', 'scheduled_at' => null, 'agent_note' => null]);

        return back()->with('success', 'No aceptaste la propuesta. La solicitud volvió a estar pendiente para coordinar otra hora.');
    }

    public function cancel(Request $request, PropertyVisit $visit): RedirectResponse
    {
        abort_unless($visit->client_user_id === $request->user()->id, 403);
        abort_unless(in_array($visit->status, ['pending', 'proposed', 'confirmed'], true), 422);
        $visit->update(['status' => 'cancelled']);

        return back()->with('success', 'Solicitud cancelada.');
    }
}
