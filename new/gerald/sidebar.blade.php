@php
        /* Panel Lateral */
        $links = [
            [
                'header' => 'Inicio',
                'permission' => 'view-dashboard',
            ],
            [
                'name' => 'Dashboard',
                'icon' => 'fa-solid fa-gauge-high ',
                'url' => route('admin.dashboard'),
                'active' => request()->routeIs('admin.dashboard'),
                'permission' => 'view-dashboard',
            ],


            [
                'header' => 'Panel de usuarios',
                'permission' => 'view-patient',
            ],
            [
                'name' => 'Usuarios',
                'icon' => 'fa-solid fa-users',
                'url' => route('admin.users.index'),
                'active' => request()->routeIs('admin.users.*'),
                'permission' => 'view-user',
            ],



            [
                'header' => 'Gestion Legilativa',
                'permission' => 'view-documento',
            ],
            [
                'name' => 'Instrumentos Legales',
                'icon' => 'fa-solid fa-landmark',
                'href' => '#',
                'active' => request()->routeIs('admin.ordenanzas.*', 'admin.gacetas.*', 'admin.acuerdos.*', 'admin.categoria-instrumentos.*'),
                'id_submenu' => 'submenu-documentos',
                'permission' => 'view-documento',
                'submenu' => [
                    [
                        'name' => 'Ordenanzas',
                        'icon' => 'fa-solid fa-file-contract',
                        'url' => route('admin.ordenanzas.index'),
                        'active' => request()->routeIs('admin.ordenanzas.*'),
                        'permission' => 'view-ordenanza',
                    ],
                    [
                        'name' => 'Gacetas',
                        'icon' => 'fa-solid fa-newspaper',
                        'url' => route('admin.gacetas.index'),
                        'active' => request()->routeIs('admin.gacetas.*'),
                        'permission' => 'view-gaceta',
                    ],
                    [
                        'name' => 'Acuerdos',
                        'icon' => 'fa-solid fa-handshake',
                        'url' => route('admin.acuerdos.index'),
                        'active' => request()->routeIs('admin.acuerdos.*'),
                        'permission' => 'view-acuerdo',
                    ],

                [
                    'name' => 'Categoria/Instrumentos',
                'icon' => 'fa-solid fa-file-contract',
                    'url' => route('admin.categoria-instrumentos.index'),
                    'active' => request()->routeIs('admin.categoria-instrumentos.*'),
                    'permission' => 'view-categoria-instrumento',
                ],


                ],
            ],

                    [
                    'name' => 'Listado de Concejales',
                    'icon' => 'fa-solid fa-users',
                    'href' => '#',
                    'active' => request()->routeIs(['admin.concejales.*', 'admin.miembros.*', 'admin.comisiones.*']),
                    'id_submenu' => 'submenu-consejales',
                    'permission' => 'view-concejal',
                    'submenu' => [
                        [
                            'name' => 'Registrar Concejal',
                            'icon' => 'fa-solid fa-user-plus',
                            'url' => route('admin.concejales.index'),
                            'active' => request()->routeIs('admin.concejales.*'),
                            'permission' => 'view-concejal',
                        ],
                        [
                            'name' => 'Miembros/Comisión',
                            'icon' => 'fa-solid fa-users',
                            'url' => route('admin.miembros.index'),
                            'active' => request()->routeIs('admin.miembros.*'),
                            'permission' => 'view-miembro',
                        ],
                        [
                    'name' => 'Categoria/Comisiones',
                    'icon' => 'fa-solid fa-people-group',
                    'url' => route('admin.comisiones.index'),
                    'active' => request()->routeIs('admin.comisiones.*'),
                    'permission' => 'view-comision',
                ],


                    ],
                ],

                        [
                    'name' => 'Actas de Sesiones',
                    'icon' => 'fa-solid fa-book-bookmark',
                    'href' => '#',
                    'active' => request()->routeIs(['admin.sesion_ordinaria.*', 'sesion_ordinaria.*', 'admin.sesion_extraordinaria.*', 'sesion_extraordinaria.*',
                    'admin.sesion_solemne.*', 'sesion_solemne.*', 'admin.sesion_especial.*', 'sesion_especial.*']),
                    'id_submenu' => 'submenu-actas',
                    'permission' => 'view-acta',
                    'submenu' => [
                        [
                            'name' => 'Sesión Ordinaria',
                            'icon' => 'fa-solid fa-arrow-right-to-file',
                            'url' => route('admin.sesion_ordinaria.index'),
                            'active' => request()->routeIs(['admin.sesion_ordinaria.*', 'sesion_ordinaria.*']),
                            'permission' => 'view-sesion_ordinaria',
                        ],

    [
                            'name' => 'Sesión extraordinaria',
                            'icon' => 'fa-solid fa-arrow-right-to-file',
                            'url' => route('admin.sesion_extraordinaria.index'),
                            'active' => request()->routeIs(['admin.sesion_extraordinaria.*', 'sesion_extraordinaria.*']),
                            'permission' => 'view-sesion_extraordinaria',
                        ],



    [
                            'name' => 'Sesión solemne',
                            'icon' => 'fa-solid fa-arrow-right-to-file',
                            'url' => route('admin.sesion_solemne.index'),
                            'active' => request()->routeIs(['admin.sesion_solemne.*', 'sesion_solemne.*']),
                            'permission' => 'view-sesion_solemne',
                        ],


    [
                            'name' => 'Sesión especial',
                            'icon' => 'fa-solid fa-arrow-right-to-file',
                            'url' => route('admin.sesion_especial.index'),
                            'active' => request()->routeIs(['admin.sesion_especial.*', 'sesion_especial.*']),
                            'permission' => 'view-sesion_especial',
                        ],


                    ],
                ],

        [
            'header' => 'Gestión Cultural',
            'permission' => 'view-cronista',
        ],
        [
            'name' => 'Módulo Cronistas',
            'icon' => 'fa-solid fa-user-tie',
            'href' => '#',
            // Activo si estamos en cualquier ruta de cronistas (listado, show, create, edit)
            'active' => request()->routeIs('admin.cronistas.*') || request()->routeIs('admin.cronista.*'),
            'id_submenu' => 'submenu-cronistas',
            'permission' => 'view-cronista',
            'submenu' => [

                [
                    'name' => 'Cronista / Perfil',
                    'icon' => 'fa-solid fa-user-plus',
                    'url' => route('admin.cronista.show', ['cronista' => 1]), // variable dinámica
                    // Activo si estamos en show, edit o create de un cronista
                    'active' => request()->routeIs('admin.cronista.show')
                                || request()->routeIs('admin.cronista.edit')
                                || request()->routeIs('admin.cronista.create'),
                    'permission' => 'view-permission',
                ],

                [
                    'name' => 'Listado de Cronistas',
                    'icon' => 'fa-solid fa-table-list',
                    'url' => route('admin.cronistas.index'),
                    // Solo activo en la ruta exacta del listado
                    'active' => request()->routeIs('admin.cronistas.index'),
                    'permission' => 'view-cronista',
                ],

            ],
        ],


        [
                'name' => 'Módulo Crónicas',
                'icon' => 'fa-solid fa-book-open',
                'href' => '#',
                'active' => request()->routeIs('admin.cronicas.*', 'admin.categoria_cronicas.*') ,
                'id_submenu' => 'submenu-cronicas',
                'permission' => 'view-cronicas',
                'submenu' => [


 [
                        'name' => 'Registrar/Categoría',
                    'icon' => 'fa-solid fa-book-open',
                        'url' => route('admin.categoria_cronicas.index'),
                        'active' => request()->is('admin/categoria_cronicas'),
                        'permission' => 'view-categoria_cronica',
                    ],

                    [
                        'name' => 'Crónicas',
                        'icon' => 'fa-solid fa-scroll',
                        'url' => route('admin.cronicas.index'),
                        'active' => request()->is('admin/cronicas'),
                        'permission' => 'view-cronica',
                    ],


                [
                    'name' => 'Resportes/Cronicas',
                    'icon' => 'fa-solid fa-chart-pie',
                    'url' => url('admin/cronicas/show'),
                    'active' => request()->is('admin/cronicas/show'),
                    'permission' => 'view-permission',
                ],





                ],
            ],


  [
                'header' => 'Gestión Municipal',
                'permission' => 'view-setting, view-general, view-empresa',
            ],

            [
            'name' => 'Agenda Municipal',
            'icon' => 'fa-solid fa-calendar-plus',
            'href' => '#',
            'active' => request()->routeIs('admin.sesion_municipal.*', 'admin.categorias_participacion.*','admin.derecho_palabra.*'),
            'id_submenu' => 'submenu-participacion',
            'permission' => 'view-participacion',
            'submenu' => [

                  [
                    'name' => 'Registrar/Categoría',
                    'icon' => 'fa-solid fa-layer-group',
                    'url' => route('admin.categorias_participacion.index'),
                    'active' => request()->routeIs('admin.categorias_participacion.*'),
                    'permission' => 'view-categorias-participacion',
                ],

                [
                    'name' => 'Agendar Sesión',
                    'icon' => 'fa-solid fa-calendar-day',
                    'url' => route('admin.sesion_municipal.index'),
                    'active' => request()->routeIs('admin.sesion_municipal.*'),
                    'permission' => 'view-sesion-municipal',
                ],

                   [
                    'name' => 'Solicitudes',
                    'icon' => 'fa-solid fa-microphone',
                    'url' => route('admin.derecho_palabra.index'),
                    'active' => request()->routeIs('admin.derecho_palabra.*'),
                    'permission' => 'view-derecho-palabra',
                ],


            ],
        ],



     [
            'header' => 'Atención Ciudadana',
            'permission' => 'view-atencion-ciudadana',
        ],
        [
            'name' => 'Atención Ciudadana',
            'icon' => 'fa-solid fa-headset',
            'href' => '#',
            'active' => request()->routeIs('admin.atencion-ciudadana.*', 'admin.tipos_solicitud.*'),
            'id_submenu' => 'submenu-atencion-ciudadana',
            'permission' => 'view-atencion-ciudadana',
            'submenu' => [
                [
                    'name' => 'Solicitudes',
                    'icon' => 'fa-solid fa-microphone',
                    'url' => route('admin.atencion_ciudadana.index'),
                    'active' => request()->routeIs('admin.atencion-ciudadana.*'),
                    'permission' => 'view-atencion-ciudadana',
                ],

                [
                    'name' => 'Tipo de Solicitud',
                    'icon' => 'fa-solid fa-layer-group',
                    'url' => route('admin.tipos_solicitud.index'),
                    'active' => request()->routeIs('admin.tipos_solicitud.*'),
                    'permission' => 'view-tipos_solicitud',
                ],
            ],
        ],



            [
                'header' => 'Panel/Noticias',
                'permission' => 'view-setting, view-general, view-empresa',
            ],

            [
                'name' => 'Noticias',
                'icon' => 'fa-solid fa-newspaper',
                'href' => '#',
                'active' => request()->routeIs('admin.noticias.*'),
                'id_submenu' => 'submenu-noticias',
                'permission' => 'view-noticias',
                'submenu' => [

                    [
                        'name' => 'Crear Noticia',
                        'icon' => 'fa-solid fa-plus',
                        'url' => route('admin.noticias.create'),
                        'active' => request()->routeIs('admin.noticias.create'),
                        'permission' => 'create-noticias',
                    ],
                        [
                        'name' => 'Lista de Noticias',
                        'icon' => 'fa-solid fa-list',
                        'url' => route('admin.noticias.index'),
                        'active' => request()->routeIs('admin.noticias.index'),
                        'permission' => 'view-noticias',
                    ],

                ],
            ],

                        [
                            'header' => 'Configuración Empresa',
                            'permission' => 'view-setting, view-general, view-empresa',
                        ],
                [
                    'name' => 'Config/Institucional',
                    'icon' => 'fa-solid fa-building-columns',
                    'href' => '#',
                    'active' => request()->routeIs('admin.empresa.*', 'admin.settings.*', 'admin.color_piker.*'),
                    'id_submenu' => 'submenu-institucional',
                    'permission' => 'view-empresa',
                    'submenu' => [
                        [
                            'name' => 'Empresa/Institución',
                            'icon' => 'fa-solid fa-building',
                            'url' => route('admin.empresa.index'),
                            'active' => request()->routeIs('admin.empresa.*'),
                            'permission' => 'view-empresa',
                        ],
                        [
                            'name' => 'Logos/Empresa',
                            'icon' => 'fa-solid fa-image',
                            'url' => route('admin.settings.logo'),
                            'active' => request()->routeIs('admin.settings.*'),
                            'permission' => 'view-setting',
                        ],
                        [
                            'name' => 'Colores/Institucional',
                            'icon' => 'fa-solid fa-palette',
                            'url' => route('admin.color_piker.index'),
                            'active' => request()->routeIs('admin.color_piker.*'),
                            'permission' => 'color_piker-',
                        ],
                    ],
                ],

                [
                            'header' => 'Configuración Sistema',
                            'permission' => 'view-setting, view-general, view-empresa',
                        ],

            [
                'name' => 'Panel de Tickets',
                'icon' => 'fa-solid fa-ticket-alt',
                'href' => '#',
                // SOLUCIÓN: Excluir específicamente la ruta .create
                'active' => request()->routeIs('admin.soporte_tecnico.index'),
                'id_submenu' => 'submenu-tickets',
                'permission' => 'view-general',
                'submenu' => [
                    [
                        'name' => 'Tickets de Soporte',
                        'icon' => 'fa-solid fa-envelope-open-text',
                        'url' => route('admin.soporte_tecnico.index'),
                        // SOLUCIÓN: Excluir específicamente la ruta .create
                        'active' => request()->routeIs('admin.soporte_tecnico.index'),
                        'permission' => 'view-soporte',
                    ],
                ],
            ],

              [
            'name' => 'Configuración',
                'icon' => 'fa-solid fa-gears',
                'href' => '#',
                'active' => request()->routeIs([
                    'admin.roles.*',
                    'admin.permissions.*',

                ]),
                'id_submenu' => 'submenu-general',
                'permission' => 'view-general',
                'submenu' => [
                    [
                        'name' => 'Roles',
                        'icon' => 'fa-solid fa-users-gear',
                        'url' => route('admin.roles.index'),
                        'active' => request()->routeIs('admin.roles.*'),
                        'permission' => 'view-role',
                    ],
                    [
                        'name' => 'Permisos',
                        'icon' => 'fa-solid fa-key',
                        'url' => route('admin.permissions.index'),
                        'active' => request()->routeIs('admin.permissions.*'),
                        'permission' => 'view-permission',
                    ],

                ],
            ],

        ];
        @endphp
        <aside id="logo-sidebar"
            class="fixed top-0 left-0 z-40 w-64 h-[100dvh] pt-20 flex flex-col justify-between transition-transform bg-gradient-to-b from-blue-500 via-blue-600 to-indigo-700 animate-gradientBackground border-r border-gray-200 sm:translate-x-0 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700"
            :class="{
                'translate-x-0 ease-out': sidebarOpen,
                '-translate-x-full ease-in': !sidebarOpen
            }" aria-label="Sidebar">

            {{-- Menú principal --}}
            <div class="h-full px-3 pb-4 overflow-y-auto">
                <ul class="space-y-2 font-medium">
                    @foreach ($links as $link)
                        @can($link['permission'])
                            <li class="animate-fadeIn">
                                @isset($link['header'])
                                    <div class="px-2 py-2 text-xs font-semibold text-gray-200 uppercase tracking-wide">
                                        {{ $link['header'] }}
                                    </div>
                                @else
                                    @isset($link['submenu'])
                                        <div x-data="{ open: {{ $link['active'] ? 'true' : 'false' }} }">
                                            <button type="button"
                                                @click="open = !open"
                                                class="flex items-center w-full p-2 text-white transition-all duration-300 rounded-xl group hover:scale-105 hover:shadow-lg hover:bg-white/10">
                                                <i class="{{ $link['icon'] }}"></i>
                                                <span class="flex-1 ms-3 text-left whitespace-nowrap">{{ $link['name'] }}</span>
                                                <i class="fa-solid fa-chevron-down transform transition-transform duration-300"
                                                :class="{ 'rotate-180': open }"></i>
                                            </button>
                                            <ul x-show="open" x-cloak class="py-2 space-y-2 pl-6">
                                                @foreach ($link['submenu'] as $submenu)
                                                    <li>
                                                        <a href="{{ $submenu['url'] }}" wire:navigate
                                                            class="flex items-center w-full p-2 text-white/90 rounded-lg transition-all duration-300 hover:scale-105 hover:bg-white/10 {{ $submenu['active'] ? 'bg-white/20' : '' }}">
                                                            <i class="{{ $submenu['icon'] }}"></i>
                                                            <span class="ms-2">{{ $submenu['name'] }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ $link['url'] }}"
                                            class="flex items-center p-2 text-white rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-white/10 group {{ $link['active'] ? 'bg-white/20' : '' }}"
                                            wire:navigate>
                                            <i class="{{ $link['icon'] }}"></i>
                                            <span class="ms-2">{{ $link['name'] }}</span>
                                        </a>
                                    @endisset
                                @endisset
                            </li>
                        @endcan
                    @endforeach
                </ul>
            </div>

            <div class="p-4 border-t border-white/20">
            <a href="{{ ('/') }}" class="flex justify-center items-center" target="_blank">
        <img src="{{ asset(path: 'logo_nexa_18.png') }}"
            class="h-6 sm:h-7 md:h-8 w-auto drop-shadow-lg transform transition-all duration-500 hover:scale-x-105 hover:rotate-1 animate-bounce-slow"
            alt="Nevora Logo" />
        <span class="self-center text-sm sm:text-base md:text-xl font-bold whitespace-nowrap text-white drop-shadow-md"></span>
    </a>
                        <br>
            <a
                href="{{ route('admin.soporte_tecnico.create') }}"
                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 dark:from-gray-800 dark:to-gray-800 text-white font-semibold rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 dark:hover:from-gray-600 dark:hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400"
            >
                <i class="fa-solid fa-headset animate-bounce"></i>
                Soporte Técnico
        </a>
        </div>
        </aside>
