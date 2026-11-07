@auth
    @php
        $navbarNotifications = auth()->user()->notifications()->latest()->limit(5)->get();
        $navbarUnreadCount = auth()->user()->unreadNotifications()->count();
    @endphp
@endauth

@if($showSidebarToggle ?? false)
<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
                        <div class="flex items-center">
                                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <svg class="h-8 w-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zM5 7h14v2H5V7zm0 4h14v2H5v-2zm0 4h14v2H5v-2z"/>
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">InmoPlus</span>
                    </a>
                </div>

                                <div class="hidden md:ml-8 md:flex md:items-center md:space-x-4">
                                        <a href="{{ route('public.properties.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-150 {{ request()->routeIs('public.properties.index') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                        Propiedades
                    </a>

                                        @auth
                        @if(auth()->user()?->role === 'client')
                            <a href="{{ route('client.properties.index') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-150 {{ request()->routeIs('client.properties.*') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                                Propiedades
                            </a>
                            <a href="{{ route('client.interests') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-150 {{ request()->routeIs('client.interests') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                                Mis Intereses
                            </a>
                            <a href="{{ route('client.visits.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Mis visitas</a>
                        @elseif(auth()->user()?->role === 'admin' || auth()->user()?->role === 'agent')
                            <a href="{{ route('dashboard.properties.index') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-150 {{ request()->routeIs('dashboard.properties.*') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                                Todas las Propiedades
                            </a>
                            <a href="{{ route('dashboard.properties.create') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-150">
                                + Nueva Propiedad
                            </a>
                            <a href="{{ route('dashboard.visits.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">Agenda de visitas</a>
                        @else
                            <a href="{{ route('dashboard') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                                Panel
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

                        <div class="hidden md:flex md:items-center md:space-x-4">
                @guest
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors duration-150">
                        Iniciar Sesión
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors duration-150">
                            Registrarse
                        </a>
                    @endif
                @else
                    @include('components.notification-menu')

                                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="flex items-center space-x-2 p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                aria-label="Menú de usuario" aria-expanded="false" aria-haspopup="true">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="h-8 w-8 rounded-full object-cover ring-2 ring-white">
                            @else
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center ring-2 ring-white">
                                    <span class="text-sm font-medium text-indigo-600">{{ strtoupper(auth()->user()->name[0]) }}</span>
                                </div>
                            @endif
                            <svg class="h-4 w-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200 ring-1 ring-black ring-opacity-5">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                            </div>

                            @if(auth()->user()?->role === 'client')
                                <a href="{{ route('client.properties.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Propiedades
                                </a>
                                <a href="{{ route('client.interests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    Mis Intereses
                                </a>
                                <a href="{{ route('client.visits.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mis visitas</a>
                            @elseif(auth()->user()?->role === 'admin' || auth()->user()?->role === 'agent')
                                <a href="{{ route('dashboard.properties.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Todas las Propiedades
                                </a>
                                <a href="{{ route('dashboard.properties.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nueva Propiedad
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    Panel de Control
                                </a>
                            @endif

                            @if(!auth()->user()->isClient())
                                <a href="{{ route('public.properties.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver Sitio Público
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Mi Perfil
                            </a>

                            <hr class="border-gray-100 my-1">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

                        <div class="flex items-center md:hidden">
                @auth
                    @include('components.notification-menu')
                @endauth
                @if($showSidebarToggle ?? false)
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        aria-label="Abrir menú lateral" :aria-expanded="sidebarOpen" aria-controls="sidebar">
                    <svg x-show="!sidebarOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="sidebarOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @else
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        aria-label="Abrir menú de navegación" :aria-expanded="mobileMenuOpen" aria-controls="mobile-navigation">
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </div>

    @unless($showSidebarToggle ?? false)
        <div id="mobile-navigation" x-cloak x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-gray-200 bg-white">
            <nav class="space-y-1 px-4 py-3" aria-label="Navegación móvil">
                <a href="{{ route('public.properties.index') }}" @click="mobileMenuOpen = false" class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">Propiedades</a>
                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('client.interests') }}" @click="mobileMenuOpen = false" class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">Mis intereses</a>
                        <a href="{{ route('client.visits.index') }}" @click="mobileMenuOpen = false" class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">Mis visitas</a>
                    @else
                        <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">Panel de control</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" @click="mobileMenuOpen = false" class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">Mi perfil</a>
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="w-full rounded-md py-3 text-left text-base font-medium text-red-600 hover:bg-red-50">Cerrar sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="block rounded-md px-3 py-3 text-base font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">Iniciar sesión</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="block rounded-md px-3 py-3 text-base font-medium text-indigo-700 hover:bg-indigo-50">Registrarse</a>
                    @endif
                @endauth
            </nav>
        </div>
    @endunless
