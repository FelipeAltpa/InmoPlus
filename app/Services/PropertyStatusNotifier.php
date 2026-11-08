<?php

namespace App\Services;

use App\Models\Property;
use App\Notifications\PropertyStatusChangedNotification;

class PropertyStatusNotifier
{
    public function notify(Property $property): void
    {
        $property->clients()->with('user')->get()->each(function ($client) use ($property): void {
            if ($client->user?->isClient()) {
                $client->user->notify(new PropertyStatusChangedNotification(
                    propertyId: $property->id,
                    propertyTitle: $property->title,
                    status: $property->status,
                ));
            }
        });
    }
}
