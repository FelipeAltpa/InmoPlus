<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::with('user')->latest()->get();

        return view('dashboard.clients.index', compact('clients'));
    }

    public function create(): View
    {
        $this->authorize('create', Client::class);

        return view('dashboard.clients.create');
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        $this->authorize('create', Client::class);

        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated): User {
            $user = User::create([
                'name' => $validated['name'],
                // Placeholder emails keep the login account valid until an admin adds a real one.
                'email' => $validated['email'] ?? $this->placeholderEmail($validated['name']),
                'phone' => $validated['phone'] ?? null,
                'role' => 'client',
                // New accounts get a random password.
                'password' => Hash::make(Str::random(64)),
            ]);
            // email_verified_at no es fillable: create() lo descartaba.
            $user->markEmailAsVerified();

            Client::create([...$validated, 'user_id' => $user->id]);

            return $user;
        });

        if (! str_ends_with($user->email, '@inmoplus.local')) {
            Password::sendResetLink(['email' => $user->email]);
        }

        return redirect()->route('clients.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Client $client): View
    {
        $this->authorize('view', $client);

        $client->load('user');

        return view('dashboard.clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        $this->authorize('update', $client);

        return view('dashboard.clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorize('update', $client);

        $validated = $request->validated();

        $previousEmail = $client->user?->email;
        $user = DB::transaction(function () use ($client, $validated): ?User {
            // Status changes here affect the CRM record, not the login role.
            if ($client->user) {
                $client->user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? $client->user->email,
                ]);
            }

            $client->update($validated);

            return $client->user;
        });

        if ($user && $user->email !== $previousEmail && ! str_ends_with($user->email, '@inmoplus.local')) {
            Password::sendResetLink(['email' => $user->email]);
        }

        return redirect()->route('clients.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    // The .invalid domain prevents placeholder addresses from receiving mail.
    private function placeholderEmail(string $name): string
    {
        return 'cliente-'.Str::slug($name).'-'.Str::lower(Str::random(6)).'@inmoplus.local';
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->authorize('delete', $client);

        DB::transaction(function () use ($client) {
            // The client row references this account, so delete it first.
            $userId = $client->user_id;
            $client->delete();

            if ($userId !== null) {
                User::whereKey($userId)->delete();
            }
        });

        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}
