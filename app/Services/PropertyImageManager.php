<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class PropertyImageManager
{
    /** @param array<int, UploadedFile> $uploads */
    public function add(Property $property, array $uploads): array
    {
        $paths = [];

        try {
            foreach ($uploads as $upload) {
                $path = $upload->store('properties', 'public');

                if (! $path) {
                    throw new RuntimeException('No se pudo guardar una foto de la propiedad.');
                }

                $paths[] = $path;
                $property->galleryImages()->create(['path' => $path]);
            }
        } catch (Throwable $exception) {
            $this->deleteFiles($paths);
            throw $exception;
        }

        return $paths;
    }

    public function remove(Property $property, array $ids): array
    {
        $images = $property->galleryImages()->whereKey($ids)->get();
        $images->each->delete();

        return $images->pluck('path')->all();
    }

    public function deleteFiles(array $paths): void
    {
        Storage::disk('public')->delete($paths);
    }
}
