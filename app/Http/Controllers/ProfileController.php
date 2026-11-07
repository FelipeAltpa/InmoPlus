<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except(['profile_photo']);
        $user = $request->user();
        $oldPhoto = $user->profile_photo;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            if ($path && Storage::disk('public')->exists($path)) {
                $validated['profile_photo'] = $path;
            }
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Elimina la anterior después de guardar la nueva referencia.
        if ($oldPhoto && $oldPhoto !== $user->profile_photo) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return Redirect::route('profile.edit')->with('success', 'Perfil actualizado correctamente.');
    }

    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_photo) {
            if (Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->profile_photo = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('success', 'Foto de perfil eliminada.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Las propiedades referencian al usuario (FK sin cascada).
        if ($user->properties()->exists()) {
            return back()->with('error', 'No puedes eliminar tu cuenta mientras tengas propiedades. Reasígnalas o elimínalas primero.');
        }

        Auth::logout();

        DB::transaction(function () use ($user) {
            // clients.user_id también es FK sin cascada: se borra primero el cliente.
            $user->client?->delete();
            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
