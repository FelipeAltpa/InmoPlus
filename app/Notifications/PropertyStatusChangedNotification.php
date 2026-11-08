<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PropertyStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $propertyId,
        public string $propertyTitle,
        public string $status,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** Get the database representation of the notification. */
    public function toDatabase(object $notifiable): array
    {
        return [
            'kind' => 'property_status_changed',
            'title' => 'Cambio en una propiedad de tu interés',
            'message' => "«{$this->propertyTitle}» ahora figura como {$this->status}.",
            'property_id' => $this->propertyId,
        ];
    }
}
