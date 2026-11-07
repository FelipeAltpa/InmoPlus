<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /** @return array<string, ValidationRule|array|string> */
    public function rules(): array
    {
        $propertyTypeId = $this->route('property_type')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('property_types', 'name')->ignore($propertyTypeId)],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'status' => 'estado',
        ];
    }
}