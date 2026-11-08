<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

it('envía el correo de verificación al registrarse', function () {
    Notification::fake();

    $this->post('/register', [
        'name' => 'Cliente Nuevo',
        'email' => 'nuevo@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'nuevo@example.com')->firstOrFail();

    expect($user->hasVerifiedEmail())->toBeFalse();
    Notification::assertSentTo($user, VerifyEmail::class);
});

it('bloquea las rutas con login hasta verificar el correo', function () {
    $user = User::factory()->client()->unverified()->create();

    $this->actingAs($user)
        ->get(route('client.properties.index'))
        ->assertRedirect(route('verification.notice'));
});

it('verifica el correo con el enlace firmado', function () {
    $user = User::factory()->client()->unverified()->create();

    $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
        'id' => $user->id,
        'hash' => sha1($user->email),
    ]);

    $this->actingAs($user)->get($url)->assertRedirect();

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('las cuentas creadas por el administrador quedan verificadas', function () {
    Notification::fake();
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('clients.store'), [
        'name' => 'Cliente Panel',
        'email' => 'panel@example.com',
        'status' => 'active',
    ])->assertRedirect(route('clients.index'));

    $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Agente Panel',
        'email' => 'agente.panel@example.com',
        'role' => 'agent',
        'is_active' => true,
    ])->assertRedirect(route('users.index'));

    expect(User::where('email', 'panel@example.com')->first()->hasVerifiedEmail())->toBeTrue()
        ->and(User::where('email', 'agente.panel@example.com')->first()->hasVerifiedEmail())->toBeTrue();
});
