@props(['title' => config('app.name', 'Laravel')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data: blob:;">

    <title>{{ $title }} | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles


    <style>
        [x-cloak] {
            display: none !important;
        }

        /* ================================================
           SISTEMA DE COLORES GLOBAL - MODO CLARO Y OSCURO
           ================================================ */

        /* ===== MODO CLARO (Por defecto) ===== */
        :root {
            /* Fondos principales */
            --color-bg-app: #f8fafc;
            --color-bg-primary: #ffffff;
            --color-bg-secondary: #f1f5f9;
            --color-bg-tertiary: #e2e8f0;
            --color-bg-hover: #f1f5f9;
            --color-bg-active: #dbeafe;
            
            /* Textos */
            --color-text-primary: #0f172a;
            --color-text-secondary: #475569;
            --color-text-tertiary: #64748b;
            --color-text-muted: #94a3b8;
            --color-text-inverse: #ffffff;
            
            /* Bordes */
            --color-border-primary: #e2e8f0;
            --color-border-secondary: #cbd5e1;
            --color-border-focus: #3b82f6;
            
            /* Sombras */
            --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            
            /* Azul (Primary) */
            --color-blue-50: #eff6ff;
            --color-blue-100: #dbeafe;
            --color-blue-500: #3b82f6;
            --color-blue-600: #2563eb;
            --color-blue-700: #1d4ed8;
            --color-blue-800: #1e40af;
            
            /* Verde (Success) */
            --color-green-50: #f0fdf4;
            --color-green-100: #dcfce7;
            --color-green-500: #22c55e;
            --color-green-600: #16a34a;
            --color-green-700: #15803d;
            
            /* Amarillo (Warning) */
            --color-yellow-50: #fefce8;
            --color-yellow-100: #fef9c3;
            --color-yellow-500: #eab308;
            --color-yellow-600: #ca8a04;
            
            /* Rojo (Danger) */
            --color-red-50: #fef2f2;
            --color-red-100: #fee2e2;
            --color-red-500: #ef4444;
            --color-red-600: #dc2626;
            --color-red-700: #b91c1c;
            
            /* Naranja */
            --color-orange-50: #fff7ed;
            --color-orange-500: #f97316;
            --color-orange-600: #ea580c;
            
            /* Morado */
            --color-purple-50: #faf5ff;
            --color-purple-500: #a855f7;
            --color-purple-600: #9333ea;
        }

        /* ===== MODO OSCURO ===== */
        .dark {
            /* Fondos principales */
            --color-bg-app: #020617;
            --color-bg-primary: #0f172a;
            --color-bg-secondary: #1e293b;
            --color-bg-tertiary: #334155;
            --color-bg-hover: #1e293b;
            --color-bg-active: #1e3a8a;
            
            /* Textos */
            --color-text-primary: #f1f5f9;
            --color-text-secondary: #cbd5e1;
            --color-text-tertiary: #94a3b8;
            --color-text-muted: #64748b;
            --color-text-inverse: #0f172a;
            
            /* Bordes */
            --color-border-primary: #334155;
            --color-border-secondary: #475569;
            --color-border-focus: #60a5fa;
            
            /* Sombras */
            --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.5);
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.6);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.7);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.8);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.9);
            
            /* Azul (Primary) - más brillante */
            --color-blue-50: #1e3a8a;
            --color-blue-100: #1e40af;
            --color-blue-500: #60a5fa;
            --color-blue-600: #3b82f6;
            --color-blue-700: #2563eb;
            --color-blue-800: #1d4ed8;
            
            /* Verde (Success) - más brillante */
            --color-green-50: #14532d;
            --color-green-100: #15803d;
            --color-green-500: #4ade80;
            --color-green-600: #22c55e;
            --color-green-700: #16a34a;
            
            /* Amarillo (Warning) - más brillante */
            --color-yellow-50: #713f12;
            --color-yellow-100: #854d0e;
            --color-yellow-500: #fbbf24;
            --color-yellow-600: #f59e0b;
            
            /* Rojo (Danger) - más brillante */
            --color-red-50: #7f1d1d;
            --color-red-100: #991b1b;
            --color-red-500: #f87171;
            --color-red-600: #ef4444;
            --color-red-700: #dc2626;
            
            /* Naranja - más brillante */
            --color-orange-50: #7c2d12;
            --color-orange-500: #fb923c;
            --color-orange-600: #f97316;
            
            /* Morado - más brillante */
            --color-purple-50: #581c87;
            --color-purple-500: #c084fc;
            --color-purple-600: #a855f7;
        }

        /* Estilos personalizados para scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: var(--color-border-secondary);
            border-radius: 3px;
            transition: background-color 0.2s;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: var(--color-text-muted);
        }

        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: var(--color-border-secondary) transparent;
        }
        
        /* Transiciones suaves */
        * {
            transition: background-color 0.15s ease, 
                        border-color 0.15s ease, 
                        color 0.15s ease,
                        box-shadow 0.15s ease;
        }
        
        button, a, input, select, textarea {
            transition: all 0.2s ease;
        }
    </style>
    @stack('styles')
</head>

<body x-data="{
    darkMode: $persist(localStorage.getItem('darkMode') === 'true' ||
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)),
    sidebarOpen: false
}" x-init="$watch('darkMode', value => document.documentElement.classList.toggle('dark', value))" 
    class="min-h-screen antialiased"
    style="background-color: var(--color-bg-app); color: var(--color-text-primary);"
    :class="{ 'dark': darkMode }" x-cloak>

    @if (Auth::check())
        <!-- Overlay para móviles -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 sm:hidden" x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false">
        </div>
        
        <!-- Botón flotante para abrir sidebar en móviles -->
        <button @click="sidebarOpen = true" 
            class="fixed top-4 left-4 z-30 sm:hidden bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg shadow-lg transition-colors"
            x-show="!sidebarOpen">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    @endif

    @if (Auth::check())
        <livewire:layout.admin.includes.sidebar />
    @endif

    <!-- Main Content -->
    @if (Auth::check())
        <div class="sm:ml-64 min-h-screen" style="background-color: var(--color-bg-app);">
            <div style="color: var(--color-text-primary);">
                <div class="flex justify-between items-center px-4 pt-6">

                    @isset($breadcrumbs)
                        {{ $breadcrumbs }}
                    @endisset

                    @isset($action)
                        {{ $action }}
                    @endisset
                </div>

                @if (isset($header))
                    <header style="background-color: var(--color-bg-primary); border-bottom: 1px solid var(--color-border-primary); box-shadow: var(--shadow-sm);">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="mt-4 p-4">
                    {{ $slot }}
                </main>
            </div>
        </div>
    @else
        <main class="bg-gray-50 rounded-lg dark:border-gray-700 dark:bg-gray-900">
            
            {{ $slot }}
        </main>
    @endif


    @livewireScripts
    @wireUiScripts
    @stack('scripts')
    @if (session('swal'))
        <script type="module">
            Swal.fire({!! json_encode(session('swal')) !!});
        </script>
    @endif

    <script>
        document.addEventListener('livewire:navigated', () => {
            initFlowbite();
        });

        Livewire.on('swal', data => {
            Swal.fire(data[0]);
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showAlert', (data) => {
                Swal.fire({
                    icon: data[0].icon,
                    title: data[0].title,
                    text: data[0].text,
                    showConfirmButton: true,
                    timer: 3000
                });
            });
        });
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            /* Echo.channel('notification')
                .listen('NotificationSend', (e) => {
                    console.log('se ha generado una notificación');
                }); */

        });
    </script>

</body>

</html>
