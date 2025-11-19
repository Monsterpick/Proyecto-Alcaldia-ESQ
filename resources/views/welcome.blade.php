@php
    use App\Models\Setting;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Sistema Web de Gestion de la Alcaldia del Municipio Escuque - Sistema integral de gestión y control de beneficios sociales. Software especializado para la administración eficiente de programas de ayuda social en Venezuela.">
    <meta name="keywords"
        content="beneficios sociales venezuela, control beneficios, gestión social, programa ayuda social, sistema beneficiarios, software social venezuela, administración beneficios, escuque, 1x10, ayuda comunitaria">
    <meta name="author" content="ag1.app">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="VE">
    <meta name="geo.placename" content="Caracas">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title"
        content="Sistema Web de Gestion de la Alcaldia del Municipio Escuque - Sistema de Gestión de Beneficios Sociales en Venezuela">
    <meta property="og:description"
        content="Software especializado para la gestión integral de beneficios sociales y programas de ayuda comunitaria en Venezuela.">
    <meta property="og:image" content="{{ asset('logo_ag.png') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Sistema Web de Gestion de la Alcaldia del Municipio Escuque - Sistema de Gestión de Beneficios Sociales | Venezuela">
    <meta name="twitter:description"
        content="Software especializado para la gestión integral de beneficios sociales y programas de ayuda comunitaria en Venezuela.">

    <link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png">
    <link rel="manifest" href="favicons/site.webmanifest">

    <title>{{ Setting::get('name') ?? 'Sistema de Beneficios' }} - Sistema de Gestión de Beneficios Sociales | Venezuela</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles


    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #0f172a;
        }

        .font-display {
            font-family: 'Figtree', sans-serif;
            font-weight: 700;
        }

        /* Paleta Institucional Elegante */
        .bg-primary-dark {
            background-color: #1e293b;
        }

        .bg-primary {
            background-color: #334155;
        }

        .bg-accent {
            background-color: #3b82f6;
        }

        .bg-accent-light {
            background-color: #60a5fa;
        }

        .text-accent {
            color: #3b82f6;
        }

        .text-accent-light {
            color: #60a5fa;
        }

        .border-accent {
            border-color: #3b82f6;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
        }

        .bg-gradient-accent {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }

        .bg-secondary {
            background-color: #10b981;
        }

        .text-secondary {
            color: #10b981;
        }

        /* Estilos específicos para el menú móvil */
        .mobile-menu-button {
            z-index: 50;
        }

        @media (max-width: 1023px) {
            .mobile-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                z-index: 40;
            }
        }

        /* Animaciones suaves */
        * {
            transition: color 0.2s ease, background-color 0.2s ease;
        }

        /* Estilo para enlace activo en navegación */
        .nav-link.active,
        .nav-link-mobile.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5), 0 2px 4px -1px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>

