<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'city' => $this->city,
            'neighborhood' => $this->neighborhood,
            'address' => $this->address,
            'price' => $this->price,
            'area' => $this->area,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'operation_type' => $this->operation_type,
            'status' => $this->status,
            'property_type_id' => $this->property_type_id,
            'main_image' => $this->main_image ? asset('storage/'.$this->main_image) : null,
            'images' => collect($this->images)
                ->map(fn (string $image): string => asset('storage/'.$image))
                ->values(),
            'property_type' => [
                'id' => $this->propertyType->id,
                'name' => $this->propertyType->name,
            ],
            'agent' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'profile_photo' => $this->user->profile_photo ? asset('storage/'.$this->user->profile_photo) : null,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
