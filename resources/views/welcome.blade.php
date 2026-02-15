@php
    use App\Models\Setting;

    // Textos estáticos para que el home quede EXACTAMENTE como en tu diseño
    $heroName = 'Alcaldía Bolivariana del Municipio Escuque';
    $heroSubtitle = 'ESCUQUE';
    $heroDescription = 'Gobierno comprometido con el desarrollo y bienestar de nuestra comunidad.';
    $heroLogo = asset('logo-alcaldia-escuque.png');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description"
        content="{{ $heroName }} - {{ $heroDescription }}">
    <title>{{ $heroName }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --escuque-red: #b91c1c;
            --escuque-gold: #d97706;
        }
        .bg-escuque-red { background-color: var(--escuque-red); }
        .bg-escuque-gold { background-color: var(--escuque-gold); }
        .hover\:bg-escuque-red:hover { background-color: var(--escuque-red); }
        .hover\:bg-escuque-gold:hover { background-color: var(--escuque-gold); }
        .text-escuque-red { color: var(--escuque-red); }
        .text-escuque-gold { color: var(--escuque-gold); }
        .border-escuque-red { border-color: var(--escuque-red); }
        .border-escuque-gold { border-color: var(--escuque-gold); }
        .focus\:ring-escuque-red:focus { --tw-ring-color: var(--escuque-red); }
        .hover\:border-escuque-red:hover { border-color: var(--escuque-red); }
        .hover\:border-escuque-gold:hover { border-color: var(--escuque-gold); }
        /* Transiciones fluidas globales */
        .transition-smooth { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); }
    </style>
</head>

