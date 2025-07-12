<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="NEVORA - Sistema integral de gestión para ópticas, optometristas y oftalmólogos en Venezuela. Software especializado para clínicas oftalmológicas, distribución de lentes y monturas en Caracas.">
    <meta name="keywords" content="sistema óptica venezuela, software oftalmológico, gestión optometría, clínica oftalmológica caracas, distribución lentes venezuela, monturas ópticas, software médico venezuela, historia clínica digital, gestión inventario óptica">
    <meta name="author" content="NEXA 2.0">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="VE">
    <meta name="geo.placename" content="Caracas">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="NEVORA - Sistema de Gestión para Ópticas y Servicios Oftalmológicos en Venezuela">
    <meta property="og:description" content="Software especializado para la gestión integral de ópticas, clínicas oftalmológicas y distribución de productos ópticos en Venezuela.">
    <meta property="og:image" content="{{ asset('images/nevora-preview.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="NEVORA - Sistema de Gestión para Ópticas | Venezuela">
    <meta name="twitter:description" content="Software especializado para la gestión integral de ópticas y servicios oftalmológicos en Venezuela.">

    <title>NEVORA - Sistema de Gestión para Ópticas y Servicios Oftalmológicos | Venezuela</title>

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
                        <span class="font-display text-2xl font-bold text-white">NEVORA</span>
                        <span class="text-sm text-gray-300 ml-2">by NEXA 2.0</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center">
                    <div class="flex items-baseline space-x-8 mr-8" data-aos="fade-left" data-aos-duration="1000"
                        data-aos-delay="100">
                        <a href="#inicio" class="text-white hover:text-nevora-green transition-colors">Inicio</a>
                        <a href="#about" class="text-gray-300 hover:text-nevora-green transition-colors">Acerca de</a>
                        <a href="#servicios"
                            class="text-gray-300 hover:text-nevora-green transition-colors">Servicios</a>
                        <a href="#innovaciones"
                            class="text-gray-300 hover:text-nevora-green transition-colors">Innovaciones</a>
                        <a href="#contacto" class="text-gray-300 hover:text-nevora-green transition-colors">Contacto</a>
                    </div>
                    <div class="flex items-center space-x-4" data-aos="fade-left" data-aos-duration="1000"
                        data-aos-delay="200">
                        <a href="{{ route('login') }}" class="text-white hover:text-nevora-green transition-colors font-medium">
                            Iniciar Sesión
                        </a>
                        <a href="#"
                            class="bg-nevora-green text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors font-medium">
                            Comenzar Gratis
                        </a>
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
                    <a href="#inicio"
                        class="block px-3 py-2 text-white hover:text-nevora-green transition-colors">Inicio</a>
                    <a href="#about"
                        class="block px-3 py-2 text-gray-300 hover:text-nevora-green transition-colors">Acerca de</a>
                    <a href="#servicios"
                        class="block px-3 py-2 text-gray-300 hover:text-nevora-green transition-colors">Servicios</a>
                    <a href="#innovaciones"
                        class="block px-3 py-2 text-gray-300 hover:text-nevora-green transition-colors">Innovaciones</a>
                    <a href="#contacto"
                        class="block px-3 py-2 text-gray-300 hover:text-nevora-green transition-colors">Contacto</a>
                    <div class="pt-4 pb-2 border-t border-white/10">
                        <a href="{{ route('login') }}"
                            class="block px-3 py-2 text-white hover:text-nevora-green transition-colors font-medium">
                            Iniciar Sesión
                        </a>
                        <a href="#"
                            class="block px-3 py-2 mt-2 bg-nevora-green text-white rounded-lg hover:bg-green-600 transition-colors font-medium text-center mx-3">
                            Comenzar Gratis
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
                    Sistema de Gestión para
                    <span class="text-nevora-green block mt-2">Ópticas en Venezuela</span>
                </h1>
                <p class="mt-8 text-xl md:text-2xl text-gray-300 max-w-4xl mx-auto leading-relaxed" data-aos="fade-up"
                    data-aos-delay="200">
                    Software especializado para ópticas, optometristas y oftalmólogos en Caracas. 
                    Gestión integral de historias clínicas, inventario y facturación diseñado para el mercado venezolano.
                </p>
                <div class="mt-12" data-aos="fade-up" data-aos-delay="300">
                    <a href="#"
                        class="bg-gradient-accent text-white px-10 py-4 rounded-lg text-lg font-semibold hover:shadow-xl transform hover:scale-105 transition-all duration-300 inline-block">
                        Solicitar Demo Gratuita →
                    </a>
                </div>
                <div class="mt-6 text-gray-300 text-sm">
                    <p>Disponible en Caracas y toda Venezuela | Soporte técnico local</p>
                </div>
            </div>
        </div>

        <!-- Decorative wave -->
        <div class="relative" data-aos="fade-up" data-aos-delay="400">
            <svg viewBox="0 0 1200 120" class="w-full h-20 text-white">
                <path d="M0,60 C200,100 400,20 600,60 C800,100 1000,20 1200,60 L1200,120 L0,120 Z"
                    fill="currentColor" />
            </svg>
        </div>
    </section>

    <!-- About NEVORA Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-display text-4xl font-bold text-nevora-blue">
                    Acerca de NEVORA
                </h2>
                <div class="w-24 h-1 bg-nevora-green mx-auto mt-4"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right" data-aos-delay="100">
                    <p class="text-lg text-gray-600 leading-relaxed mb-6">
                        Somos un laboratorio de innovación tecnológica dedicado a transformar las fronteras
                        de los sistemas de gestión. Nuestra misión es desarrollar soluciones de vanguardia
                        que empoderen negocios ópticos e impulsen a las empresas individuales.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-nevora-green p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display font-semibold text-nevora-blue text-lg">Innovación Primero</h3>
                                <p class="text-gray-600">Utilizamos investigación de vanguardia con métodos de
                                    aprendizaje avanzados para crear tecnología revolucionaria.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-nevora-green p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display font-semibold text-nevora-blue text-lg">Espíritu Colaborativo
                                </h3>
                                <p class="text-gray-600">Nuestra diversidad de talentos se centra en cooperaciones de
                                    colaboración para evaluar y crear soluciones empresariales.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-nevora-green p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display font-semibold text-nevora-blue text-lg">Integridad y Confianza
                                </h3>
                                <p class="text-gray-600">Nuestros desarrollos transparentes son diseñados para lograr
                                    el desarrollo ético sostenible en el negocio óptico.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative" data-aos="fade-left" data-aos-delay="500">
                    <div class="bg-gradient-dark p-8 rounded-2xl shadow-2xl">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect width='400' height='300' fill='%23f8f9fa'/%3E%3Cg fill='%23003366'%3E%3Ccircle cx='200' cy='100' r='30'/%3E%3Crect x='170' y='120' width='60' height='8' rx='4'/%3E%3Crect x='180' y='135' width='40' height='6' rx='3'/%3E%3Crect x='160' y='160' width='80' height='4' rx='2'/%3E%3Crect x='170' y='170' width='60' height='4' rx='2'/%3E%3Crect x='180' y='180' width='40' height='4' rx='2'/%3E%3C/g%3E%3Ctext x='200' y='220' text-anchor='middle' fill='%232E8B57' font-family='Arial' font-size='14'%3ENEVORA Dashboard%3C/text%3E%3Ctext x='200' y='240' text-anchor='middle' fill='%23666' font-family='Arial' font-size='12'%3EGestión Integral%3C/text%3E%3C/svg%3E"
                            alt="NEVORA Sistema" class="w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Vision & Values -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-display text-4xl font-bold text-nevora-blue">
                    Nuestra Visión y Valores
                </h2>
                <div class="w-24 h-1 bg-nevora-green mx-auto mt-4"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="bg-nevora-blue p-6 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-semibold text-xl text-nevora-blue mb-4">Innovación Primero</h3>
                    <p class="text-gray-600">Utilizamos investigación de vanguardia con métodos de aprendizaje
                        avanzados para crear tecnología revolucionaria.</p>
                </div>

                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="bg-nevora-green p-6 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-semibold text-xl text-nevora-blue mb-4">Espíritu Colaborativo</h3>
                    <p class="text-gray-600">Nuestra diversidad de talentos se centra en cooperaciones de colaboración
                        para evaluar y crear soluciones empresariales.</p>
                </div>

                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div
                        class="bg-nevora-blue p-6 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-semibold text-xl text-nevora-blue mb-4">Integridad y Confianza</h3>
                    <p class="text-gray-600">Nuestros desarrollos transparentes son diseñados para lograr el desarrollo
                        ético sostenible en el negocio óptico.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Core Services -->
    <section id="servicios" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl font-bold text-nevora-blue">
                    Soluciones Especializadas para Ópticas
                </h2>
                <p class="mt-4 text-xl text-gray-600">
                    Sistema completo adaptado a las necesidades del mercado óptico venezolano
                </p>
                <div class="w-24 h-1 bg-nevora-green mx-auto mt-4"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-nevora-blue p-8 rounded-2xl mb-6 group-hover:bg-nevora-green transition-colors">
                        <svg class="w-16 h-16 text-white mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-2xl text-nevora-blue mb-4">Historia Clínica Digital</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Gestión completa de pacientes, exámenes optométricos, recetas y diagnósticos. 
                        Compatible con los requerimientos médicos venezolanos.
                    </p>
                </div>

                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-nevora-green p-8 rounded-2xl mb-6 group-hover:bg-nevora-blue transition-colors">
                        <svg class="w-16 h-16 text-white mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-2xl text-nevora-blue mb-4">Gestión de Inventario y Ventas</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Control de monturas, lentes y accesorios. 
                        Múltiples formas de pago.
                    </p>
                </div>

                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-nevora-blue p-8 rounded-2xl mb-6 group-hover:bg-nevora-green transition-colors">
                        <svg class="w-16 h-16 text-white mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-2xl text-nevora-blue mb-4">Laboratorio y Distribución</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Seguimiento de órdenes de laboratorio, gestión de proveedores y 
                        distribución de productos ópticos en toda Venezuela.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Innovations -->
    <section id="innovaciones" class="py-20 bg-gradient-dark text-white dark:text-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-delay="100">
                <h2 class="font-display text-4xl font-bold">
                    Innovaciones Destacadas
                </h2>
                <p class="mt-4 text-xl text-gray-300">
                    Descubre algunas de nuestras innovaciones más destacadas que están dando forma al futuro de la
                    tecnología óptica
                </p>
                <div class="w-24 h-1 bg-nevora-green mx-auto mt-4"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-16">
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 hover:bg-opacity-20 transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="100">
                    <div class="mb-6">
                        <div
                            class="w-full h-48 bg-gradient-to-br from-nevora-green to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-display font-bold text-2xl mb-4 text-nevora-blue">Procesamiento Cuántico Mejorado</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Explora el futuro de la computación cuántica con procesadores avanzados que
                        brindan capacidades de cálculo sin precedentes para análisis ópticos complejos.
                    </p>
                    <a href="#" class="text-nevora-green hover:text-green-400 font-semibold transition-colors">
                        Leer Más →
                    </a>
                </div>

                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 hover:bg-opacity-20 transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="200">
                    <div class="mb-6">
                        <div
                            class="w-full h-48 bg-gradient-to-br from-blue-600 to-nevora-green rounded-xl flex items-center justify-center">
                            <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-display font-bold text-2xl mb-4 text-nevora-blue">Redes Neurales Adaptativas</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Las redes neurales adaptativas aprovechan el aprendizaje automático para personalizar
                        y optimizar la experiencia de gestión óptica de cada usuario.
                    </p>
                    <a href="#" class="text-nevora-green hover:text-green-400 font-semibold transition-colors">
                        Leer Más →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Ready to Innovate -->
    <section class="py-20 bg-gradient-accent text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-4xl font-bold mb-6" data-aos="fade-up" data-aos-delay="100">
                ¿Listo para Innovar con Nosotros?
            </h2>
            <p class="text-xl mb-8 opacity-90" data-aos="fade-up" data-aos-delay="200">
                Únete a NEVORA y eleva tu óptica al siguiente nivel con tecnología de vanguardia
                para optimizar cada aspecto de tu negocio.
            </p>
            <a href="#" data-aos="fade-up" data-aos-delay="300"
                class="bg-white text-nevora-green px-10 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 inline-block">
                Comenzar Ahora →
            </a>
        </div>
    </section>

    <!-- Contact Us -->
    <section id="contacto" class="py-20 bg-nevora-blue text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-down">
                <h2 class="font-display text-4xl font-bold">Contáctanos en Venezuela</h2>
                <p class="mt-4 text-xl text-gray-300">
                    Agenda una demostración personalizada de nuestro sistema para ópticas
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <div class="space-y-8" data-aos="fade-right" data-aos-delay="100">
                    <div>
                        <h3 class="text-2xl font-semibold mb-4">Oficina Principal</h3>
                        <p class="text-gray-300">Av. Francisco de Miranda</p>
                        <p class="text-gray-300">Edificio Centro Empresarial</p>
                        <p class="text-gray-300">Chacao, Caracas 1060</p>
                        <p class="text-gray-300">Venezuela</p>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold mb-4">Contacto Directo</h3>
                        <p class="text-gray-300">Teléfono: +58 212-555-0123</p>
                        <p class="text-gray-300">WhatsApp: +58 424-555-0123</p>
                        <p class="text-gray-300">Email: contacto@nevora.com</p>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold mb-4">Horario de Atención</h3>
                        <p class="text-gray-300">Lunes a Viernes: 8:00 AM - 5:00 PM</p>
                        <p class="text-gray-300">Sábados: 9:00 AM - 1:00 PM</p>
                    </div>
                </div>

                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8" data-aos="fade-left" data-aos-delay="200">
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <input type="text" placeholder="Nombre Completo" required
                                    class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-300 text-white focus:outline-none focus:ring-2 focus:ring-nevora-green focus:border-transparent">
                            </div>
                            <div>
                                <input type="email" placeholder="Correo Electrónico" required
                                    class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-300 text-white focus:outline-none focus:ring-2 focus:ring-nevora-green focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <input type="tel" placeholder="Teléfono / WhatsApp" required
                                class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-300 text-white focus:outline-none focus:ring-2 focus:ring-nevora-green focus:border-transparent">
                        </div>
                        <div>
                            <select class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg text-gray-300 focus:outline-none focus:ring-2 focus:ring-nevora-green focus:border-transparent">
                                <option value="">Tipo de Negocio</option>
                                <option value="optica">Óptica</option>
                                <option value="clinica">Clínica Oftalmológica</option>
                                <option value="laboratorio">Laboratorio Óptico</option>
                                <option value="distribuidor">Distribuidor</option>
                            </select>
                        </div>
                        <div>
                            <textarea rows="4" placeholder="Mensaje" required
                                class="w-full px-4 py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg placeholder-gray-300 text-white focus:outline-none focus:ring-2 focus:ring-nevora-green focus:border-transparent"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                class="bg-nevora-green text-white px-10 py-4 rounded-lg font-semibold hover:bg-green-600 transform hover:scale-105 transition-all duration-300">
                                Solicitar Información
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Microdata for Local Business -->
    {{-- <script type="application/ld+json">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="mb-8" data-aos="fade-up">
                    <span class="font-display text-3xl font-bold text-white">NEVORA</span>
                    <span class="text-nevora-green ml-2">by NEXA 2.0</span>
                </div>
                <p class="text-gray-400 mb-8" data-aos="fade-up" data-aos-delay="100">
                    &copy; 2025 NEXA 2.0 Innovations. Todos los derechos reservados.
                </p>
                <div class="flex justify-center space-x-8 text-sm text-gray-400" data-aos="fade-up"
                    data-aos-delay="200">
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
