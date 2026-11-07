<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'InmoPlus') }}</title>

                <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

                @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/datatables.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
                        @include('components.sidebar', ['sidebarOpen' => 'sidebarOpen'])

                        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
                                @include('components.navbar', ['showSidebarToggle' => true])

                                <main class="flex-1">
                                        @include('components.alerts')

                                        @isset($header)
                        <header class="bg-white shadow-sm border-b border-gray-200">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

                <style>
            /* Slide down animation for mobile menu */
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-slide-down {
                animation: slideDown 0.2s ease-out;
            }

            /* Slide in animation for toasts */
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(100%);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .animate-slide-in {
                animation: slideIn 0.3s ease-out;
            }

            /* Scrollbar styling */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #a1a1a1;
            }
        </style>
        @stack('scripts')
    </body>
</html>
