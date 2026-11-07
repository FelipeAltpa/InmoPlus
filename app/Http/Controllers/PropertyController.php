<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Models\PropertyType;
use App\Services\PropertyFilter;
use App\Services\PropertyImageManager;
use App\Services\PropertyStatusNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PropertyController extends Controller
{
    public function index(Request $request, PropertyFilter $filters): View
    {
        // Los filtros comunes (tipo, operación, búsqueda) viven en PropertyFilter, igual que en la API.
        $query = $filters->apply(Property::with(['user', 'propertyType']), $request);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if (! auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $properties = $query->get();
        $propertyTypes = PropertyType::active()->get();
        $operationTypes = ['venta' => 'Venta', 'alquiler' => 'Alquiler'];
        $statuses = ['disponible' => 'Disponible', 'reservado' => 'Reservado', 'vendido' => 'Vendido', 'alquilado' => 'Alquilado'];

        return view('dashboard.properties.index', compact('properties', 'propertyTypes', 'operationTypes', 'statuses'));
    }

    public function create(): View
    {
        $propertyTypes = PropertyType::active()->get();
        $operationTypes = ['venta' => 'Venta', 'alquiler' => 'Alquiler'];
        $statuses = ['disponible' => 'Disponible', 'reservado' => 'Reservado', 'vendido' => 'Vendido', 'alquilado' => 'Alquilado'];

        return view('dashboard.properties.create', compact('propertyTypes', 'operationTypes', 'statuses'));
    }

    public function store(StorePropertyRequest $request, PropertyImageManager $images): RedirectResponse
    {
        $this->authorize('create', Property::class);

        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $galleryUploads = $validated['gallery_images'] ?? [];
        unset($validated['gallery_images']);

        $storedPaths = [];
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('properties', 'public');
            if (! $path) {
                throw new RuntimeException('No se pudo guardar la imagen principal.');
            }

            $validated['main_image'] = $path;
            $storedPaths[] = $path;
        }

        try {
            DB::transaction(function () use ($validated, $galleryUploads, $images, &$storedPaths): void {
                $property = Property::create($validated);
                $storedPaths = [...$storedPaths, ...$images->add($property, $galleryUploads)];
            });
        } catch (Throwable $exception) {
            $images->deleteFiles($storedPaths);
            throw $exception;
        }

        return redirect()->route('dashboard.properties.index')
            ->with('success', 'Propiedad creada exitosamente.');
    }

    public function show(Property $property): View
    {
        $this->authorize('view', $property);
        $property->load(['user', 'propertyType', 'galleryImages']);

        return view('dashboard.properties.show', compact('property'));
    }

    public function edit(Property $property): View
    {
        $this->authorize('update', $property);
        $property->load('galleryImages');

        $propertyTypes = PropertyType::active()->get();
        $operationTypes = ['venta' => 'Venta', 'alquiler' => 'Alquiler'];
        $statuses = ['disponible' => 'Disponible', 'reservado' => 'Reservado', 'vendido' => 'Vendido', 'alquilado' => 'Alquilado'];

        return view('dashboard.properties.edit', compact('property', 'propertyTypes', 'operationTypes', 'statuses'));
    }

    public function update(UpdatePropertyRequest $request, Property $property, PropertyStatusNotifier $notifier, PropertyImageManager $images): RedirectResponse
    {
        $this->authorize('update', $property);

        $previousStatus = $property->status;
        $validated = $request->validated();
        $galleryUploads = $validated['gallery_images'] ?? [];
        $removeIds = $validated['remove_gallery_images'] ?? [];
        unset($validated['gallery_images'], $validated['remove_gallery_images']);

        $newMainPath = null;
        if ($request->hasFile('main_image')) {
            $newImagePath = $request->file('main_image')->store('properties', 'public');

            if (! $newImagePath || ! Storage::disk('public')->exists($newImagePath)) {
                return Redirect::route('dashboard.properties.edit', $property)
                    ->withErrors(['main_image' => 'Error al guardar la nueva imagen.'])
                    ->withInput();
            }

            $validated['main_image'] = $newImagePath;
            $newMainPath = $newImagePath;
        }

        $oldMainPath = $property->main_image;
        $removedPaths = [];
        $newGalleryPaths = [];

        try {
            DB::transaction(function () use ($property, $validated, $galleryUploads, $removeIds, $images, &$removedPaths, &$newGalleryPaths): void {
                $property->update($validated);
                $removedPaths = $images->remove($property, $removeIds);
                $newGalleryPaths = $images->add($property, $galleryUploads);
            });
        } catch (Throwable $exception) {
            $images->deleteFiles($newGalleryPaths);
            if ($newMainPath) {
                $images->deleteFiles([$newMainPath]);
            }

            throw $exception;
        }

        if ($newMainPath && $oldMainPath) {
            $images->deleteFiles([$oldMainPath]);
        }
        $images->deleteFiles($removedPaths);
        $property->refresh();

        if ($property->status !== $previousStatus) {
            $notifier->notify($property);
        }

        return redirect()->route('dashboard.properties.index')
            ->with('success', 'Propiedad actualizada exitosamente.');
    }

    public function destroy(Property $property, PropertyImageManager $images): RedirectResponse
    {
        $this->authorize('delete', $property);

        $imagePath = $property->main_image;
        $galleryPaths = $property->galleryImages()->pluck('path')->all();
        DB::transaction(fn () => $property->delete());

        $images->deleteFiles(array_filter([$imagePath, ...$galleryPaths]));

        return redirect()->route('dashboard.properties.index')
            ->with('success', 'Propiedad eliminada exitosamente.');
    }
}
