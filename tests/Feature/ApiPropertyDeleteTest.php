<?php

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

it('keeps a property image when deleting the database row fails', function () {
    Storage::fake('public');

    $agent = User::factory()->agent()->create();
    $property = Property::factory()->create([
        'user_id' => $agent->id,
        'main_image' => 'properties/test-image.jpg',
    ]);
    Storage::disk('public')->put($property->main_image, 'image contents');

    Property::deleting(function (Property $deleting) use ($property): void {
        if ($deleting->is($property)) {
            throw new RuntimeException('Simulated database delete failure.');
        }
    });

    $token = JWTAuth::claims(['abilities' => ['properties:write']])->fromUser($agent);
    $this->withoutExceptionHandling();

    try {
        $this->withToken($token)->deleteJson('/api/properties/'.$property->id);
        $this->fail('The simulated delete failure was not raised.');
    } catch (RuntimeException $exception) {
        $this->assertSame('Simulated database delete failure.', $exception->getMessage());
    } finally {
        Event::forget('eloquent.deleting: '.Property::class);
    }

    Storage::disk('public')->assertExists($property->main_image);
    $this->assertDatabaseHas('properties', ['id' => $property->id]);
});
