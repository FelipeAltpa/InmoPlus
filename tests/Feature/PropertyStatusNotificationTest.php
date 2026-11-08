<?php

use App\Models\Client;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use App\Notifications\PropertyStatusChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

it('notifies interested clients when the API changes a property status', function () {
    Notification::fake();

    $agent = User::factory()->agent()->create();
    $clientUser = User::factory()->client()->create();
    $client = Client::factory()->create(['user_id' => $clientUser->id]);
    $property = Property::factory()->forSale()->available()->create([
        'user_id' => $agent->id,
        'property_type_id' => PropertyType::factory()->active(),
    ]);
    $property->clients()->attach($client);

    $token = JWTAuth::claims(['abilities' => ['properties:write']])->fromUser($agent);

    $this->withToken($token)->putJson('/api/properties/'.$property->id, [
        'property_type_id' => $property->property_type_id,
        'title' => $property->title,
        'address' => $property->address,
        'city' => $property->city,
        'price' => $property->price,
        'bedrooms' => $property->bedrooms,
        'bathrooms' => $property->bathrooms,
        'operation_type' => $property->operation_type,
        'status' => 'vendido',
    ])->assertOk();

    Notification::assertSentTo($clientUser, PropertyStatusChangedNotification::class);
});

it('notifies interested clients when the web form changes a property status', function () {
    Notification::fake();

    $agent = User::factory()->agent()->create();
    $clientUser = User::factory()->client()->create();
    $client = Client::factory()->create(['user_id' => $clientUser->id]);
    $property = Property::factory()->forSale()->available()->create([
        'user_id' => $agent->id,
        'property_type_id' => PropertyType::factory()->active(),
    ]);
    $property->clients()->attach($client);

    $this->actingAs($agent)->put(route('dashboard.properties.update', $property), [
        'property_type_id' => $property->property_type_id,
        'title' => $property->title,
        'address' => $property->address,
        'city' => $property->city,
        'price' => $property->price,
        'bedrooms' => $property->bedrooms,
        'bathrooms' => $property->bathrooms,
        'operation_type' => $property->operation_type,
        'status' => 'vendido',
    ])->assertRedirect(route('dashboard.properties.index'));

    Notification::assertSentTo($clientUser, PropertyStatusChangedNotification::class);
});