</nav>
@else
    @php
        $isClientMarketplace = request()->routeIs('client.*');
        $propertyTypesForNav = isset($propertyTypes) ? $propertyTypes : \App\Models\PropertyType::active()->get();
        $marketplaceSearchRoute = $isClientMarketplace ? 'client.properties.index' : 'public.properties.index';
        $categoryQuery = request()->except(['property_type_id']);
    @endphp
    <nav class="sticky top-0 z-50 border-b border-slate-100 bg-white/80 backdrop-blur" x-data="{ accountOpen: false }">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-6 lg:px-10">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200" aria-label="InmoPlus, inicio">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-500 text-white">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                </span>
                <span class="text-lg font-semibold tracking-tight text-slate-900">InmoPlus</span>
            </a>

            <form method="GET" action="{{ route($marketplaceSearchRoute) }}" class="hidden min-w-0 max-w-md flex-1 md:block">
                @foreach(['operation_type', 'property_type_id', 'min_price', 'max_price', 'page'] as $filter)
                    @if(request()->filled($filter))<input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">@endif
                @endforeach
                <label for="marketplace-search" class="sr-only">Buscar propiedades</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    <input id="marketplace-search" name="search" value="{{ request('search') }}" placeholder="Buscar propiedades o ubicaciones" class="h-11 w-full rounded-full border border-slate-200/70 bg-slate-50 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-500 focus-visible:border-blue-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">
                </div>
            </form>

            <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                <div class="flex h-10 items-center rounded-full border border-slate-200/70 bg-white px-1 sm:px-3 [&_label]:sr-only [&_[data-currency-select]]:h-8 [&_[data-currency-select]]:w-[4.5rem] [&_[data-currency-select]]:rounded-full [&_[data-currency-select]]:border-0 [&_[data-currency-select]]:bg-transparent [&_[data-currency-select]]:py-1 [&_[data-currency-select]]:pl-2 [&_[data-currency-select]]:pr-5 [&_[data-currency-select]]:text-xs sm:[&_[data-currency-select]]:w-32 [&_[data-currency-status]]:sr-only">
                    <x-currency-switcher id="marketplace-currency" />
                </div>
                @auth
                    <div class="hidden sm:block [&_button]:h-10 [&_button]:rounded-full [&_button]:border [&_button]:border-slate-200/70 [&_button]:bg-white [&_button]:px-3 [&_button]:transition [&_button]:hover:bg-slate-50">
                        @include('components.notification-menu')
                    </div>
                    <div class="relative" @click.outside="accountOpen = false">
                        <button type="button" @click="accountOpen = !accountOpen" :aria-expanded="accountOpen" aria-haspopup="true" class="inline-flex h-10 items-center gap-2 rounded-full border border-slate-200/70 bg-white px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="" class="h-7 w-7 rounded-full object-cover">
                            @else
                                <span class="grid h-7 w-7 place-items-center rounded-full bg-blue-100 text-xs font-semibold text-blue-700">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                            @endif
                            <span class="hidden max-w-28 truncate lg:inline">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                        </button>
                        <div x-cloak x-show="accountOpen" x-transition class="absolute right-0 z-50 mt-2 w-64 overflow-hidden rounded-2xl border border-slate-200/70 bg-white py-2 shadow-xl">
                            <div class="border-b border-slate-100 px-4 pb-3 pt-2"><p class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p><p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p><p class="mt-1 text-xs capitalize text-slate-500">{{ auth()->user()->role }}</p></div>
                            @if(auth()->user()->isClient())
                                <a href="{{ route('client.properties.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Propiedades</a>
                                <a href="{{ route('client.interests') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Mis intereses</a>
                                <a href="{{ route('client.visits.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Mis visitas</a>
                            @elseif(auth()->user()->isAdmin() || auth()->user()->isAgent())
                                <a href="{{ route('dashboard.properties.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Panel de propiedades</a>
                                <a href="{{ route('dashboard.properties.create') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Publicar propiedad</a>
                                <a href="{{ route('dashboard.visits.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Agenda de visitas</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Panel de control</a>
                            @endif
                            @unless(auth()->user()->isClient())<a href="{{ route('public.properties.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Ver sitio público</a>@endunless
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Mi perfil</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-1">@csrf<button type="submit" class="w-full px-4 py-2 text-left text-sm font-medium text-red-700 hover:bg-red-50">Cerrar sesión</button></form>
                        </div>
                    </div>
                @else
                    <div class="relative" @click.outside="accountOpen = false">
                        <button type="button" @click="accountOpen = !accountOpen" :aria-expanded="accountOpen" aria-haspopup="true" class="inline-flex h-10 items-center gap-2 rounded-full border border-slate-200/70 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">
                            Cuenta<svg class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                        </button>
                        <div x-cloak x-show="accountOpen" x-transition class="absolute right-0 z-50 mt-2 w-48 overflow-hidden rounded-2xl border border-slate-200/70 bg-white py-2 shadow-xl">
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Iniciar sesión</a>
                            @if(Route::has('register'))<a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Crear cuenta</a>@endif
                        </div>
                    </div>
                @endauth
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="grid h-10 w-10 place-items-center rounded-full border border-slate-200/70 bg-white text-slate-700 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200 md:hidden" aria-label="Abrir menú" :aria-expanded="mobileMenuOpen">
                    <svg x-show="!mobileMenuOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    <svg x-cloak x-show="mobileMenuOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
        </div>

        <div class="px-6 pb-3 md:hidden">
            <form method="GET" action="{{ route($marketplaceSearchRoute) }}" class="relative">
                @foreach(['operation_type', 'property_type_id', 'min_price', 'max_price', 'page'] as $filter)
                    @if(request()->filled($filter))<input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">@endif
                @endforeach
                <label for="marketplace-search-mobile" class="sr-only">Buscar propiedades</label>
                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                <input id="marketplace-search-mobile" name="search" value="{{ request('search') }}" placeholder="Buscar propiedades o ubicaciones" class="h-11 w-full rounded-full border border-slate-200/70 bg-slate-50 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">
            </form>
        </div>

        <div class="border-t border-slate-100">
            <div class="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-6 py-2.5 lg:px-10" aria-label="Categorías de propiedades">
                <a href="{{ route($marketplaceSearchRoute, $categoryQuery) }}" class="inline-flex h-10 shrink-0 items-center rounded-full px-4 text-sm font-medium transition hover:bg-slate-100 {{ request('property_type_id') ? 'text-slate-700' : 'bg-blue-200/70 text-slate-900' }}">Todas</a>
                @foreach($propertyTypesForNav as $type)
                    <a href="{{ route($marketplaceSearchRoute, array_merge($categoryQuery, ['property_type_id' => $type->id])) }}" class="inline-flex h-10 shrink-0 items-center rounded-full px-4 text-sm font-medium transition hover:bg-slate-100 {{ request('property_type_id') == $type->id ? 'bg-blue-200/70 text-slate-900' : 'text-slate-700' }}">{{ $type->name }}</a>
                @endforeach
            </div>
        </div>

        <div x-cloak x-show="mobileMenuOpen" x-transition class="border-t border-slate-100 bg-white px-6 py-3 md:hidden">
            <nav class="flex flex-col" aria-label="Navegación móvil">
                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('client.interests') }}" class="rounded-xl px-3 py-3 text-sm text-slate-700 hover:bg-slate-50">Mis intereses</a>
                        <a href="{{ route('client.visits.index') }}" class="rounded-xl px-3 py-3 text-sm text-slate-700 hover:bg-slate-50">Mis visitas</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="rounded-xl px-3 py-3 text-sm text-slate-700 hover:bg-slate-50">Panel de control</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="rounded-xl px-3 py-3 text-sm text-slate-700 hover:bg-slate-50">Mi perfil</a>
                    <form method="POST" action="{{ route('logout') }}" class="py-2">@csrf<button class="w-full rounded-xl px-3 py-3 text-left text-sm font-medium text-red-700 hover:bg-red-50">Cerrar sesión</button></form>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-3 py-3 text-sm text-slate-700 hover:bg-slate-50">Iniciar sesión</a>
                    @if(Route::has('register'))<a href="{{ route('register') }}" class="rounded-xl px-3 py-3 text-sm text-slate-700 hover:bg-slate-50">Crear cuenta</a>@endif
                @endauth
            </nav>
        </div>
    </nav>
@endif
