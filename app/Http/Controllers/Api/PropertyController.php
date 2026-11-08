<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Services\PropertyFilter;
use App\Services\PropertyImageManager;
use App\Services\PropertyStatusNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class PropertyController extends Controller
{
    public function index(Request $request, PropertyFilter $filters): AnonymousResourceCollection
    {
        return PropertyResource::collection(
            $filters->apply(Property::with(['propertyType', 'user', 'galleryImages'])->where('status', 'disponible'), $request)
                ->get()
        );
    }

    public function show(Property $property): PropertyResource
    {
        if ($property->status !== 'disponible') {
            abort(404);
        }

        $property->load(['propertyType', 'user', 'galleryImages']);

        return new PropertyResource($property);
    }

    public function store(StorePropertyRequest $request, PropertyImageManager $images): JsonResponse
    {
        $this->authorize('create', Property::class);

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $galleryUploads = $validated['gallery_images'] ?? [];
        unset($validated['gallery_images']);

        $storedPaths = [];
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('properties', 'public');
            if (! $mainImagePath) {
                throw new RuntimeException('No se pudo guardar la imagen principal.');
            }

            $storedPaths[] = $mainImagePath;
            $validated['main_image'] = $mainImagePath;
        }

        try {
            $property = DB::transaction(function () use ($validated, $galleryUploads, $images, &$storedPaths): Property {
                $property = Property::create($validated);
                $storedPaths = [...$storedPaths, ...$images->add($property, $galleryUploads)];

                return $property;
            });
        } catch (Throwable $exception) {
            $images->deleteFiles($storedPaths);
            throw $exception;
        }

        $property->load(['propertyType', 'user', 'galleryImages']);

        return (new PropertyResource($property))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdatePropertyRequest $request, Property $property, PropertyStatusNotifier $notifier, PropertyImageManager $images): PropertyResource
    {
        $this->authorize('update', $property);

        $previousStatus = $property->status;
        $validated = $request->validated();
        $galleryUploads = $validated['gallery_images'] ?? [];
        $removeIds = $validated['remove_gallery_images'] ?? [];

        // Ownership comes from the authenticated user, not the request.
        unset($validated['user_id'], $validated['gallery_images'], $validated['remove_gallery_images']);

        $oldMainPath = $property->main_image;
        $newMainPath = null;
        if ($request->hasFile('main_image')) {
            $newMainPath = $request->file('main_image')->store('properties', 'public');
            if (! $newMainPath || ! Storage::disk('public')->exists($newMainPath)) {
                throw new RuntimeException('No se pudo guardar la nueva imagen.');
            }

            $validated['main_image'] = $newMainPath;
        }

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

        if ($property->status !== $previousStatus) {
            $notifier->notify($property);
        }

        $property->load(['propertyType', 'user', 'galleryImages']);

        return new PropertyResource($property);
    }

    public function destroy(Property $property, PropertyImageManager $images): JsonResponse
    {
        $this->authorize('delete', $property);

        $imagePath = $property->main_image;
        $galleryPaths = $property->galleryImages()->pluck('path')->all();

        DB::transaction(fn () => $property->delete());

        $images->deleteFiles(array_filter([$imagePath, ...$galleryPaths]));

        return response()->json(null, 204);
    }
}
