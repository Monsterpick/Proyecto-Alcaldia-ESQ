@php
    use App\Models\Setting;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="NEVORA - Sistema integral de gestión para ópticas, optometristas y oftalmólogos en Venezuela. Software especializado para clínicas oftalmológicas, distribución de lentes y monturas en Caracas.">
    <meta name="keywords"
        content="sistema óptica venezuela, software oftalmológico, gestión optometría, clínica oftalmológica caracas, distribución lentes venezuela, monturas ópticas, software médico venezuela, historia clínica digital, gestión inventario óptica">
    <meta name="author" content="gruponexa.app">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="VE">
    <meta name="geo.placename" content="Caracas">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title"
        content="NEVORA - Sistema de Gestión para Ópticas y Servicios Oftalmológicos en Venezuela">
    <meta property="og:description"
        content="Software especializado para la gestión integral de ópticas, clínicas oftalmológicas y distribución de productos ópticos en Venezuela.">
    <meta property="og:image" content="{{ asset('logo_nexa.png') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="NEVORA - Sistema de Gestión para Ópticas | Venezuela">
    <meta name="twitter:description"
        content="Software especializado para la gestión integral de ópticas y servicios oftalmológicos en Venezuela.">

    <link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png">
    <link rel="manifest" href="favicons/site.webmanifest">

    <title>{{ Setting::get('name') }} - Sistema de Gestión para Ópticas y Servicios Oftalmológicos | Venezuela</title>

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
            font-family: 'Open Sans', sans-serif;
        }

        .font-display {
            font-family: 'Montserrat', sans-serif;
        }

        .bg-gradient-dark {
            background: linear-gradient(135deg, #001122 0%, #003366 50%, #002244 100%);
        }

        .bg-gradient-accent {
            background: linear-gradient(135deg, #2E8B57 0%, #3AA76B 100%);
        }

        .text-nevora-blue {
            color: #003366;
        }

        .text-nevora-green {
            color: #2E8B57;
        }

        .bg-nevora-blue {
            background-color: #003366;
        }

        .bg-nevora-green {
            background-color: #2E8B57;
        }

        .border-nevora-green {
            border-color: #2E8B57;
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
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-nevora-blue shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0" data-aos="fade-right" data-aos-duration="1000">
                        <span class="font-display text-2xl font-bold text-white">{{ Setting::get('name') }}</span>
                        {{-- <span class="text-sm text-gray-300 ml-2">by Nevora</span> --}}
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center">
                    <div class="flex items-baseline space-x-8 mr-8" data-aos="fade-left" data-aos-duration="1000"
                        data-aos-delay="100">
                        <a href="#inicio" class="text-white hover:text-nevora-green transition-colors">
                            <i class="fa-solid fa-house me-2"></i>Inicio
                        </a>
                        <a href="#servicios" class="text-gray-300 hover:text-nevora-green transition-colors">
                            <i class="fa-solid fa-bars-progress me-2"></i>Servicios
                        </a>
                        <a href="#contacto" class="text-gray-300 hover:text-nevora-green transition-colors">
                            <i class="fa-solid fa-phone me-2"></i>Contacto
                        </a>
                    </div>
                    <div class="flex items-center space-x-4" data-aos="fade-left" data-aos-duration="1000"
                        data-aos-delay="200">
                        @if (Auth::check())
                            @if (!Auth::user()->hasRole(['Paciente']))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-gray-300 hover:text-nevora-green transition-colors">
                                    <i class="fa-solid fa-gauge me-2"></i>Dashboard
                                </a>
                            @endif
                            @if (Auth::user()->hasRole(['Paciente']))
                                <a href="{{ route('dashboard') }}"
                                    class="text-gray-300 hover:text-nevora-green transition-colors">
                                    <i class="fa-solid fa-gauge-simple me-2"></i>Panel
                                </a>
                            @endif
                            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                                class="bg-nevora-green text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors font-medium">
                                <i class="fa-solid fa-user me-2"></i>{{ Auth::user()->name }}
                                {{ Auth::user()->last_name }} <i class="fa-solid fa-chevron-down ms-2"></i>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="dropdownNavbar"
                                class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownLargeButton">
                                    <li>
                                        @if (!Auth::user()->hasRole(['Paciente']))
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a>
                                        @endif
                                        @if (Auth::user()->hasRole(['Paciente']))
                                        <a href="{{ route('dashboard') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-gauge-simple me-2"></i>Panel</a>
                                        @endif
                                    </li>
                                    <li>
                                        @if (Auth::user()->hasRole(['Paciente']))
                                        <a href="{{ route('settings.profile') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-user me-2"></i>Perfil</a>
                                        @endif
                                        @if (!Auth::user()->hasRole(['Paciente']))
                                        <a href="{{ route('settings.profile') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-user me-2"></i>Perfil</a>
                                        @endif
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
                                class="bg-nevora-green text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors font-medium">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Iniciar Sesión
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex lg:hidden items-center">
                    <button type="button"
                        class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-white hover:text-nevora-green focus:outline-none"
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
                <div class="px-2 pt-2 pb-3 space-y-1 bg-nevora-blue border-t border-white/10">
                    <a href="#inicio" class="block px-3 py-2 text-white hover:text-nevora-green transition-colors">
                        <i class="fa-solid fa-house me-2"></i>Inicio
                    </a>
                    <a href="#servicios"
                        class="block px-3 py-2 text-gray-300 hover:text-nevora-green transition-colors">
                        <i class="fa-solid fa-bars-progress me-2"></i>Servicios
                    </a>
                    <a href="#contacto"
                        class="block px-3 py-2 text-gray-300 hover:text-nevora-green transition-colors">
                        <i class="fa-solid fa-phone me-2"></i>Contacto
                    </a>
                    <div class="pt-4 pb-2 border-t border-white/10">
                        <a href="{{ route('login') }}"
                            class="block px-3 py-2 mt-2 bg-nevora-green text-white rounded-lg hover:bg-green-600 transition-colors font-medium text-center mx-3">
                            <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="bg-gradient-dark text-white pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="font-display text-5xl md:text-7xl font-bold leading-tight" data-aos="fade-down"
                    data-aos-delay="100">
                    Bienvenido a
                    <span class="text-nevora-green block mt-2">{{ Setting::get('name') }}</span>
                </h1>
                <p class="mt-8 text-xl md:text-2xl text-gray-300 max-w-4xl mx-auto leading-relaxed" data-aos="fade-up"
                    data-aos-delay="200">
                    {{ Setting::get('description') }}
                </p>
                <p class="mt-8 text-xl md:text-2xl text-gray-300 max-w-4xl mx-auto leading-relaxed" data-aos="fade-up"
                    data-aos-delay="200">
                    {{ Setting::get('long_description') }}
                </p>
                <div class="mt-12" data-aos="fade-up" data-aos-delay="300">
                    <a href="#"
                        class="bg-gradient-accent text-white px-10 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transform hover:scale-105 transition-all duration-300 inline-block">
                        <i class="fa-solid fa-calendar-plus me-2"></i> Agendar Cita
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Vision & Values -->
    <section id="servicios" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-display text-4xl font-bold text-nevora-blue">
                    Nuestros Servicios
                </h2>
                <div class="w-24 h-1 bg-nevora-green mx-auto mt-4"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                @php
                    $servicios = Setting::get('servicios');
                @endphp
                @foreach ($servicios as $servicio)
                    <div class="text-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div
                            class="bg-nevora-{{ $servicio['color'] }} p-6 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                            <i class="fa-solid {{ $servicio['icon'] }} text-white text-2xl"></i>
                        </div>
                        <h3 class="font-display font-semibold text-xl text-nevora-blue mb-4">{{ $servicio['title'] }}
                        </h3>
                        <p class="text-gray-600">{{ $servicio['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Us -->
    <section id="contacto" class="py-20 bg-nevora-blue text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-down">
                <h2 class="font-display text-4xl font-bold">Contáctanos en {{ Setting::get('ciudad') }}</h2>
                <p class="mt-4 text-xl text-gray-300">
                    Agenda tu primera cita con nosotros
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <div class="space-y-8" data-aos="fade-right" data-aos-delay="100">
                    <div>
                        <h3 class="text-2xl font-semibold mb-4">Oficina {{ Setting::get('oficina_principal') }}</h3>
                        <p class="text-gray-300"><i class="fa fa-building me-2 text-nevora-green"></i>
                            {{ Setting::get('direccion_fiscal') }}</p>
                        <p class="text-gray-300">{{ Setting::get('ciudad') }}</p>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold mb-4">Contacto Directo</h3>
                        <p class="text-gray-300"><i class="fa-brands fa-whatsapp me-2 text-nevora-green"></i>Teléfono
                            -
                            WhatsApp: {{ Setting::get('telefono_principal') }}</p>
                        <p class="text-gray-300"><i class="fa-solid fa-phone me-2 text-nevora-green"></i>WhatsApp:
                            {{ Setting::get('telefono_secundario') }}
                        </p>
                        <p class="text-gray-300"><i class="fa-solid fa-envelope me-2 text-nevora-green"></i>Email:
                            {{ Setting::get('email_principal') }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold mb-4">Horario de Atención</h3>
                        <p class="text-gray-300"><i
                                class="fa-solid fa-clock me-2 text-nevora-green"></i>{{ Setting::get('horario_atencion') }}
                        </p>
                    </div>
                </div>

                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8" data-aos="fade-left"
                    data-aos-delay="200">
                    @php
                        $lat = Setting::get('latitude', '9.303214851683354');
                        $lon = Setting::get('longitude', '-70.67561745643616');
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
                            class="bg-nevora-green text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors font-medium"
                            title="Ver en Google Maps">
                            <i class="fa-solid fa-map-location-dot"></i>
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
        "name": "NEVORA - Sistemas para Ópticas",
        "description": "Software de gestión integral para ópticas y servicios oftalmológicos en Venezuela",
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
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-down" data-aos-delay="100">
            <div class="text-center">
                <div class="mb-8">
                    <span class="font-display text-3xl font-bold text-white">NEVORA</span>
                    <span class="text-nevora-green ml-2">by NEXA 2.0</span>
                </div>
                <p class="text-gray-400 mb-8">
                    &copy; 2025 NEXA 2.0. Todos los derechos reservados.
                </p>
                <div class="flex justify-center space-x-8 text-sm text-gray-400">
                    <a href="#" class="hover:text-nevora-green transition-colors">Política de Privacidad</a>
                    <a href="#" class="hover:text-nevora-green transition-colors">Términos de Servicio</a>
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

        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetElement = document.querySelector(this.getAttribute('href'));
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>
