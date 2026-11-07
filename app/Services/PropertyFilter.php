<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PropertyFilter
{
    public function apply(Builder $query, Request $request): Builder
    {
        $request->validate([
            'operation_type' => ['sometimes', 'nullable', 'in:venta,alquiler'],
            'property_type_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'search' => ['sometimes', 'nullable', 'string', 'max:120'],
            'city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'neighborhood' => ['sometimes', 'nullable', 'string', 'max:100'],
            'min_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'bedrooms' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:30'],
            'bathrooms' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:30'],
            'min_area' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100000'],
            'sort' => ['sometimes', 'nullable', 'in:recent,price_asc,price_desc,area_desc'],
        ]);
        $query
            ->when($request->filled('operation_type'), fn ($q) => $q->where('operation_type', $request->input('operation_type')))
            ->when($request->filled('property_type_id'), fn ($q) => $q->where('property_type_id', $request->integer('property_type_id')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = trim($request->string('search')->toString());
                $q->where(function ($nested) use ($term) {
                    foreach (['title', 'description', 'address', 'city', 'neighborhood'] as $column) {
                        $nested->orWhere($column, 'like', "%{$term}%");
                    }
                });
            })
            ->when($request->filled('city'), fn ($q) => $q->where('city', 'like', '%'.trim($request->string('city')->toString()).'%'))
            ->when($request->filled('neighborhood'), fn ($q) => $q->where('neighborhood', 'like', '%'.trim($request->string('neighborhood')->toString()).'%'))
            ->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', $request->input('max_price')))
            ->when($request->filled('bedrooms'), fn ($q) => $q->where('bedrooms', '>=', $request->integer('bedrooms')))
            ->when($request->filled('bathrooms'), fn ($q) => $q->where('bathrooms', '>=', $request->integer('bathrooms')))
            ->when($request->filled('min_area'), fn ($q) => $q->where('area', '>=', $request->input('min_area')));

        match ($request->string('sort', 'recent')->toString()) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'area_desc' => $query->orderByDesc('area'),
            default => $query->latest(),
        };

        return $query;
    }
}
