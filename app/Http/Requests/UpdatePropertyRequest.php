<?php

namespace App\Http\Requests;

use App\Models\Property;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array|string> */
    public function rules(): array
    {
        return [
            'property_type_id' => ['required', 'exists:property_types,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['required', 'integer', 'min:0'],
            'bathrooms' => ['required', 'integer', 'min:0'],
            'operation_type' => ['required', Rule::in(['venta', 'alquiler'])],
            'status' => ['required', Rule::in(['disponible', 'reservado', 'vendido', 'alquilado'])],
            'main_image' => [
                'nullable', 'file', 'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
            ],
            'gallery_images' => ['nullable', 'array', 'max:3'],
            'gallery_images.*' => [
                'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
            ],
            'remove_gallery_images' => ['nullable', 'array', 'max:3'],
            'remove_gallery_images.*' => [
                'integer', 'distinct', Rule::exists('property_images', 'id')->where('property_id', $this->route('property')?->id),
            ],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            $property = $this->route('property');

            if ($validator->errors()->isNotEmpty() || ! $property instanceof Property) {
                return;
            }

            $remaining = $property->galleryImages()->count() - count($this->input('remove_gallery_images') ?? []);
            $uploads = count($this->file('gallery_images', []));

            if ($remaining + $uploads > 3) {
                $validator->errors()->add('gallery_images', 'La propiedad puede tener máximo 3 fotos adicionales.');
            }
        }];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'property_type_id' => 'tipo de propiedad',
            'title' => 'título',
            'description' => 'descripción',
            'address' => 'dirección',
            'city' => 'ciudad',
            'neighborhood' => 'barrio',
            'price' => 'precio',
            'area' => 'área',
            'bedrooms' => 'dormitorios',
            'bathrooms' => 'baños',
            'operation_type' => 'tipo de operación',
            'status' => 'estado',
            'main_image' => 'imagen principal',
            'gallery_images' => 'fotos adicionales',
            'gallery_images.*' => 'foto adicional',
            'remove_gallery_images.*' => 'foto seleccionada para eliminar',
        ];
    }
}
