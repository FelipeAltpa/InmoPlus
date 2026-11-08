<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Property;
use App\Models\PropertyType;
use App\Notifications\PropertyInterestNotification;
use App\Services\PropertyFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientPropertyController extends Controller
{
    public function index(Request $request): View
    {
        $query = app(PropertyFilter::class)->apply(
            Property::with(['user', 'propertyType'])->where('status', 'disponible'),
            $request
        );

        $properties = $query->paginate(12)->withQueryString();

        $propertyTypes = PropertyType::active()->get();
        $interestedIds = $this->currentClient()?->properties()->pluck('properties.id')->toArray() ?? [];

        return view('client.properties.index', compact('properties', 'interestedIds', 'propertyTypes'));
    }

    public function show(Property $property): View
    {
        if ($property->status !== 'disponible') {
            abort(404);
        }

        $property->load(['user', 'propertyType', 'galleryImages']);

        $isInterested = $this->currentClient()?->properties()->whereKey($property->id)->exists() ?? false;

        return view('client.properties.show', compact('property', 'isInterested'));
    }

    public function interests(): View
    {
        $client = $this->currentClient();

        // Historical associations are kept even if a property is no longer
        // available; only new associations are restricted to available ones.
        $properties = $client?->properties()->with(['user', 'propertyType'])
            ->orderByPivot('created_at', 'desc')
            ->get() ?? collect();

        return view('client.interests', compact('properties'));
    }

    public function interest(Request $request, Property $property): RedirectResponse
    {
        if ($property->status !== 'disponible') {
            return back()->with('error', 'Solo puedes marcar como interés propiedades disponibles.');
        }

        $client = $this->currentClient();

        if ($client === null) {
            abort(403, 'Tu cuenta de cliente no está configurada.');
        }

        $alreadyInterested = $client->properties()->whereKey($property->getKey())->exists();

        $client->properties()->syncWithoutDetaching([$property->id]);

        if (! $alreadyInterested && in_array($property->user?->role, ['admin', 'agent'], true)) {
            $property->user->notify(new PropertyInterestNotification(
                propertyId: $property->id,
                propertyTitle: $property->title,
                clientName: $client->name,
            ));
        }

        return back()->with('success', 'Propiedad agregada a tus intereses.');
    }

    public function uninterest(Request $request, Property $property): RedirectResponse
    {
        $client = $this->currentClient();

        if ($client === null) {
            abort(403, 'Tu cuenta de cliente no está configurada.');
        }

        $client->properties()->detach($property->id);

        return back()->with('success', 'Propiedad quitada de tus intereses.');
    }

    // The CRM profile always comes from the authenticated account.
    private function currentClient(): ?Client
    {
        return auth()->user()?->client;
    }
}
