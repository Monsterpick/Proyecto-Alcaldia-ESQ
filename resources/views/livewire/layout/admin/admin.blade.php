@props(['title' => config('app.name', 'Laravel')])

@php
    // Cargar datos institucionales desde Settings (con cache para performance)
    $appColors = cache()->remember('app_colors', 60, function () {
        return [
            'primary' => \App\Models\Setting::get('color_primary', '#5C0A1E'),
            'secondary' => \App\Models\Setting::get('color_secondary', '#7A1232'),
            'buttons' => \App\Models\Setting::get('color_buttons', '#5C0A1E'),
        ];
    });

    $appBranding = cache()->remember('app_branding', 60, function () {
        return [
            'name' => \App\Models\Setting::get('name', 'Sistema Web de Gestión'),
            'description' => \App\Models\Setting::get('description', 'Sistema de gestión'),
        ];
    });

    // Función para oscurecer un color hex
    $darken = function($hex, $percent) {
        $hex = ltrim($hex, '#');
        $r = max(0, hexdec(substr($hex, 0, 2)) - (255 * $percent / 100));
        $g = max(0, hexdec(substr($hex, 2, 2)) - (255 * $percent / 100));
        $b = max(0, hexdec(substr($hex, 4, 2)) - (255 * $percent / 100));
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    };

    $primaryDark = $darken($appColors['primary'], 15);
    $primaryDarker = $darken($appColors['primary'], 25);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data: blob:;">

    <title>{{ $title }} | {{ $appBranding['name'] }}</title>

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
        
        /* Transiciones suaves — solo en elementos interactivos, no en * */
        button, a, input, select, textarea,
        .transition-colors {
            transition: background-color 0.15s ease,
                        border-color 0.15s ease,
                        color 0.15s ease,
                        box-shadow 0.15s ease;
        }

        /* ===== SIDEBAR - Color Institucional Dinámico ===== */
        #logo-sidebar {
            background: linear-gradient(180deg, {{ $primaryDark }} 0%, {{ $primaryDarker }} 100%) !important;
            border-right: 1px solid rgba(255,255,255,0.08) !important;
            --color-bg-primary: {{ $primaryDark }};
            --color-bg-secondary: rgba(255,255,255,0.08);
            --color-bg-tertiary: rgba(255,255,255,0.12);
            --color-bg-hover: rgba(255,255,255,0.1);
            --color-bg-active: rgba(255,255,255,0.15);
            --color-text-primary: #ffffff;
            --color-text-secondary: rgba(255,255,255,0.85);
            --color-text-tertiary: rgba(255,255,255,0.6);
            --color-text-muted: rgba(255,255,255,0.4);
            --color-border-primary: rgba(255,255,255,0.1);
            --color-border-secondary: rgba(255,255,255,0.15);
            --color-blue-50: rgba(255,255,255,0.12);
            --color-blue-600: #ffffff;
            --color-red-50: rgba(255,150,150,0.15);
            --color-red-600: #fca5a5;
            --color-red-700: #f87171;
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.3);
        }

        #logo-sidebar .text-gray-400,
        #logo-sidebar .dark\:text-gray-500 {
            color: rgba(255,255,255,0.45) !important;
        }

        #logo-sidebar .bg-blue-600 {
            background-color: rgba(255,255,255,0.2) !important;
        }

        /* Sidebar en modo oscuro - se integra con el tema oscuro global */
        .dark #logo-sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #0b1120 100%) !important;
            border-right-color: #1e293b !important;
            --color-bg-primary: #0f172a;
            --color-bg-secondary: #1e293b;
            --color-bg-tertiary: #334155;
            --color-bg-hover: #1e293b;
            --color-bg-active: #1e3a8a;
            --color-text-primary: #f1f5f9;
            --color-text-secondary: #cbd5e1;
            --color-text-tertiary: #94a3b8;
            --color-text-muted: #64748b;
            --color-border-primary: #334155;
            --color-border-secondary: #475569;
            --color-blue-50: #1e3a8a;
            --color-blue-600: #3b82f6;
            --color-red-50: #7f1d1d;
            --color-red-600: #f87171;
            --color-red-700: #ef4444;
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.6);
        }

        .dark #logo-sidebar .text-gray-400,
        .dark #logo-sidebar .dark\:text-gray-500 {
            color: #64748b !important;
        }

        .dark #logo-sidebar .bg-blue-600 {
            background-color: #1e40af !important;
        }

        /* Scrollbar del sidebar con tema vino (modo claro) */
        #logo-sidebar .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,0.15);
        }
        #logo-sidebar .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255,255,255,0.25);
        }
        #logo-sidebar .scrollbar-thin {
            scrollbar-color: rgba(255,255,255,0.15) transparent;
        }

        /* Scrollbar del sidebar en modo oscuro */
        .dark #logo-sidebar .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #334155;
        }
        .dark #logo-sidebar .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: #475569;
        }
        .dark #logo-sidebar .scrollbar-thin {
            scrollbar-color: #334155 transparent;
        }
    </style>
    @stack('styles')
