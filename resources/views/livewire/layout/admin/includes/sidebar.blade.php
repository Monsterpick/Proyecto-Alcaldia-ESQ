@php
    /* Panel Lateral */
    $links = [
        [
            'header' => 'Inicio',
            'permission' => 'view-dashboard',
        ],
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge-high',
            'url' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
            'permission' => 'view-dashboard',
        ],
        [
            'name' => 'Tenants',
            'icon' => 'fa-solid fa-building',
            'url' => route('admin.tenants.index'),
            'active' => request()->routeIs('admin.tenants.*'),
            'permission' => 'view-tenant',
        ],
        [
            'name' => 'Pagos',
            'icon' => 'fa-solid fa-circle-dollar-to-slot',
            'url' => route('admin.tenant-payments.index'),
            'active' => request()->routeIs('admin.tenant-payments.*'),
            'permission' => 'view-tenant-payment',
        ],
        [
            'header' => 'Administración',
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
            'name' => 'Pacientes',
            'icon' => 'fa-solid fa-user-injured',
            'url' => route('admin.patients.index'),
            'active' => request()->routeIs('admin.patients.*'),
            'permission' => 'view-patient',
        ],
        [
            'name' => 'Doctores',
            'icon' => 'fa-solid fa-user-doctor',
            'url' => route('admin.doctors.index'),
            'active' => request()->routeIs('admin.doctors.*'),
            'permission' => 'view-doctor',
        ],
        [
            'name' => 'Citas',
            'icon' => 'fa-solid fa-calendar-check',
            'url' => route('admin.appointments.index'),
            'active' => request()->routeIs('admin.appointments.*'),
            'permission' => 'view-appointment',
        ],
        [
            'name' => 'Calendario',
            'icon' => 'fa-solid fa-calendar-days',
            'url' => route('admin.calendar.index'),
            'active' => request()->routeIs('admin.calendar.*'),
            'permission' => 'view-calendar',
        ],
        [
            'header' => 'Configuración de Sistema',
            'permission' => 'view-setting',
        ],


        [
            'name' => 'Direcciones',
            'icon' => 'fa-solid fa-location-dot',
            'href' => '#',
            'active' => request()->routeIs([
                'admin.estados.*', 
                'admin.municipios.*', 
                'admin.parroquias.*'
            ]),
            'id_submenu' => 'submenu-direcciones',
            'permission' => 'view-direccion',
            'submenu' => [
                [
                    'name' => 'Estados',
                    'icon' => 'fa-solid fa-map-location-dot',
                    'url' => route('admin.estados.index'),
                    'active' => request()->routeIs('admin.estados.*'),
                    'permission' => 'view-estado',
                ],
                [
                    'name' => 'Municipios',
                    'icon' => 'fa-solid fa-location-dot',
                    'url' => route('admin.municipios.index'),
                    'active' => request()->routeIs('admin.municipios.*'),
                    'permission' => 'view-municipio',
                ],
                [
                    'name' => 'Parroquias',
                    'icon' => 'fa-solid fa-map-pin',
                    'url' => route('admin.parroquias.index'),
                    'active' => request()->routeIs('admin.parroquias.*'),
                    'permission' => 'view-parroquia',
                ],
            ],
        ],
        [
            'name' => 'General',
            'icon' => 'fa-solid fa-gears',
            'href' => '#',
            'active' => request()->routeIs([
                'admin.settings.*',
                'admin.roles.*',
                'admin.permissions.*',
                'admin.estatuses.*',
                'admin.appointment-statuses.*',
                'admin.payment-types.*',
                'admin.payment-origins.*',
                'admin.categories.*',
                'admin.products.*',
                'admin.warehouses.*',
            ]),
            'id_submenu' => 'submenu-general',
            'permission' => 'view-general',
            'submenu' => [
                [
                    'name' => 'Datos de la empresa',
                    'icon' => 'fa-solid fa-building',
                    'url' => route('admin.settings.general'),
                    'active' => request()->routeIs('admin.settings.*'),
                    'permission' => 'view-setting',
                ],
                [
                    'name' => 'Moneda',
                    'icon' => 'fa-solid fa-money-bill',
                    'url' => route('admin.settings.index'),
                    'active' => request()->routeIs('admin.settings.*'),
                    'permission' => 'view-setting',
                ],
                [
                    'name' => 'Logos',
                    'icon' => 'fa-solid fa-image',
                    'url' => route('admin.settings.logo'),
                    'active' => request()->routeIs('admin.settings.*'),
                    'permission' => 'view-setting',
                ],
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
                [
                    'name' => 'Estatus',
                    'icon' => 'fa-solid fa-toggle-on',
                    'url' => route('admin.estatuses.index'),
                    'active' => request()->routeIs('admin.estatuses.*'),
                    'permission' => 'view-estatus',
                ],
                [
                    'name' => 'Estatus de Cita',
                    'icon' => 'fa-solid fa-calendar-days',
                    'url' => route('admin.appointment-statuses.index'),
                    'active' => request()->routeIs('admin.appointment-statuses.*'),
                    'permission' => 'view-appointment-status',
                ],
                [
                    'name' => 'Tipos de pago',
                    'icon' => 'fa-solid fa-coins',
                    'url' => route('admin.payment-types.index'),
                    'active' => request()->routeIs('admin.payment-types.*'),
                    'permission' => 'view-payment-type',
                ],
                [
                    'name' => 'Orígenes de pago',
                    'icon' => 'fa-solid fa-hand-holding-dollar',
                    'url' => route('admin.payment-origins.index'),
                    'active' => request()->routeIs('admin.payment-origins.*'),
                    'permission' => 'view-payment-origin',
                ],
                [
                    'name' => 'Categorías',
                    'icon' => 'fa-solid fa-tags',
                    'url' => route('admin.categories.index'),
                    'active' => request()->routeIs('admin.categories.*'),
                    'permission' => 'view-category',
                ],
                [
                    'name' => 'Productos',
                    'icon' => 'fa-solid fa-box',
                    'url' => route('admin.products.index'),
                    'active' => request()->routeIs('admin.products.*'),
                    'permission' => 'view-product',
                ],


                [
                    'name' => 'Almacenes',
                    'icon' => 'fa-solid fa-warehouse',
                    'url' => route('admin.warehouses.index'),
                    'active' => request()->routeIs('admin.warehouses.*'),
                    'permission' => 'view-warehouse',
                ],
            ],

        ],
        [
            'name' => 'Compras',
            'icon' => 'fa-solid fa-cart-shopping',
            'active' => request()->routeIs([
                'admin.suppliers.*',
                'admin.purchase-orders.*',
                'admin.purchases.*',
            ]),
            'permission' => 'view-purchase',
            'id_submenu' => 'submenu-compras',
            'submenu' => [
                [
                    'name' => 'Proveedores',
                    'icon' => 'fa-solid fa-truck',
                    'url' => route('admin.suppliers.index'),
                    'active' => request()->routeIs('admin.suppliers.*'),
                    'permission' => 'view-supplier',
                ],
                [
                    'name' => 'Ordenes de compra',
                    'icon' => 'fa-solid fa-cart-shopping',
                    'url' => route('admin.purchase-orders.index'),
                    'active' => request()->routeIs('admin.purchase-orders.*'),
                    'permission' => 'view-purchase-order',
                ],
                [
                    'name' => 'Compras',
                    'icon' => 'fa-solid fa-cart-plus',
                    'url' => route('admin.purchases.index'),
                    'active' => request()->routeIs('admin.purchases.*'),
                    'permission' => 'view-purchase',
                ],
            ],
        ],
        [
            'name' => 'Ventas',
            'icon' => 'fa-solid fa-cash-register',
            'active' => request()->routeIs([
                'admin.customers.*',
                'admin.quotes.*',
                'admin.sales.*',
            ]),
            'permission' => 'view-sale',
            'id_submenu' => 'submenu-ventas',
            'submenu' => [
                [
                    'name' => 'Clientes',
                    'icon' => 'fa-solid fa-user-group',
                    'url' => route('admin.customers.index'),
                    'active' => request()->routeIs('admin.customers.*'),
                    'permission' => 'view-customer',
                ],
                [
                    'name' => 'Cotizaciones',
                    'icon' => 'fa-solid fa-file-invoice',
                    'url' => route('admin.quotes.index'),
                    'active' => request()->routeIs('admin.quotes.*'),
                    'permission' => 'view-quote',
                ],
                [
                    'name' => 'Ventas',
                    'icon' => 'fa-solid fa-file-invoice-dollar',
                    'url' => route('admin.sales.index'),
                    'active' => request()->routeIs('admin.sales.*'),
                    'permission' => 'view-sale',
                ],
            ],
        ],

        [
            'name' => 'Movimientos',
            'icon' => 'fa-solid fa-arrows-rotate',
            'active' => request()->routeIs([
                'admin.movements.*',
            ]),
            'permission' => 'view-movement',
            'id_submenu' => 'submenu-movimientos',
            'submenu' => [
                [
                    'name' => 'Entradas y Salidas',
                    'icon' => 'fa-solid fa-right-left',
                    'url' => route('admin.movements.index'),
                    'active' => request()->routeIs('admin.movements.*'),
                    'permission' => 'view-movement',
                ],
                [
                    'name' => 'Transferencias',
                    'icon' => 'fa-solid fa-retweet',
                    'url' => route('admin.transfers.index'),
                    'active' => request()->routeIs('admin.transfers.*'),
                    'permission' => 'view-movement',
                ],
            ],
        ],

        [
            'name' => 'Reportes',
            'icon' => 'fa-solid fa-chart-line',
            'active' => request()->routeIs([
                'admin.reports.*',
            ]),
            'permission' => 'view-report',
            'id_submenu' => 'submenu-reportes',
            'submenu' => [
                [
                    'name' => 'Entradas y Salidas',
                    'icon' => 'fa-solid fa-users',
                    'url' => route('admin.customers.index'),
                    'active' => request()->routeIs('admin.customers.*'),
                    'permission' => 'view-customer',
                ],
                [
                    'name' => 'Transferencias',
                    'icon' => 'fa-solid fa-cart-shopping',
                    'url' => route('admin.quotes.index'),
                    'active' => request()->routeIs('admin.quotes.*'),
                    'permission' => 'view-quote',
                ],
            ],
        ],

    ];

