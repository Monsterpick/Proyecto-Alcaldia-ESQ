<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; 

?>

@php
    /* Panel Lateral */
    $links = [
        [
            'name' => 'Panel',
            'icon' => 'fa-solid fa-house',
            'url' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
            'permission' => 'view-dashboard',
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
            'header' => 'Beneficiarios',
            'permission' => 'view-dashboard',
        ],
        [
            'name' => 'Beneficiarios',
            'icon' => 'fa-solid fa-user-group',
            'url' => route('admin.beneficiaries.index'),
            'active' => request()->routeIs('admin.beneficiaries.*'),
            'permission' => 'view-dashboard',
        ],
        [
            'header' => 'Inventario/Almacén',
            'permission' => 'view-inventory',
        ],
        [
            'name' => 'Inventario',
            'icon' => 'fa-solid fa-boxes-stacked',
            'href' => '#',
            'active' => request()->routeIs([
                'admin.products.*',
                'admin.warehouses.*',
                'admin.categories.*',
                'admin.movements.*',
            ]),
            'id_submenu' => 'submenu-inventory',
            'permission' => 'view-inventory',
            'submenu' => [
                [
                    'name' => 'Productos',
                    'icon' => 'fa-solid fa-box',
                    'url' => route('admin.products.index'),
                    'active' => request()->routeIs('admin.products.*'),
                    'permission' => 'view-product',
                ],
                [
                    'name' => 'Almacén',
                    'icon' => 'fa-solid fa-warehouse',
                    'url' => route('admin.warehouses.index'),
                    'active' => request()->routeIs('admin.warehouses.*'),
                    'permission' => 'view-warehouse',
                ],
                [
                    'name' => 'Categorías',
                    'icon' => 'fa-solid fa-tags',
                    'url' => route('admin.categories.index'),
                    'active' => request()->routeIs('admin.categories.*'),
                    'permission' => 'view-category',
                ],
                [
                    'name' => 'Stock',
                    'icon' => 'fa-solid fa-boxes-stacked',
                    'url' => route('admin.stock-adjustments.index'),
                    'active' => request()->routeIs('admin.stock-adjustments.*'),
                    'permission' => 'view-stock-adjustment',
                ],
            ],
        ],
        [
            'name' => 'Movimientos',
            'icon' => 'fa-solid fa-arrows-rotate',
            'href' => '#',
            'active' => request()->routeIs([
                'admin.movements.*',
                'admin.inventory-entries.*',
                'admin.inventory-exits.*',
            ]),
            'id_submenu' => 'submenu-movements',
            'permission' => 'view-movement',
            'submenu' => [
                [
                    'name' => 'Entrada de Inventario',
                    'icon' => 'fa-solid fa-arrow-down',
                    'url' => route('admin.inventory-entries.index'),
                    'active' => request()->routeIs('admin.inventory-entries.*'),
                    'permission' => 'view-inventory-entry',
                ],
                [
                    'name' => 'Salida de Inventario',
                    'icon' => 'fa-solid fa-arrow-up',
                    'url' => route('admin.inventory-exits.index'),
                    'active' => request()->routeIs('admin.inventory-exits.*'),
                    'permission' => 'view-inventory-exit',
                ],
                [
                    'name' => 'Historial de Movimientos',
                    'icon' => 'fa-solid fa-clock-rotate-left',
                    'url' => route('admin.movements.index'),
                    'active' => request()->routeIs('admin.movements.*'),
                    'permission' => 'view-movement',
                ],
            ],
        ],
        [
            'header' => 'Reportes y Entregas',
            'permission' => 'view-dashboard',
        ],
        [
            'name' => 'Reportes de Entregas',
            'icon' => 'fa-solid fa-file-alt',
            'url' => route('admin.reports.index'),
            'active' => request()->routeIs('admin.reports.*'),
            'permission' => 'view-dashboard',
        ],
        [
            'name' => 'Mapa de Geolocalización',
            'icon' => 'fa-solid fa-map-location-dot',
            'url' => route('admin.map.index'),
            'active' => request()->routeIs('admin.map.*'),
            'permission' => 'view-dashboard',
        ],
        [
            'name' => 'Registro de Actividades',
            'icon' => 'fa-solid fa-clipboard-list',
            'url' => route('admin.activity-logs.index'),
            'active' => request()->routeIs('admin.activity-logs.*'),
            'permission' => 'view-dashboard',
        ],
        [
            'header' => 'Proyectos Comunitarios',
            'permission' => 'view-community-project',
        ],
        [
            'name' => 'Proyectos',
            'icon' => 'fa-solid fa-diagram-project',
            'href' => '#',
            'active' => request()->routeIs([
                'admin.community-projects.*',
                'admin.projects-in-progress.*',
                'admin.projects-executed.*',
                'admin.projects-proposed.*',
            ]),
            'id_submenu' => 'submenu-projects',
            'permission' => 'view-community-project',
            'submenu' => [
                [
                    'name' => 'En Proceso',
                    'icon' => 'fa-solid fa-spinner',
                    'url' => '#',
                    'active' => request()->routeIs('admin.projects-in-progress.*'),
                    'permission' => 'view-project-in-progress',
                ],
                [
                    'name' => 'Ejecutados',
                    'icon' => 'fa-solid fa-check-circle',
                    'url' => '#',
                    'active' => request()->routeIs('admin.projects-executed.*'),
                    'permission' => 'view-project-executed',
                ],
                [
                    'name' => 'Propuestos',
                    'icon' => 'fa-solid fa-lightbulb',
                    'url' => '#',
                    'active' => request()->routeIs('admin.projects-proposed.*'),
                    'permission' => 'view-project-proposed',
                ],
            ],
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
                [
                    'name' => 'Circuitos Comunales',
                    'icon' => 'fa-solid fa-map-marked-alt',
                    'url' => route('admin.circuitos-comunales.index'),
                    'active' => request()->routeIs('admin.circuitos-comunales.*'),
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
                    'active' => request()->routeIs('admin.settings.general.*'),
                    'permission' => 'view-setting',
                ],
                [
                    'name' => 'Moneda',
                    'icon' => 'fa-solid fa-money-bill',
                    'url' => route('admin.settings.index'),
                    'active' => request()->routeIs('admin.settings.moneda.*'),
                    'permission' => 'view-setting',
                ],
                [
                    'name' => 'Logos',
                    'icon' => 'fa-solid fa-image',
                    'url' => route('admin.settings.logo'),
                    'active' => request()->routeIs('admin.settings.logo.*'),
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
            ],

        ],

    ];

