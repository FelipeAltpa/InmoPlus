<?php

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('el agente solo ve sus propiedades y puede buscar', function () {
    $agent = User::factory()->agent()->create();
    $mine = Property::factory()->create(['user_id' => $agent->id, 'title' => 'Casa Azul']);
    $other = Property::factory()->create(['title' => 'Casa Azul Ajena']);

    $this->actingAs($agent)
        ->get('/dashboard/properties?search=Azul')
        ->assertOk()
        ->assertSee($mine->title)
        ->assertDontSee($other->title);
});

it('rechaza una operación inválida en el listado web', function () {
    $this->actingAs(User::factory()->agent()->create())
        ->get('/dashboard/properties?operation_type=trueque')
        ->assertSessionHasErrors('operation_type');
});
