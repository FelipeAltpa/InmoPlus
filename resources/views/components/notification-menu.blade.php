<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button type="button" @click="open = !open" :aria-expanded="open"
            class="relative rounded-full p-2 text-gray-500 transition-colors duration-150 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            aria-label="Notificaciones">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($navbarUnreadCount > 0)
            <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-semibold leading-none text-white">
                {{ $navbarUnreadCount > 99 ? '99+' : $navbarUnreadCount }}
            </span>
        @endif
    </button>

    <div x-cloak x-show="open" x-transition
         class="absolute right-0 z-50 mt-2 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <h2 class="text-sm font-semibold text-gray-900">Notificaciones</h2>
            @if($navbarUnreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                        Marcar todas como leídas
                    </button>
                </form>
            @endif
        </div>

        @forelse($navbarNotifications as $notification)
            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                @csrf
                <button type="submit" class="flex w-full gap-3 border-b border-gray-100 px-4 py-3 text-left transition-colors hover:bg-gray-50 last:border-b-0">
                    <span class="mt-1.5 h-2 w-2 flex-none rounded-full {{ $notification->read_at ? 'bg-transparent' : 'bg-indigo-600' }}" aria-hidden="true"></span>
                    <span class="min-w-0">
                        <span class="block text-sm font-medium text-gray-900">{{ data_get($notification->data, 'title', 'Notificación') }}</span>
                        <span class="mt-0.5 block text-sm text-gray-600">{{ data_get($notification->data, 'message', '') }}</span>
                        <span class="mt-1 block text-xs text-gray-400">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                    </span>
                </button>
            </form>
        @empty
            <p class="px-4 py-6 text-center text-sm text-gray-500">No tienes notificaciones todavía.</p>
        @endforelse
    </div>
</div>
