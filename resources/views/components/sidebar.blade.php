<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0" :class="{ '-translate-x-full': !sidebarOpen }" @keydown.escape.window="sidebarOpen = false">
        <div class="lg:hidden flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Menú</h2>
        <button @click="sidebarOpen = false"
                class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-150"
                aria-label="Cerrar menú lateral">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="flex flex-col h-full overflow-y-auto">
                <div class="p-4 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <svg class="h-8 w-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zM5 7h14v2H5V7zm0 4h14v2H5v-2zm0 4h14v2H5v-2z"/>
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
                </svg>
                <span class="text-xl font-bold text-gray-900">InmoPlus</span>
            </a>
            <p class="mt-1 text-xs text-gray-500">Panel de Administración</p>
        </div>

                <nav class="flex-1 p-4 space-y-1" aria-label="Navegación principal">
                        <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
               aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

                        @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isAgent()))
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Propiedades</h3>

                    <a href="{{ route('dashboard.properties.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('dashboard.properties.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                       aria-current="{{ request()->routeIs('dashboard.properties.*') ? 'page' : 'false' }}">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Todas las Propiedades
                    </a>

                    <a href="{{ route('dashboard.properties.create') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('dashboard.properties.create') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                       aria-current="{{ request()->routeIs('dashboard.properties.create') ? 'page' : 'false' }}">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nueva Propiedad
                    </a>
                </div>
            @endif

                        @can('create', \App\Models\PropertyType::class)
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Configuración</h3>

                    <a href="{{ route('property-types.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('property-types.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                       aria-current="{{ request()->routeIs('property-types.*') ? 'page' : 'false' }}">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Tipos de Propiedad
                    </a>
                </div>
            @endcan

                        @if(auth()->user() && auth()->user()->isAdmin())
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administración</h3>

                    <a href="{{ route('users.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('users.index') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                       aria-current="{{ request()->routeIs('users.index') ? 'page' : 'false' }}">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Usuarios
                    </a>

                    <a href="{{ route('clients.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('clients.*') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                       aria-current="{{ request()->routeIs('clients.*') ? 'page' : 'false' }}">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Clientes
                    </a>
                </div>
            @endif

                        @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isAgent()))
                <div>
                    <a href="{{ route('dashboard.visits.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard.visits.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">Agenda de visitas</a>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Reportes</h3>

                    <a href="{{ route('reports.index') }}"
                       class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                              {{ request()->routeIs('reports.index') || request()->routeIs('reports.properties.pdf') ? 'bg-amber-50 text-amber-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                       aria-current="{{ request()->routeIs('reports.index') || request()->routeIs('reports.properties.pdf') ? 'page' : 'false' }}">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Reportes y Estadísticas
                    </a>
                </div>
            @endif

                        <div>
                <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">API</h3>

                <a href="{{ url('/docs/api') }}" target="_blank" rel="noopener"
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                    <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    Documentación API (Swagger)
                </a>
            </div>
        </nav>

                <div class="p-4 border-t border-gray-200">
            <div class="flex items-center space-x-3">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                         alt="{{ auth()->user()->name }}"
                         class="h-10 w-10 rounded-full object-cover">
                @else
                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-lg font-medium text-indigo-600">{{ strtoupper(auth()->user()->name[0]) }}</span>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="mt-3 flex space-x-2">
                <a href="{{ route('profile.edit') }}"
                   class="flex-1 text-center px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-150">
                    Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex-1 text-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-150">
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen" @click="sidebarOpen = false"
     class="fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden transition-opacity duration-300"
     aria-hidden="true"></div>
