<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /** @return array<string, ValidationRule|array|string> */
    public function rules(): array
    {
        $clientId = $this->route('client')?->id;

        // Exclude the linked rows so an unchanged email remains valid on update.
        $clientEmailRule = Rule::unique('clients', 'email')->ignore($clientId);
        $userEmailRule = Rule::unique('users', 'email')->ignore($this->linkedUserId($clientId));

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', $clientEmailRule, $userEmailRule],
            'phone' => ['nullable', 'string', 'max:255'],
            'identification' => ['nullable', 'string', 'max:50', Rule::unique('clients', 'identification')->ignore($clientId)],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'identification' => 'identificación',
            'address' => 'dirección',
            'city' => 'ciudad',
            'status' => 'estado',
        ];
    }

    // Used to ignore this client's login account in the users email rule.
    private function linkedUserId(?int $clientId): ?int
    {
        if ($clientId === null) {
            return null;
        }

        return Client::with('user')->find($clientId)?->user_id;
    }
}
