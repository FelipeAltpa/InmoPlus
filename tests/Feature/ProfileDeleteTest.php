<?php

use App\Models\Client;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('permite a un cliente eliminar su cuenta', function () {
    $user = User::factory()->create(['role' => 'client']);
    Client::factory()->create(['user_id' => $user->id, 'email' => $user->email]);

    $this->actingAs($user)
        ->delete('/profile', ['password' => 'password'])
        ->assertRedirect('/');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('clients', ['user_id' => $user->id]);
});

it('no elimina la cuenta de un agente con propiedades', function () {
    $agent = User::factory()->agent()->create();
    Property::factory()->create(['user_id' => $agent->id]);

    $this->actingAs($agent)
        ->delete('/profile', ['password' => 'password'])
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $agent->id]);
});
