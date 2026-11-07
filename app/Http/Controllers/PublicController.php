<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use App\Services\PropertyFilter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function index(): View
    {
        $agentCount = User::where('role', 'agent')->count();
        $availablePropertyCount = Property::where('status', 'disponible')->count();
        $cityCount = Property::where('status', 'disponible')->distinct('city')->count('city');

        $stats = [
            'agents' => $agentCount,
            'properties' => $availablePropertyCount,
            'cities' => $cityCount,
            'satisfaction' => '98%',
            'value' => [
                ['value' => $availablePropertyCount, 'label' => 'Propiedades'],
                ['value' => $agentCount, 'label' => 'Agentes'],
                ['value' => $cityCount, 'label' => 'Ciudades'],
            ],
        ];

        $propertyTypes = PropertyType::active()->get();

        $featuredProperties = Property::with(['propertyType', 'user'])
            ->where('status', 'disponible')
            ->latest()
            ->limit(8)
            ->get();

        $interestedIds = [];
        if (auth()->user()?->isClient()) {
            $interestedIds = auth()->user()->client?->properties()
                ->pluck('properties.id')
                ->all() ?? [];
        }

        $features = [
            [
                'title' => 'Búsqueda Avanzada',
                'description' => 'Filtra por tipo, precio, ubicación, operación y más características.',
                'color' => 'bg-blue-100',
                'icon' => 'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z',
            ],
            [
                'title' => 'Agentes Verificados',
                'description' => 'Todos nuestros agentes son profesionales certificados y verificados.',
                'color' => 'bg-green-100',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'title' => 'Fotos Profesionales',
                'description' => 'Galerías de imágenes de alta calidad para cada propiedad.',
                'color' => 'bg-purple-100',
                'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            ],
            [
                'title' => 'Alertas Personalizadas',
                'description' => 'Recibe notificaciones cuando nuevas propiedades coincidan con tu búsqueda.',
                'color' => 'bg-amber-100',
                'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            ],
        ];

        return view('public.index', compact('stats', 'propertyTypes', 'featuredProperties', 'features', 'interestedIds'));
    }

    public function properties(Request $request): View
    {
        $query = app(PropertyFilter::class)->apply(
            Property::with(['user', 'propertyType'])->where('status', 'disponible'),
            $request
        );

        $properties = $query->paginate(12)->withQueryString();
        $propertyTypes = PropertyType::active()->get();
        $operationTypes = ['venta' => 'Venta', 'alquiler' => 'Alquiler'];

        $interestedIds = [];
        if (auth()->user()?->isClient()) {
            $interestedIds = auth()->user()->client?->properties()
                ->pluck('properties.id')
                ->all() ?? [];
        }

        return view('public.properties.index', compact('properties', 'propertyTypes', 'operationTypes', 'interestedIds'));
    }

    public function show(Property $property): View
    {
        if ($property->status !== 'disponible') {
            abort(404);
        }

        $property->load(['user', 'propertyType', 'galleryImages']);

        $relatedProperties = Property::with(['propertyType'])
            ->where('status', 'disponible')
            ->where('property_type_id', $property->property_type_id)
            ->where('id', '!=', $property->id)
            ->latest()
            ->limit(4)
            ->get();

        $isInterested = auth()->user()?->client?->properties()
            ->whereKey($property->id)
            ->exists() ?? false;

        return view('public.properties.show', compact('property', 'relatedProperties', 'isInterested'));
    }
}