@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-[100dvh] pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    :class="{
        'translate-x-0 ease-out': sidebarOpen,
        '-translate-x-full ease-in': !sidebarOpen
    }" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                @can($link['permission'])
                    <li>
                        @isset($link['header'])
                            <div class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">
                                {{ $link['header'] }}
                            </div>
                        @else
                            @isset($link['submenu'])
                                <div x-data="{
                                    open: {{ $link['active'] ? 'true' : 'false' }}
                                }">
                                    <button type="button"
                                        @click="open = !open"
                                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                        {{-- aria-controls="{{ $link['id_submenu'] }}" data-collapse-toggle="{{ $link['id_submenu'] }}" --}}>
                                        <i class="{{ $link['icon'] }}"></i>
                                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $link['name'] }}</span>
                                        <i class="fa-solid fa-chevron-down" :class="{ 'rotate-180': open }"></i>
                                    </button>
                                    <ul x-show="open" x-cloak {{-- id="{{ $link['id_submenu'] }}" --}} class="{{-- hidden --}} py-2 space-y-2">
                                        @foreach ($link['submenu'] as $submenu)
                                            <li>
                                                <a href="{{ $submenu['url'] }}" wire:navigate
                                                    class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $submenu['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                                    <i class="{{ $submenu['icon'] }}"></i>
                                                    <span class="ms-2">{{ $submenu['name'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            @else
                                <a href="{{ $link['url'] }}"
                                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}"
                                    wire:navigate>
                                    <i class="{{ $link['icon'] }}"></i>
                                    <span class="ms-2">{{ $link['name'] }}</span>
                                </a>
                            @endisset
                        @endisset
                    </li>
                @endcan
            @endforeach
            <li>

            </li>

        </ul>
    </div>
</aside>