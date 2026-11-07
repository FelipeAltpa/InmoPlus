@if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
    <div class="fixed top-4 right-4 z-50 space-y-2 w-full max-w-md" x-data="alpineToasts()">
        @foreach(['success', 'error', 'warning', 'info'] as $type)
            @if (session()->has($type))
                <div x-show="show{{ ucfirst($type) }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="transform opacity-0 translate-x-full" x-transition:enter-end="transform opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform opacity-100 translate-x-0" x-transition:leave-end="transform opacity-0 translate-x-full"
                     class="flex items-start p-4 rounded-lg shadow-lg border-l-4 animate-slide-in"
                     :class="{
                        'bg-green-50 border-green-500 text-green-800': '{{ $type }}' === 'success',
                        'bg-red-50 border-red-500 text-red-800': '{{ $type }}' === 'error',
                        'bg-yellow-50 border-yellow-500 text-yellow-800': '{{ $type }}' === 'warning',
                        'bg-blue-50 border-blue-500 text-blue-800': '{{ $type }}' === 'info'
                     }"
                     role="alert" aria-live="polite" aria-atomic="true">
                    <div class="flex-shrink-0 mt-0.5">
                        @if ($type === 'success')
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @elseif ($type === 'error')
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        @elseif ($type === 'warning')
                            <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.765 1.36.765 3.576 0 4.936C17.802 18.328 16.049 19 14.017 19H5.983C3.951 19 2.198 18.328 1.136 17.056c-.765-1.36-.765-3.576 0-4.936l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium">{{ session($type) }}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="hide{{ ucfirst($type) }}()"
                                class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                :class="{
                                    'text-green-500 hover:bg-green-100 focus:ring-green-500': '{{ $type }}' === 'success',
                                    'text-red-500 hover:bg-red-100 focus:ring-red-500': '{{ $type }}' === 'error',
                                    'text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-500': '{{ $type }}' === 'warning',
                                    'text-blue-500 hover:bg-blue-100 focus:ring-blue-500': '{{ $type }}' === 'info'
                                }"
                                aria-label="Cerrar">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <script>
        function alpineToasts() {
            return {
                showSuccess: {{ session()->has('success') ? 'true' : 'false' }},
                showError: {{ session()->has('error') ? 'true' : 'false' }},
                showWarning: {{ session()->has('warning') ? 'true' : 'false' }},
                showInfo: {{ session()->has('info') ? 'true' : 'false' }},

                hideSuccess() { this.showSuccess = false; },
                hideError() { this.showError = false; },
                hideWarning() { this.showWarning = false; },
                hideInfo() { this.showInfo = false; },

                init() {
                    // Auto-hide after 5 seconds
                    if (this.showSuccess) setTimeout(() => this.hideSuccess(), 5000);
                    if (this.showError) setTimeout(() => this.hideError(), 8000);
                    if (this.showWarning) setTimeout(() => this.hideWarning(), 6000);
                    if (this.showInfo) setTimeout(() => this.hideInfo(), 5000);
                }
            }
        }
    </script>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium">Por favor corrige los siguientes errores:</h3>
                <div class="mt-2 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif