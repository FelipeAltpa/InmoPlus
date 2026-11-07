<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);
        $users = User::withCount('properties')->latest()->paginate(15);

        return view('dashboard.users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        $user = new User(['role' => 'agent', 'is_active' => true]);

        return view('dashboard.users.form', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        $data = $this->validated($request);
        $user = DB::transaction(function () use ($data) {
            $user = User::create($data + ['password' => Str::random(48)]);
            if ($user->role === 'client') {
                Client::create(['user_id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone, 'status' => 'active']);
            }

            return $user;
        });

        // Se envía fuera de la transacción: solo si el usuario ya quedó guardado.
        Password::sendResetLink(['email' => $user->email]);

        return redirect()->route('users.index')->with('success', 'Usuario creado. Se envió el enlace para definir su contraseña.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('dashboard.users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        abort_if($user->is($request->user()) && ($request->input('role') !== 'admin' || ! $request->boolean('is_active')), 422, 'No puedes cambiar o desactivar tu propia cuenta de administrador.');
        $data = $this->validated($request, $user);
        $roleChanged = $user->role !== $data['role'];
        DB::transaction(function () use ($data, $user, $roleChanged) {
            $user->update($data);
            if ($data['role'] === 'client') {
                Client::updateOrCreate(['user_id' => $user->id], ['name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'] ?? null, 'status' => 'active']);
            }
            if ($roleChanged || ! $data['is_active']) {
                $user->increment('token_version');
            }
        });

        return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
    }

    public function toggle(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        abort_if($user->is($request->user()), 422, 'No puedes desactivar tu propia cuenta.');
        $user->is_active = ! $user->is_active;
        if (! $user->is_active) {
            $user->token_version++;
        }
        $user->save();

return back()->with('success', $user->is_active ? 'Cuenta activada.' : 'Cuenta desactivada y tokens revocados.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)], 'phone' => ['nullable', 'string', 'max:30'], 'role' => ['required', Rule::in(['admin', 'agent', 'client'])], 'is_active' => ['sometimes', 'boolean']]) + ['is_active' => $request->boolean('is_active',true)];
    }
}
