<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertyTypeRequest;
use App\Models\PropertyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyTypeController extends Controller
{
    public function index(): View
    {
        $propertyTypes = PropertyType::latest()->get();

        return view('dashboard.property-types.index', compact('propertyTypes'));
    }

    public function create(): View
    {
        $this->authorize('create', PropertyType::class);

        return view('dashboard.property-types.create');
    }

    public function store(PropertyTypeRequest $request): RedirectResponse
    {
        $this->authorize('create', PropertyType::class);

        PropertyType::create($request->validated());

        return redirect()->route('property-types.index')
            ->with('success', 'Tipo de propiedad creado exitosamente.');
    }

    public function show(PropertyType $propertyType): View
    {
        $this->authorize('view', $propertyType);
        $propertyType->loadCount('properties');

        return view('dashboard.property-types.show', compact('propertyType'));
    }

    public function edit(PropertyType $propertyType): View
    {
        $this->authorize('update', $propertyType);

        return view('dashboard.property-types.edit', compact('propertyType'));
    }

    public function update(PropertyTypeRequest $request, PropertyType $propertyType): RedirectResponse
    {
        $this->authorize('update', $propertyType);

        $propertyType->update($request->validated());

        return redirect()->route('property-types.index')
            ->with('success', 'Tipo de propiedad actualizado exitosamente.');
    }

    public function destroy(PropertyType $propertyType): RedirectResponse
    {
        $this->authorize('delete', $propertyType);

        if ($propertyType->properties()->exists()) {
            return redirect()->route('property-types.index')
                ->with('error', 'No se puede eliminar el tipo de propiedad porque tiene propiedades asociadas.');
        }

        $propertyType->delete();

        return redirect()->route('property-types.index')
            ->with('success', 'Tipo de propiedad eliminado exitosamente.');
    }
}