@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-[100dvh] transition-transform -translate-x-full sm:translate-x-0"
    style="background-color: var(--color-bg-primary); border-right: 1px solid var(--color-border-primary);"
    :class="{
        'translate-x-0 ease-out': sidebarOpen,
        '-translate-x-full ease-in': !sidebarOpen
    }" aria-label="Sidebar">
    
    <!-- Header del Sidebar con Logo -->
    <div class="px-5 py-6 bg-gradient-to-r from-blue-600 to-indigo-600" style="border-bottom: 1px solid var(--color-border-primary);">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3 flex-1">
                <img src="{{ asset('1.png') }}" alt="NEVORA Logo" class="h-10 w-auto object-contain">
                <div>
                    <h2 class="text-sm font-bold text-white leading-tight">Sistema Web de Gestion</h2>
                    <p class="text-sm font-semibold text-blue-100">Alcaldia del Municipio Escuque</p>
                </div>
            </a>
            <!-- Botón cerrar sidebar en móvil -->
            <button @click="sidebarOpen = false" class="sm:hidden text-white hover:bg-white/10 p-2 rounded-lg transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Contenido Principal del Sidebar -->
    <div class="flex flex-col overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-transparent" style="height: calc(100vh - 220px);">
        <ul class="space-y-1 px-3 py-4 font-medium flex-1">
            @foreach ($links as $link)
                @can($link['permission'])
                    <li>
                        @isset($link['header'])
                            <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase dark:text-gray-500">
                                {{ $link['header'] }}
                            </div>
                        @else
                            @isset($link['submenu'])
                                <div x-data="{
                                    open: {{ $link['active'] ? 'true' : 'false' }}
                                }">
                                    <button type="button"
                                        @click="open = !open"
                                        class="flex items-center w-full px-3 py-2.5 text-sm font-medium transition-all duration-200 rounded-lg group {{ $link['active'] ? 'font-semibold' : '' }}"
                                        style="color: {{ $link['active'] ? 'var(--color-blue-600)' : 'var(--color-text-secondary)' }}; background-color: {{ $link['active'] ? 'var(--color-blue-50)' : 'transparent' }};"
                                        onmouseover="this.style.backgroundColor='var(--color-bg-hover)'; this.style.color='var(--color-blue-600)'"
                                        onmouseout="this.style.backgroundColor='{{ $link['active'] ? 'var(--color-blue-50)' : 'transparent' }}'; this.style.color='{{ $link['active'] ? 'var(--color-blue-600)' : 'var(--color-text-secondary)' }}'">
                                        <i class="{{ $link['icon'] }} w-5" style="color: {{ $link['active'] ? 'var(--color-blue-600)' : 'var(--color-text-tertiary)' }};"></i>
                                        <span class="flex-1 ms-3 text-left">{{ $link['name'] }}</span>
                                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                                    </button>
                                    <ul x-show="open" 
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        class="mt-1 space-y-1">
                                        @foreach ($link['submenu'] as $submenu)
                                            @can($submenu['permission'])
                                                <li>
                                                    <a href="{{ $submenu['url'] }}" wire:navigate
                                                        class="flex items-center w-full px-3 py-2 pl-12 text-sm transition-all duration-200 rounded-lg {{ $submenu['active'] ? 'font-medium' : '' }}"
                                                        style="color: {{ $submenu['active'] ? 'var(--color-blue-600)' : 'var(--color-text-tertiary)' }}; background-color: {{ $submenu['active'] ? 'var(--color-blue-50)' : 'transparent' }};"
                                                        onmouseover="this.style.backgroundColor='var(--color-bg-hover)'; this.style.color='var(--color-blue-600)'"
                                                        onmouseout="this.style.backgroundColor='{{ $submenu['active'] ? 'var(--color-blue-50)' : 'transparent' }}'; this.style.color='{{ $submenu['active'] ? 'var(--color-blue-600)' : 'var(--color-text-tertiary)' }}'">
                                                        <i class="{{ $submenu['icon'] }} w-4 text-xs" style="color: {{ $submenu['active'] ? 'var(--color-blue-600)' : 'var(--color-text-tertiary)' }};"></i>
                                                        <span class="ms-2">{{ $submenu['name'] }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a href="{{ $link['url'] }}"
                                    class="flex items-center px-3 py-2.5 text-sm font-medium transition-all duration-200 rounded-lg {{ $link['active'] ? 'font-semibold' : '' }}"
                                    style="color: {{ $link['active'] ? 'var(--color-blue-600)' : 'var(--color-text-secondary)' }}; background-color: {{ $link['active'] ? 'var(--color-blue-50)' : 'transparent' }};"
                                    onmouseover="this.style.backgroundColor='var(--color-bg-hover)'; this.style.color='var(--color-blue-600)'"
                                    onmouseout="this.style.backgroundColor='{{ $link['active'] ? 'var(--color-blue-50)' : 'transparent' }}'; this.style.color='{{ $link['active'] ? 'var(--color-blue-600)' : 'var(--color-text-secondary)' }}'"
                                    wire:navigate>
                                    <i class="{{ $link['icon'] }} w-5" style="color: {{ $link['active'] ? 'var(--color-blue-600)' : 'var(--color-text-tertiary)' }};"></i>
                                    <span class="ms-3">{{ $link['name'] }}</span>
                                </a>
                            @endisset
                        @endisset
                    </li>
                @endcan
            @endforeach
        </ul>
    </div>

    <!-- Footer del Sidebar con Info del Usuario -->
    @if(Auth::check())
    <div class="absolute bottom-0 left-0 right-0" style="border-top: 1px solid var(--color-border-primary); background-color: var(--color-bg-primary);">
        <div class="p-3 space-y-2">
            <!-- Info del Usuario -->
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg" style="background-color: var(--color-bg-secondary);">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white font-semibold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate" style="color: var(--color-text-primary);">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs truncate" style="color: var(--color-text-tertiary);">
                        {{ Auth::user()->email }}
                    </p>
                </div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="grid grid-cols-3 gap-2">
                <!-- Botón Tema -->
                <button @click="darkMode = !darkMode"
                    class="flex items-center justify-center gap-1 px-2 py-2 text-xs font-medium rounded-lg transition-colors"
                    style="background-color: var(--color-bg-secondary); color: var(--color-text-secondary); box-shadow: var(--shadow-sm);"
                    onmouseover="this.style.backgroundColor='var(--color-bg-hover)'; this.style.color='var(--color-blue-600)'"
                    onmouseout="this.style.backgroundColor='var(--color-bg-secondary)'; this.style.color='var(--color-text-secondary)'">
                    <i class="fa-solid text-sm" :class="darkMode ? 'fa-sun text-yellow-500' : 'fa-moon text-blue-600'"></i>
                    <span x-text="darkMode ? 'Claro' : 'Oscuro'"></span>
                </button>
                
                <!-- Botón Configuración -->
                <a href="{{ route('admin.settings.profile') }}" wire:navigate
                    class="flex items-center justify-center gap-1 px-2 py-2 text-xs font-medium rounded-lg transition-colors"
                    style="background-color: var(--color-bg-secondary); color: var(--color-text-secondary); box-shadow: var(--shadow-sm);"
                    onmouseover="this.style.backgroundColor='var(--color-bg-hover)'; this.style.color='var(--color-blue-600)'"
                    onmouseout="this.style.backgroundColor='var(--color-bg-secondary)'; this.style.color='var(--color-text-secondary)'">
                    <i class="fa-solid fa-gear text-sm"></i>
                    <span>Config</span>
                </a>
                
                <!-- Botón Salir -->
                <button wire:click="logout"
                    class="flex items-center justify-center gap-1 px-2 py-2 text-xs font-medium rounded-lg transition-colors"
                    style="background-color: var(--color-bg-secondary); color: var(--color-red-600); box-shadow: var(--shadow-sm);"
                    onmouseover="this.style.backgroundColor='var(--color-red-50)'; this.style.color='var(--color-red-700)'"
                    onmouseout="this.style.backgroundColor='var(--color-bg-secondary)'; this.style.color='var(--color-red-600)'">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                    <span>Salir</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</aside>