<body class="bg-slate-950">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 shadow-2xl fixed w-full z-50 border-b border-blue-500/30 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Espacio vacío izquierdo -->
                <div class="flex-1"></div>

                <!-- Desktop Menu Centrado -->
                <div class="hidden lg:flex items-center justify-center">
                    <div class="flex items-center space-x-3 bg-slate-800/60 rounded-xl px-4 py-2 shadow-lg backdrop-blur-md border border-blue-500/20" data-aos="fade-down" data-aos-duration="1000"
                        data-aos-delay="100">
                        <a href="#inicio" class="nav-link text-slate-200 hover:text-white hover:bg-blue-600 px-4 py-2 rounded-lg transition-all font-semibold shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-home me-2"></i>Inicio
                        </a>
                        <a href="#servicios" class="nav-link text-slate-200 hover:text-white hover:bg-blue-600 px-4 py-2 rounded-lg transition-all font-semibold shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-briefcase me-2"></i>Servicios
                        </a>
                        <a href="#contacto" class="nav-link text-slate-200 hover:text-white hover:bg-blue-600 px-4 py-2 rounded-lg transition-all font-semibold shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-envelope me-2"></i>Contacto
                        </a>
                    </div>
                </div>

                <!-- Botón derecho -->
                <div class="flex-1 flex justify-end">
                    <div class="flex items-center space-x-4" data-aos="fade-left" data-aos-duration="1000"
                        data-aos-delay="200">
                        @if (Auth::check())
                            @if (!Auth::user()->hasRole(['Beneficiario']))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-slate-200 font-semibold hover:text-white hover:bg-blue-600 px-4 py-2 rounded-lg transition-all shadow-md hover:shadow-lg">
                                    <i class="fa-solid fa-chart-line me-2"></i>Dashboard
                                </a>
                            @endif
                            @if (Auth::user()->hasRole(['Beneficiario']))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-slate-200 font-semibold hover:text-white hover:bg-blue-600 px-4 py-2 rounded-lg transition-all shadow-md hover:shadow-lg">
                                    <i class="fa-solid fa-chart-simple me-2"></i>Panel
                                </a>
                            @endif
                            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-bold shadow-xl hover:shadow-2xl border border-blue-400/30">
                                <i class="fa-solid fa-user me-2"></i>{{ Auth::user()->name }}
                                {{ Auth::user()->last_name }} <i class="fa-solid fa-chevron-down ms-2"></i>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="dropdownNavbar"
                                class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownLargeButton">
                                    <li>
                                        @if (!Auth::user()->hasRole(['Beneficiario']))
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a>
                                        @endif
                                        @if (Auth::user()->hasRole(['Beneficiario']))
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-gauge-simple me-2"></i>Panel</a>
                                        @endif
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.settings.profile') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-user me-2"></i>Perfil</a>
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-bold shadow-xl hover:shadow-2xl border border-blue-400/30">
                                <i class="fa-solid fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex lg:hidden items-center">
                    <button type="button"
                        class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-slate-200 hover:text-blue-400 focus:outline-none"
                        aria-expanded="false">
                        <span class="sr-only">Abrir menú principal</span>
                        <!-- Icon when menu is closed -->
                        <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Icon when menu is open -->
                        <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="mobile-menu hidden lg:hidden">
                <div class="px-4 pt-4 pb-4 space-y-2 bg-slate-800/95 border-t border-blue-500/30 shadow-2xl backdrop-blur-md">
                    <a href="#inicio" class="nav-link-mobile block px-4 py-3 text-slate-200 font-semibold hover:text-white hover:bg-blue-600 rounded-lg transition-all shadow-md">
                        <i class="fa-solid fa-home me-2"></i>Inicio
                    </a>
                    <a href="#servicios"
                        class="nav-link-mobile block px-4 py-3 text-slate-200 font-semibold hover:text-white hover:bg-blue-600 rounded-lg transition-all shadow-md">
                        <i class="fa-solid fa-briefcase me-2"></i>Servicios
                    </a>
                    <a href="#contacto"
                        class="nav-link-mobile block px-4 py-3 text-slate-200 font-semibold hover:text-white hover:bg-blue-600 rounded-lg transition-all shadow-md">
                        <i class="fa-solid fa-envelope me-2"></i>Contacto
                    </a>
                    <div class="pt-4 pb-2 border-t border-blue-500/30">
                        <a href="{{ route('login') }}"
                            class="block px-4 py-3 mt-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-bold text-center shadow-xl border border-blue-400/30">
                            <i class="fa-solid fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="relative bg-slate-950 pt-16 overflow-hidden">
        <!-- Imagen de fondo enfocada -->
        <div class="absolute inset-0" style="background-image: url('{{ asset('fondo.png') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat; background-attachment: fixed; filter: blur(0px);"></div>
        <!-- Overlay oscuro para legibilidad -->
        <div class="absolute inset-0 bg-slate-950/60"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <!-- Logo/Icono -->
                <div class="inline-flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 mb-8 shadow-2xl shadow-blue-500/50 border border-blue-400/50" data-aos="zoom-in" data-aos-duration="800">
                    <i class="fa-solid fa-landmark text-8xl text-white" style="filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.5));"></i>
                </div>

                <h1 class="font-display text-5xl md:text-7xl font-bold leading-tight mb-6" data-aos="fade-down" data-aos-delay="100">
                    <span class="block text-black drop-shadow-lg mb-2" style="text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8);">Sistema Web de Gestión</span>
                    <span class="block text-black drop-shadow-lg mt-2" style="text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8);">Alcaldía del Municipio Escuque</span>
                </h1>

                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-8 max-w-4xl mx-auto shadow-2xl border-2 border-gray-300" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-2xl md:text-3xl text-black leading-relaxed font-bold">
                        {{ Setting::get('description') ?? 'Sistema integral de gestión de beneficios para la comunidad' }}
                    </p>
                    <p class="mt-4 text-xl md:text-2xl text-gray-800" data-aos="fade-up" data-aos-delay="300">
                        {{ Setting::get('long_description') ?? 'Plataforma diseñada para optimizar la distribución y control de beneficios sociales' }}
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-10 py-5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 text-lg border border-blue-400/30">
                        <i class="fa-solid fa-sign-in-alt mr-3 text-xl"></i>
                        Acceder al Sistema
                    </a>
                    <a href="#servicios" class="inline-flex items-center justify-center px-10 py-5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 text-lg border border-slate-600">
                        <i class="fa-solid fa-info-circle mr-3 text-xl"></i>
                        Conocer Más
                    </a>
                </div>

                <!-- Stats -->
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto" data-aos="fade-up" data-aos-delay="500">
                    <div class="bg-slate-800 backdrop-blur-sm rounded-2xl p-8 shadow-2xl shadow-blue-500/30 border-2 border-blue-500/50 hover:border-blue-400 hover:shadow-blue-500/50 transition-all group">
                        <div class="text-7xl font-bold text-blue-400 mb-4 group-hover:scale-110 transition-transform" style="filter: drop-shadow(0 6px 12px rgba(59, 130, 246, 0.7));">
                            <i class="fa-solid fa-hands-helping"></i>
                        </div>
                        <div class="text-6xl font-bold text-black mb-3">1X10</div>
                        <div class="text-2xl text-black font-bold">Programa Social</div>
                    </div>
                    <div class="bg-slate-800 backdrop-blur-sm rounded-2xl p-8 shadow-2xl shadow-emerald-500/30 border-2 border-emerald-500/50 hover:border-emerald-400 hover:shadow-emerald-500/50 transition-all group">
                        <div class="text-7xl font-bold text-emerald-400 mb-4 group-hover:scale-110 transition-transform" style="filter: drop-shadow(0 6px 12px rgba(16, 185, 129, 0.7));">
                            <i class="fa-solid fa-warehouse"></i>
                        </div>
                        <div class="text-6xl font-bold text-black mb-3">100%</div>
                        <div class="text-2xl text-black font-bold">Control de Inventario</div>
                    </div>
                    <div class="bg-slate-800 backdrop-blur-sm rounded-2xl p-8 shadow-2xl shadow-blue-500/30 border-2 border-blue-500/50 hover:border-blue-400 hover:shadow-blue-500/50 transition-all group">
                        <div class="text-7xl font-bold text-blue-400 mb-4 group-hover:scale-110 transition-transform" style="filter: drop-shadow(0 6px 12px rgba(59, 130, 246, 0.7));">
                            <i class="fa-solid fa-users-line"></i>
                        </div>
                        <div class="text-6xl font-bold text-black mb-3">24/7</div>
                        <div class="text-2xl text-black font-bold">Atención Comunitaria</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Vision & Values -->
    <section id="servicios" class="relative py-20 bg-slate-950 overflow-hidden">
        <!-- Imagen de fondo desenfocada (fondo3) - sin repetir -->
        <div class="absolute inset-0" style="background-image: url('{{ asset('fondo3.png') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat; filter: blur(8px); transform: scale(1.1);"></div>
        <!-- Overlay oscuro -->
        <div class="absolute inset-0 bg-slate-950/80"></div>
        <div class="relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-display text-4xl md:text-5xl font-bold text-black mb-6 drop-shadow-lg" style="text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8);">
                    Nuestros Servicios
                </h2>
                <div class="w-32 h-2 bg-gradient-to-r from-blue-600 to-blue-400 mx-auto rounded-full mb-6 shadow-lg shadow-blue-500/50"></div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-8 max-w-3xl mx-auto shadow-2xl border-2 border-gray-300">
                    <p class="text-2xl text-black font-bold">
                        Sistema integral de gestión para el bienestar de la comunidad de Escuque
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $servicios = Setting::get('servicios') ?? [];
                @endphp
                @forelse ($servicios as $servicio)
                    <div class="group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-2xl shadow-blue-500/20 hover:shadow-blue-500/40 transform hover:-translate-y-3 transition-all duration-300 border-2 border-blue-500/40 hover:border-blue-400 h-full">
                            <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-5 rounded-2xl w-24 h-24 mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/50">
                                <i class="fa-solid {{ $servicio['icon'] }} text-white text-4xl" style="filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.7));"></i>
                            </div>
                            <h3 class="font-display font-bold text-3xl text-black mb-4 group-hover:text-blue-600 transition-colors">
                                {{ $servicio['title'] }}
                            </h3>
                            <p class="text-gray-800 leading-relaxed text-xl font-semibold">{{ $servicio['description'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-12 shadow-xl">
                            <i class="fas fa-cogs text-gray-400 text-6xl mb-4"></i>
                            <p class="text-gray-600 text-xl font-semibold">No hay servicios configurados</p>
                            <p class="text-gray-500 mt-2">Configura los servicios desde el panel de administración</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        </div>
    </section>

    <!-- Contact Us -->
    <section id="contacto" class="relative py-20 bg-slate-950 text-white overflow-hidden">
        <!-- Imagen de fondo desenfocada (fondo2) - sin repetir -->
        <div class="absolute inset-0" style="background-image: url('{{ asset('fondo2.png') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat; filter: blur(8px); transform: scale(1.1);"></div>
        <!-- Overlay oscuro -->
        <div class="absolute inset-0 bg-slate-950/80"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-down">
                <div class="inline-flex items-center justify-center bg-slate-800 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-2xl shadow-blue-500/40 border-2 border-blue-500/50">
                    <i class="fa-solid fa-landmark text-6xl text-blue-400" style="filter: drop-shadow(0 4px 8px rgba(59, 130, 246, 0.6));"></i>
                </div>
                <h2 class="font-display text-4xl md:text-5xl font-bold mb-6 text-black drop-shadow-lg" style="text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8);">Alcaldía de Escuque</h2>
                <div class="w-32 h-2 bg-gradient-to-r from-blue-600 to-blue-400 mx-auto rounded-full mb-6 shadow-lg shadow-blue-500/50"></div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl p-8 max-w-3xl mx-auto shadow-2xl border-2 border-gray-300">
                    <p class="text-2xl text-black font-bold leading-relaxed">
                        Al servicio de la comunidad, gestionando beneficios sociales para el bienestar de todos
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <div class="space-y-6" data-aos="fade-right" data-aos-delay="100">
                    <!-- Ubicación -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-2xl shadow-blue-500/30 hover:shadow-blue-500/50 transition-all border-2 border-blue-500/50 hover:border-blue-400">
                        <div class="flex items-start">
                            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-4 mr-4 shadow-lg shadow-blue-500/50">
                                <i class="fa-solid fa-map-marker-alt text-3xl text-white" style="filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.7));"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-3 text-black">Ubicación</h3>
                                <p class="text-gray-800 font-bold text-lg">Calle Páez, Sector La Loma</p>
                                <p class="text-gray-800 font-bold text-lg">Parroquia Escuque, Estado Trujillo</p>
                                <p class="text-gray-800 font-bold text-lg">Venezuela</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-2xl shadow-emerald-500/30 hover:shadow-emerald-500/50 transition-all border-2 border-emerald-500/50 hover:border-emerald-400">
                        <div class="flex items-start">
                            <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-xl p-4 mr-4 shadow-lg shadow-emerald-500/50">
                                <i class="fa-solid fa-phone text-3xl text-white" style="filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.7));"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-3 text-black">Teléfono</h3>
                                <p class="text-gray-800 text-xl font-bold">0271-2950133</p>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-2xl shadow-blue-500/30 hover:shadow-blue-500/50 transition-all border-2 border-blue-500/50 hover:border-blue-400">
                        <div class="flex items-start">
                            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-4 mr-4 shadow-lg shadow-blue-500/50">
                                <i class="fa-brands fa-instagram text-3xl text-white" style="filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.7));"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-3 text-black">Redes Sociales</h3>
                                <a href="https://instagram.com/alcaldiadeescuque" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors text-xl font-bold">
                                    @alcaldiadeescuque
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Horario -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-2xl shadow-emerald-500/30 hover:shadow-emerald-500/50 transition-all border-2 border-emerald-500/50 hover:border-emerald-400">
                        <div class="flex items-start">
                            <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-xl p-4 mr-4 shadow-lg shadow-emerald-500/50">
                                <i class="fa-solid fa-clock text-3xl text-white" style="filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.7));"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-3 text-black">Horario de Atención</h3>
                                <p class="text-gray-800 font-bold text-lg">{{ Setting::get('horario_atencion') ?? 'Lunes a Viernes: 8:00 AM - 4:00 PM' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur-lg rounded-2xl p-8 border-2 border-blue-500/50 shadow-2xl shadow-blue-500/30" data-aos="fade-left"
                    data-aos-delay="200">
                    @php
                        // Coordenadas de la Alcaldía de Escuque, Trujillo
                        $lat = '9.295866608435222';
                        $lon = '-70.67296915830971';
                        $zoom = 17;
                        $bbox_lon1 = $lon - 0.01;
                        $bbox_lon2 = $lon + 0.01;
                        $bbox_lat1 = $lat - 0.005;
                        $bbox_lat2 = $lat + 0.005;
                    @endphp
                    <iframe class="w-full h-96 rounded-2xl"
                        src="https://www.openstreetmap.org/export/embed.html?bbox={{ $bbox_lon1 }}%2C{{ $bbox_lat1 }}%2C{{ $bbox_lon2 }}%2C{{ $bbox_lat2 }}&amp;layer=mapnik&amp;marker={{ $lat }}%2C{{ $lon }}"
                        style="border: 1px solid black">
                    </iframe>
                    <br />
                    <small class="flex justify-center">
                        <a href="https://www.google.com/maps/search/?api=1&amp;query={{ $lat }},{{ $lon }}"
                            target="_blank"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg transition-all font-bold shadow-xl border border-blue-400/30"
                            title="Ver en Google Maps">
                            <i class="fa-solid fa-map-location-dot mr-2"></i>
                            Ver en Google Maps
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </section>

    <!-- Microdata for Local Business -->
    {{--
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareCompany",
        "name": "Sistema Web de Gestion de la Alcaldia del Municipio Escuque",
        "description": "Software de gestión integral para beneficios sociales y programas de ayuda comunitaria en Venezuela",
        "url": "https://nevora.app",
        "logo": "https://nevora.app/images/logo.png",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Av. Francisco de Miranda, Edificio Centro Empresarial",
            "addressLocality": "Chacao",
            "addressRegion": "Caracas",
            "postalCode": "1060",
            "addressCountry": "VE"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "10.4916",
            "longitude": "-66.8559"
        },
        "openingHours": "Mo-Fr 08:00-17:00",
        "telephone": "+582125550123",
        "sameAs": [
            "https://facebook.com/nevora",
            "https://instagram.com/nevora",
            "https://linkedin.com/company/nevora"
        ]
    }
    </script> --}}

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-slate-950 to-slate-900 text-white py-16 border-t border-blue-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up" data-aos-delay="100">
            <div class="text-center">
                <div class="mb-8">
                    <span class="font-display text-3xl font-bold text-white">{{ Setting::get('name') ?? 'Sistema de Beneficios' }}</span>
                    <span class="text-blue-400 ml-3 text-lg font-semibold">by AG 1.0</span>
                </div>
                <p class="text-slate-300 mb-8 text-lg">
                    &copy; 2025 AG 1.0. Todos los derechos reservados.
                </p>
                <div class="flex justify-center space-x-8 text-base text-slate-300">
                    <a href="#" class="hover:text-blue-400 transition-colors font-medium">Política de Privacidad</a>
                    <a href="#" class="hover:text-blue-400 transition-colors font-medium">Términos de Servicio</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @livewireScripts
    @wireUiScripts
    <script>
        // Inicializar AOS (Animate On Scroll)


        // Mobile Menu Toggle
        document.addEventListener('livewire:navigated', function() {
            const button = document.querySelector('.mobile-menu-button');
            const menu = document.querySelector('.mobile-menu');
            const menuIcons = button.querySelectorAll('svg');

            if (button && menu && menuIcons.length) {
                button.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                    menuIcons.forEach(icon => icon.classList.toggle('hidden'));
                    button.setAttribute('aria-expanded',
                        button.getAttribute('aria-expanded') === 'false' ? 'true' : 'false'
                    );
                });

                // Cerrar menú al hacer clic en un enlace
                menu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        menu.classList.add('hidden');
                        menuIcons[0].classList.remove('hidden');
                        menuIcons[1].classList.add('hidden');
                        button.setAttribute('aria-expanded', 'false');
                    });
                });
            }
        });

        // Smooth scroll behavior y activación de enlaces
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetElement = document.querySelector(this.getAttribute('href'));
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                    
                    // Marcar como activo (desktop y mobile)
                    document.querySelectorAll('.nav-link, .nav-link-mobile').forEach(link => {
                        link.classList.remove('active');
                    });
                    if (this.classList.contains('nav-link') || this.classList.contains('nav-link-mobile')) {
                        this.classList.add('active');
                        
                        // Sincronizar con el otro menú
                        const href = this.getAttribute('href');
                        document.querySelectorAll(`a[href="${href}"]`).forEach(link => {
                            if (link.classList.contains('nav-link') || link.classList.contains('nav-link-mobile')) {
                                link.classList.add('active');
                            }
                        });
                    }
                }
            });
        });

        // Detectar sección visible al hacer scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const scrollPosition = window.scrollY + 100;

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');

                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    document.querySelectorAll('.nav-link, .nav-link-mobile').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + sectionId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });

        // Marcar inicio como activo al cargar
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href="#inicio"]').forEach(link => {
                if (link.classList.contains('nav-link') || link.classList.contains('nav-link-mobile')) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>