</head>

<body x-data="{
    darkMode: $persist(localStorage.getItem('darkMode') === 'true' ||
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)),
    sidebarOpen: false
}" x-init="document.documentElement.classList.toggle('dark', darkMode); $watch('darkMode', value => { document.documentElement.classList.toggle('dark', value); localStorage.setItem('darkMode', value); })" 
    class="min-h-screen antialiased"
    style="background-color: var(--color-bg-app); color: var(--color-text-primary);"
    :class="{ 'dark': darkMode }" x-cloak>

    @if (Auth::check())
        <!-- Overlay para móviles -->
        <div class="fixed inset-0 bg-gray-900/50 z-30 sm:hidden" x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false">
        </div>
    @endif

    @if (Auth::check())
        <livewire:layout.admin.includes.navigation />
        <livewire:layout.admin.includes.sidebar />
    @endif

    <!-- Main Content -->
    @if (Auth::check())
        <div class="sm:ml-64 min-h-screen pt-[3.5rem] sm:pt-[3.75rem]" style="background-color: var(--color-bg-app);">
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
    @if (session('show_password_change_alert') && Auth::check() && Auth::user()->hasRole('Director'))
        <script type="module">
            Swal.fire({
                icon: 'info',
                title: 'Cambio de contraseña recomendado',
                html: 'Por seguridad, te recomendamos cambiar tu contraseña en tu primer acceso. Puedes hacerlo desde tu <strong>Perfil</strong>.',
                confirmButtonText: 'Ir a mi perfil',
                confirmButtonColor: '#2563eb',
                showCancelButton: true,
                cancelButtonText: 'Más tarde',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("admin.settings.profile") }}';
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('livewire:navigated', () => {
            initFlowbite();
        });

        Livewire.on('swal', data => {
            Swal.fire(data[0]);
        });

        // ===== Aplicar colores institucionales en tiempo real =====
        Livewire.on('colors-updated', (data) => {
            const colors = data[0];
            const primary = colors.primary;
            const secondary = colors.secondary;

            // Función JS para oscurecer un color hex
            function darkenHex(hex, percent) {
                hex = hex.replace('#', '');
                let r = Math.max(0, parseInt(hex.substring(0, 2), 16) - Math.round(255 * percent / 100));
                let g = Math.max(0, parseInt(hex.substring(2, 4), 16) - Math.round(255 * percent / 100));
                let b = Math.max(0, parseInt(hex.substring(4, 6), 16) - Math.round(255 * percent / 100));
                return '#' + [r, g, b].map(c => c.toString(16).padStart(2, '0')).join('');
            }

            const primaryDark = darkenHex(primary, 15);
            const primaryDarker = darkenHex(primary, 25);
            const isDark = document.documentElement.classList.contains('dark');

            // 1. Actualizar la Navbar
            const navbar = document.querySelector('nav.fixed.top-0');
            if (navbar) {
                if (!isDark) {
                    navbar.style.background = `linear-gradient(to right, ${primary}, ${secondary}, ${primary})`;
                }
                // Guardar los colores en data-attributes para el toggle de dark mode
                navbar.dataset.colorPrimary = primary;
                navbar.dataset.colorSecondary = secondary;
            }

            // 2. Actualizar el Sidebar (solo en modo claro)
            const sidebar = document.getElementById('logo-sidebar');
            if (sidebar && !isDark) {
                sidebar.style.background = `linear-gradient(180deg, ${primaryDark} 0%, ${primaryDarker} 100%)`;
                sidebar.style.setProperty('--color-bg-primary', primaryDark);
            }

            // 3. Actualizar la hoja de estilos dinámica
            let styleEl = document.getElementById('dynamic-institutional-colors');
            if (!styleEl) {
                styleEl = document.createElement('style');
                styleEl.id = 'dynamic-institutional-colors';
                document.head.appendChild(styleEl);
            }
            styleEl.textContent = `
                #logo-sidebar {
                    background: linear-gradient(180deg, ${primaryDark} 0%, ${primaryDarker} 100%) !important;
                    --color-bg-primary: ${primaryDark};
                }
                .dark #logo-sidebar {
                    background: linear-gradient(180deg, #0f172a 0%, #0b1120 100%) !important;
                    --color-bg-primary: #0f172a;
                }
            `;
        });

        // ===== Aplicar branding (nombre/logo) en tiempo real =====
        Livewire.on('branding-updated', (data) => {
            const branding = data[0];

            // 1. Actualizar el nombre en la navbar
            const navTitle = document.querySelector('nav.fixed.top-0 .logo-text');
            if (navTitle && branding.name) {
                navTitle.textContent = branding.name;
            }

            // 2. Actualizar el logo en la navbar
            const navLogo = document.querySelector('nav.fixed.top-0 .logo-img');
            if (navLogo && branding.logo) {
                navLogo.src = branding.logo;
            }

            // 3. Actualizar el título de la página
            if (branding.name) {
                const titleParts = document.title.split(' | ');
                document.title = titleParts[0] + ' | ' + branding.name;
            }
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