<body class="bg-white font-sans antialiased overflow-x-hidden">

    {{-- Barra colores Venezuela --}}
    <div class="w-full h-2 bg-gradient-to-r from-[#FFCC00] via-[#00247D] to-[#CF142B]"></div>

    {{-- Navegación --}}
    <nav class="fixed w-full z-50 top-0 sm:top-2 bg-white shadow-xl border-b-4 border-escuque-red">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20 lg:h-24">
                <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                    <img src="{{ $heroLogo }}" alt="{{ $heroName }}" class="h-12 sm:h-16 lg:h-20 w-auto object-contain flex-shrink-0">
                    <div class="hidden lg:block">
                        <h1 class="text-escuque-red font-extrabold text-sm xl:text-base leading-tight">ALCALDÍA BOLIVARIANA</h1>
                        <p class="text-gray-700 font-bold text-base xl:text-lg">Municipio Escuque</p>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-2">
                    <a href="#inicio" class="nav-link px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red transition-smooth inline-flex items-center gap-2"><x-icons name="home" class="w-5 h-5" />Inicio</a>
                    <a href="#servicios" class="nav-link px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red transition-smooth inline-flex items-center gap-2"><x-icons name="briefcase" class="w-5 h-5" />Servicios</a>
                    <a href="#formulario" class="nav-link px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red transition-smooth inline-flex items-center gap-2"><x-icons name="document" class="w-5 h-5" />Solicitud</a>
                    <a href="#contacto" class="nav-link px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red transition-smooth inline-flex items-center gap-2"><x-icons name="envelope" class="w-5 h-5" />Contacto</a>
                </div>

                <div class="flex items-center gap-3">
                    @if (Auth::check())
                        @if (!Auth::user()->hasRole(['Beneficiario']))
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red transition-smooth">
                                <x-icons name="chart" class="w-5 h-5" />Dashboard
                            </a>
                        @endif
                        @if (Auth::user()->hasRole(['Beneficiario']))
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red transition-smooth">
                                <x-icons name="chart" class="w-5 h-5" />Panel
                            </a>
                        @endif
                        <div class="relative">
                            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar" class="flex items-center gap-2 px-6 py-3 rounded-lg font-bold text-white bg-escuque-red hover:bg-red-800 transition-smooth shadow-lg">
                                <x-icons name="user" class="w-5 h-5" />{{ Auth::user()->name }} {{ Auth::user()->last_name }} <x-icons name="chevron-down" class="w-4 h-4 ml-2" />
                            </button>
                            <div id="dropdownNavbar" class="z-10 hidden absolute right-0 mt-1 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 border border-gray-200">
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownNavbarLink">
                                    <li>
                                        @if (!Auth::user()->hasRole(['Beneficiario']))
                                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-t-lg"><x-icons name="gauge" class="w-4 h-4" />Dashboard</a>
                                        @endif
                                        @if (Auth::user()->hasRole(['Beneficiario']))
                                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-t-lg"><x-icons name="gauge" class="w-4 h-4" />Panel</a>
                                        @endif
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.settings.profile') }}" class="block px-4 py-2 hover:bg-gray-100"><x-icons name="user" class="w-4 h-4" />Perfil</a>
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full block px-4 py-2 text-left hover:bg-gray-100 text-gray-700 cursor-pointer rounded-b-lg">
                                            <x-icons name="logout" class="w-4 h-4" />Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:flex items-center gap-2 px-6 py-3 rounded-lg font-bold text-white bg-escuque-red hover:bg-red-800 transition-smooth shadow-lg">
                            <x-icons name="logout" class="w-5 h-5" />Ingresar
                        </a>
                    @endif
                    <button type="button" class="mobile-menu-button lg:hidden inline-flex items-center justify-center p-3 rounded-lg text-gray-700 hover:text-white hover:bg-escuque-red transition-smooth" aria-label="Abrir menú">
                        <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mobile-menu hidden lg:hidden border-t-2 border-gray-200 bg-white">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="#inicio" class="nav-link-mobile flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red"><x-icons name="home" class="w-5 h-5" />Inicio</a>
                <a href="#servicios" class="nav-link-mobile flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red"><x-icons name="briefcase" class="w-5 h-5" />Servicios</a>
                <a href="#formulario" class="nav-link-mobile flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red"><x-icons name="document" class="w-5 h-5" />Solicitud</a>
                <a href="#contacto" class="nav-link-mobile flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-lg font-semibold text-gray-700 hover:text-white hover:bg-escuque-red"><x-icons name="envelope" class="w-5 h-5" />Contacto</a>
                <div class="pt-2 border-t-2 border-gray-200">
                    @if (Auth::check())
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg font-bold text-white bg-escuque-red hover:bg-red-800 text-center">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg font-bold text-white bg-escuque-red hover:bg-red-800 text-center"><x-icons name="logout" class="w-5 h-5" />Ingresar</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section id="inicio" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-24 sm:pt-28">
        <div class="absolute inset-0">
            <img src="{{ asset('fondo.png') }}" alt="Alcaldía de Escuque" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-8 flex justify-center" data-aos="fade-down">
                <img src="{{ $heroLogo }}" alt="{{ $heroName }}" class="h-32 sm:h-40 md:h-48 w-auto" style="filter: drop-shadow(0 0 12px rgba(255,255,255,0.8)) drop-shadow(0 0 25px rgba(255,255,255,0.5));">
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold mb-4 sm:mb-6 leading-tight text-white drop-shadow-2xl px-2" data-aos="fade-up" data-aos-delay="100">
                {{ $heroName }}<br><span class="block text-escuque-gold mt-1 sm:mt-2">{{ $heroSubtitle }}</span>
            </h1>
            <p class="text-base sm:text-xl md:text-2xl text-white mb-8 sm:mb-12 max-w-3xl mx-auto font-semibold drop-shadow-lg px-2" data-aos="fade-up" data-aos-delay="200">
                {{ $heroDescription }}
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-escuque-red hover:bg-red-800 text-white rounded-lg font-bold text-base sm:text-lg shadow-2xl transition-smooth hover:scale-105 w-full sm:w-auto">
                    <x-icons name="logout" class="w-5 h-5" /><span>Acceder al Sistema</span>
                </a>
                <a href="#formulario" class="inline-flex items-center justify-center gap-2 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-white/90 hover:bg-white text-escuque-red rounded-lg font-bold text-base sm:text-lg border-2 border-white shadow-xl transition-smooth hover:scale-105 w-full sm:w-auto">
                    <span>Solicitar Servicio</span><x-icons name="arrow-right" class="w-5 h-5" />
                </a>
                <a href="#servicios" class="inline-flex items-center justify-center gap-2 sm:gap-3 px-6 sm:px-8 py-3 sm:py-4 bg-white/90 hover:bg-white text-escuque-red rounded-lg font-bold text-base sm:text-lg border-2 border-white shadow-xl transition-smooth hover:scale-105 w-full sm:w-auto">
                    <span>Nuestros Servicios</span><x-icons name="chevron-down" class="w-5 h-5" />
                </a>
            </div>
            <div class="mt-12 sm:mt-20 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 max-w-4xl mx-auto px-2" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-white/95 backdrop-blur-sm p-4 sm:p-6 rounded-xl shadow-2xl hover-lift">
                    <div class="text-3xl sm:text-4xl font-bold text-escuque-red mb-1 sm:mb-2">1X10</div>
                    <div class="text-sm sm:text-base text-gray-700 font-semibold">Control de Beneficios</div>
                </div>
                <div class="bg-white/95 backdrop-blur-sm p-4 sm:p-6 rounded-xl shadow-2xl hover-lift">
                    <div class="text-3xl sm:text-4xl font-bold text-escuque-gold mb-1 sm:mb-2">100%</div>
                    <div class="text-sm sm:text-base text-gray-700 font-semibold">Reportes y Estadísticas</div>
                </div>
                <div class="bg-white/95 backdrop-blur-sm p-4 sm:p-6 rounded-xl shadow-2xl hover-lift">
                    <div class="text-3xl sm:text-4xl font-bold text-green-600 mb-1 sm:mb-2">24/7</div>
                    <div class="text-sm sm:text-base text-gray-700 font-semibold">Gestión en Tiempo Real</div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <a href="#accesos" class="block text-white hover:text-escuque-gold"><x-icons name="chevron-down" class="w-8 h-8" /></a>
        </div>
    </section>

    {{-- Franja de estadísticas (estilo Plenaria) --}}
    @php
        try {
            $countBeneficios = \App\Models\Beneficiary::count();
            $countReportes = \App\Models\Report::count();
            $countSolicitudes = \App\Models\Solicitud::count();
        } catch (\Throwable $e) {
            $countBeneficios = $countReportes = $countSolicitudes = 0;
        }
    @endphp
    <section class="relative z-10 py-6 sm:py-8 bg-white border-b border-gray-200 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6 text-center">
                <a href="#formulario" class="group p-4 rounded-xl hover:bg-gray-50 transition-smooth">
                    <div class="text-2xl sm:text-3xl font-bold text-escuque-red group-hover:scale-105 transition-transform">{{ $countSolicitudes }}</div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Solicitudes</div>
                </a>
                <div class="p-4 rounded-xl">
                    <div class="text-2xl sm:text-3xl font-bold text-escuque-gold">{{ $countBeneficios }}</div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Beneficiarios</div>
                </div>
                <div class="p-4 rounded-xl col-span-2 sm:col-span-1">
                    <div class="text-2xl sm:text-3xl font-bold text-green-600">{{ $countReportes }}</div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Reportes</div>
                </div>
                <div class="hidden lg:block p-4 rounded-xl">
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">1X10</div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Control</div>
                </div>
                <div class="hidden lg:block p-4 rounded-xl">
                    <div class="text-2xl sm:text-3xl font-bold text-gray-700">24/7</div>
                    <div class="text-xs sm:text-sm font-semibold text-gray-600 mt-1">Gestión</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Accesos Rápidos (estilo Plenaria: 3 tarjetas) --}}
    <section id="accesos" class="py-12 sm:py-16 lg:py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-14">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-800 mb-3">Accesos Rápidos</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Acceda a la información del sistema, servicios municipales y canales de atención ciudadana</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-lg hover-lift hover:border-escuque-red/50">
                    <div class="w-14 h-14 rounded-xl bg-escuque-red/10 flex items-center justify-center mb-5">
                        <x-icons name="chart" class="w-7 h-7 text-escuque-red" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Sistema 1x10</h3>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">Control de beneficios, estadísticas y reportes del Municipio Escuque en tiempo real.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-escuque-red font-semibold hover:underline">
                        Acceder al sistema <x-icons name="arrow-right" class="w-4 h-4" />
                    </a>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-lg hover-lift hover:border-escuque-gold/50">
                    <div class="w-14 h-14 rounded-xl bg-escuque-gold/10 flex items-center justify-center mb-5">
                        <x-icons name="briefcase" class="w-7 h-7 text-escuque-gold" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Servicios Municipales</h3>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">Ayudas sociales, educación, salud, empleo y participación ciudadana.</p>
                    <a href="#servicios" class="inline-flex items-center gap-2 text-escuque-gold font-semibold hover:underline">
                        Ver servicios <x-icons name="arrow-right" class="w-4 h-4" />
                    </a>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-lg hover-lift hover:border-green-600/50">
                    <div class="w-14 h-14 rounded-xl bg-green-600/10 flex items-center justify-center mb-5">
                        <x-icons name="document" class="w-7 h-7 text-green-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Solicitar Atención</h3>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">Completa el formulario y un funcionario se pondrá en contacto contigo.</p>
                    <a href="#formulario" class="inline-flex items-center gap-2 text-green-600 font-semibold hover:underline">
                        Ir al formulario <x-icons name="arrow-right" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Misión y Visión --}}
    <section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl border-t-4 border-escuque-red hover-lift" data-aos="fade-right">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-800 mb-4 sm:mb-6">
                        <span class="text-escuque-red">Propósito</span> del Sistema
                    </h2>
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed mb-6">
                        Centralizar la gestión estadística, el control de beneficios y los reportes de la Alcaldía de Escuque,
                        permitiendo un seguimiento transparente y eficiente de los programas municipales.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] bg-escuque-red rounded-lg flex items-center justify-center flex-shrink-0 text-white"><x-icons name="chart" class="w-5 h-5" /></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Estadísticas</h4>
                                <p class="text-gray-600">Datos y reportes en tiempo real</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] bg-escuque-gold rounded-lg flex items-center justify-center flex-shrink-0 text-white"><svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 017.58 5.5h8.84a3.75 3.75 0 013.08-1.4l-1.3-1.298a1.875 1.875 0 00-1.325-.55H5.223z" clip-rule="evenodd"/><path d="M3.75 8.25v10.5a3 3 0 003 3h10.5a3 3 0 003-3V8.25H3.75z"/></svg></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Control de Inventario</h4>
                                <p class="text-gray-600">Gestión integral de almacenes y productos</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 min-w-[2.5rem] min-h-[2.5rem] bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0 text-white"><x-icons name="users" class="w-5 h-5" /></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Beneficiarios</h4>
                                <p class="text-gray-600">Registro y seguimiento de atenciones</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl border-t-4 border-escuque-gold hover-lift" data-aos="fade-left" data-aos-delay="100">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-800 mb-4 sm:mb-6">
                        <span class="text-escuque-gold">Plataforma</span> Integral
                    </h2>
                    <p class="text-gray-700 text-base sm:text-lg leading-relaxed mb-6">
                        Sistema web unificado para la toma de decisiones basada en datos, con reportes, estadísticas y control total de los beneficios del Municipio Escuque.
                    </p>
                    <div class="bg-gradient-to-r from-escuque-red to-escuque-gold text-white rounded-xl p-6">
                        <h4 class="font-bold text-xl mb-3">Valores Institucionales</h4>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2"><x-icons name="star" class="w-5 h-5 text-yellow-300" /><span>Honestidad</span></li>
                            <li class="flex items-center gap-2"><x-icons name="star" class="w-5 h-5 text-yellow-300" /><span>Compromiso Social</span></li>
                            <li class="flex items-center gap-2"><x-icons name="star" class="w-5 h-5 text-yellow-300" /><span>Eficiencia</span></li>
                            <li class="flex items-center gap-2"><x-icons name="star" class="w-5 h-5 text-yellow-300" /><span>Respeto</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Servicios --}}
    <section id="servicios" class="py-12 sm:py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-16" data-aos="fade-up">
                <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold mb-4 sm:mb-6 text-gray-800"><span class="text-escuque-red">Servicios</span> Municipales</h2>
                <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto px-2">Módulos del sistema para gestión, control y estadísticas de los programas municipales</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-white rounded-2xl p-6 sm:p-8 border-2 border-gray-200 hover:border-escuque-red shadow-lg hover-lift" data-aos="fade-up" data-aos-delay="50">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 min-w-[3.5rem] min-h-[3.5rem] bg-escuque-red rounded-xl flex items-center justify-center mb-4 sm:mb-6 flex-shrink-0 text-white"><svg class="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg></div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Ayudas Sociales</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Programas de asistencia directa para familias en situación de vulnerabilidad</p>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 border-2 border-gray-200 hover:border-blue-600 shadow-lg hover-lift" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 min-w-[3.5rem] min-h-[3.5rem] bg-blue-600 rounded-xl flex items-center justify-center mb-4 sm:mb-6 flex-shrink-0 text-white"><svg class="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z"/></svg></div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Educación</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Becas y programas de apoyo educativo para estudiantes</p>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 border-2 border-gray-200 hover:border-green-600 shadow-lg hover-lift" data-aos="fade-up" data-aos-delay="150">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 min-w-[3.5rem] min-h-[3.5rem] bg-green-600 rounded-xl flex items-center justify-center mb-4 sm:mb-6 flex-shrink-0 text-white"><svg class="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg></div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Salud</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Acceso a servicios médicos y programas de prevención</p>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 border-2 border-gray-200 hover:border-escuque-gold shadow-lg hover-lift" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 min-w-[3.5rem] min-h-[3.5rem] bg-escuque-gold rounded-xl flex items-center justify-center mb-4 sm:mb-6 flex-shrink-0 text-white"><svg class="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0112 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 017.5 5.455V5.25zm7.5 0v.09a49.488 49.488 0 00-6 0v-.09a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5zm-3 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"/><path d="M3 18.4v-2.796a4.3 4.3 0 00.713.31A26.226 26.226 0 0012 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 01-6.477-.427C4.047 21.128 3 19.852 3 18.4z"/></svg></div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Empleo</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Bolsa de trabajo y capacitación laboral</p>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 border-2 border-gray-200 hover:border-orange-600 shadow-lg hover-lift" data-aos="fade-up" data-aos-delay="250">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 min-w-[3.5rem] min-h-[3.5rem] bg-orange-600 rounded-xl flex items-center justify-center mb-4 sm:mb-6 flex-shrink-0 text-white"><svg class="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"/><path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"/></svg></div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Vivienda</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Apoyo para mejoras habitacionales y vivienda social</p>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 border-2 border-gray-200 hover:border-purple-600 shadow-lg hover-lift" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 min-w-[3.5rem] min-h-[3.5rem] bg-purple-600 rounded-xl flex items-center justify-center mb-4 sm:mb-6 flex-shrink-0 text-white"><svg class="w-6 h-6 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M8.25 4.5a3.75 3.75 0 117.5 0v8.25a3.75 3.75 0 11-7.5 0V4.5z"/><path d="M6 10.5a.75.75 0 01.75.75v1.5a5.25 5.25 0 1010.5 0v-1.5a.75.75 0 011.5 0v1.5a6.751 6.751 0 01-6 6.709v2.291h3a.75.75 0 010 1.5h-7.5a.75.75 0 010-1.5h3v-2.291a6.751 6.751 0 01-6-6.709v-1.5A.75.75 0 016 10.5z"/></svg></div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Participación Ciudadana</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Espacios para tu voz en las decisiones municipales</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Atención Ciudadana / Formulario (estilo Plenaria: Participa activamente) --}}
    <section id="formulario" class="py-12 sm:py-16 lg:py-24 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-16" data-aos="fade-up">
                <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold mb-4 sm:mb-6"><span class="text-escuque-red">Atención Ciudadana</span></h2>
                <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">Participa activamente en la construcción de nuestro municipio. Completa el formulario y un funcionario se pondrá en contacto contigo.</p>
            </div>
            <div class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 lg:p-12 border-t-4 border-escuque-red shadow-2xl" data-aos="fade-up" data-aos-delay="100">
                <livewire:solicitud-form />
            </div>
        </div>
    </section>

    {{-- Contacto (con mapa y datos reales del welcome actual) --}}
    <section id="contacto" class="py-12 sm:py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-16" data-aos="fade-up">
                <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold mb-4 sm:mb-6"><span class="text-escuque-red">Contáctanos</span></h2>
                <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">Estamos aquí para servirte. Visítanos o comunícate con nosotros</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8 sm:gap-12 items-start">
                <div class="space-y-6">
                    <div class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-escuque-red shadow-lg hover-lift" data-aos="fade-right">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] bg-escuque-red rounded-lg flex items-center justify-center flex-shrink-0"><x-icons name="map-pin" class="w-6 h-6 text-white" /></div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Ubicación</h3>
                                <p class="text-gray-600">Calle Páez, Sector La Loma</p>
                                <p class="text-gray-600">Parroquia Escuque, Estado Trujillo, Venezuela</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-green-600 shadow-lg hover-lift" data-aos="fade-right" data-aos-delay="50">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0"><x-icons name="phone" class="w-6 h-6 text-white" /></div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Teléfono</h3>
                                <p class="text-gray-600">0271-2950133</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-escuque-gold shadow-lg hover-lift" data-aos="fade-right" data-aos-delay="100">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] bg-escuque-gold rounded-lg flex items-center justify-center flex-shrink-0"><x-icons name="clock" class="w-6 h-6 text-white" /></div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Horario de Atención</h3>
                                <p class="text-gray-600">{{ Setting::get('horario_atencion') ?? 'Lunes a Viernes: 8:00 AM - 4:00 PM' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-blue-600 shadow-lg hover-lift" data-aos="fade-right" data-aos-delay="150">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 min-w-[3.5rem] min-h-[3.5rem] bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0"><x-icons name="instagram" class="w-6 h-6 text-white" /></div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Redes Sociales</h3>
                                <a href="https://instagram.com/alcaldiadeescuque" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold">@alcaldiadeescuque</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border-2 border-gray-200 shadow-xl" data-aos="fade-left" data-aos-delay="100">
                    @php
                        $lat = '9.295866608435222';
                        $lon = '-70.67296915830971';
                        $bbox_lon1 = $lon - 0.01;
                        $bbox_lon2 = $lon + 0.01;
                        $bbox_lat1 = $lat - 0.005;
                        $bbox_lat2 = $lat + 0.005;
                    @endphp
                    <iframe class="w-full h-[400px] sm:h-[500px] rounded-lg"
                        src="https://www.openstreetmap.org/export/embed.html?bbox={{ $bbox_lon1 }}%2C{{ $bbox_lat1 }}%2C{{ $bbox_lon2 }}%2C{{ $bbox_lat2 }}&amp;layer=mapnik&amp;marker={{ $lat }}%2C{{ $lon }}"
                        style="border: none"></iframe>
                    <div class="mt-4 text-center">
                        <a href="https://www.google.com/maps/search/?api=1&amp;query={{ $lat }},{{ $lon }}" target="_blank"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-escuque-red hover:bg-red-800 text-white rounded-lg font-bold shadow-xl transition-smooth hover:scale-105">
                            <x-icons name="map-pin" class="w-5 h-5 text-white" /><span>Ver en Google Maps</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-12 sm:py-16 border-t-4 border-escuque-red">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Sistema de gestión web estadístico para la Alcaldía de Escuque. Todos los derechos reservados.</p>
                <p class="text-gray-500 text-sm mt-2">Desarrollado por <span class="text-escuque-gold font-semibold">AG 1.0</span></p>
            </div>
        </div>
    </footer>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireScripts
    @wireUiScripts

    <script>
        // Listener para SweetAlert desde el componente Livewire de solicitud
        Livewire.on('swal-welcome', data => {
            Swal.fire(data[0]);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.querySelector('.mobile-menu-button');
            const menu = document.querySelector('.mobile-menu');
            const menuIcons = button?.querySelectorAll('svg');
            if (button && menu && menuIcons?.length) {
                button.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                    menuIcons.forEach(icon => icon.classList.toggle('hidden'));
                });
                menu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        menu.classList.add('hidden');
                        menuIcons[0]?.classList.remove('hidden');
                        menuIcons[1]?.classList.add('hidden');
                    });
                });
                document.addEventListener('click', (e) => {
                    if (!button.contains(e.target) && !menu.contains(e.target)) {
                        menu.classList.add('hidden');
                        menuIcons[0]?.classList.remove('hidden');
                        menuIcons[1]?.classList.add('hidden');
                    }
                });
            }
        });
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>

</html>
