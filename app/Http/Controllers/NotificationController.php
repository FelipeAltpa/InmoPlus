<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, string $notification): RedirectResponse
    {
        $notification = $request->user()->notifications()
            ->whereKey($notification)
            ->firstOrFail();

        $notification->markAsRead();

        if (data_get($notification->data, 'kind') === 'property_status_changed') {
            return redirect()->route('client.interests');
        }

        return redirect()->route('dashboard.properties.show', data_get($notification->data, 'property_id'));
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Notificaciones marcadas como leídas.');
    }
}
