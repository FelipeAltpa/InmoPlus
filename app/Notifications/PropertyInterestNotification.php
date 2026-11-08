<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PropertyInterestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $propertyId,
        public string $propertyTitle,
        public string $clientName,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'kind' => 'property_interest',
            'title' => 'Nuevo interés en una propiedad',
            'message' => "{$this->clientName} marcó interés en «{$this->propertyTitle}».",
            'client_name' => $this->clientName,
            'property_id' => $this->propertyId,
        ];
    }
}
