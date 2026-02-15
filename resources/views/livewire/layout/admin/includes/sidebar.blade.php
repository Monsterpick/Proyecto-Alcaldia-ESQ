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
            'permission' => 'view-beneficiary',
        ],
        [
            'name' => 'Beneficiarios',
            'icon' => 'fa-solid fa-user-group',
            'url' => route('admin.beneficiaries.index'),
            'active' => request()->routeIs('admin.beneficiaries.*'),
            'permission' => 'view-beneficiary',
        ],
        [
            'header' => 'Directores',
            'permission' => 'view-director',
        ],
        [
            'name' => 'Directores',
            'icon' => 'fa-solid fa-user-tie',
            'href' => '#',
            'active' => request()->routeIs([
                'admin.departamentos.*',
                'admin.directores.*',
                'admin.solicitudes.*',
            ]),
            'id_submenu' => 'submenu-directores',
            'permission' => 'view-director',
            'submenu' => [
                [
                    'name' => 'Departamentos/Directores',
                    'icon' => 'fa-solid fa-building-user',
                    'url' => route('admin.departamentos.index'),
                    'active' => request()->routeIs('admin.departamentos.*'),
                    'permission' => 'view-departamento',
                ],
                [
                    'name' => 'Listado de Directores',
                    'icon' => 'fa-solid fa-address-card',
                    'url' => route('admin.directores.index'),
                    'active' => request()->routeIs('admin.directores.index'),
                    'permission' => 'view-director',
                ],
                [
                    'name' => 'Solicitudes de Alcaldía Digital',
                    'icon' => 'fa-solid fa-file-lines',
                    'url' => route('admin.solicitudes.index'),
                    'active' => request()->routeIs('admin.solicitudes.*'),
                    'permission' => 'view-solicitud',
                ],
            ],
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
            'permission' => 'view-report',
        ],
        [
            'name' => 'Reportes de Entregas',
            'icon' => 'fa-solid fa-file-alt',
            'url' => route('admin.reports.index'),
            'active' => request()->routeIs('admin.reports.*'),
            'permission' => 'view-report',
        ],
        [
            'name' => 'Mapa de Geolocalización',
            'icon' => 'fa-solid fa-map-location-dot',
            'url' => route('admin.map.index'),
            'active' => request()->routeIs('admin.map.*'),
            'permission' => 'view-map',
        ],
        [
            'name' => 'Registro de Actividades',
            'icon' => 'fa-solid fa-clipboard-list',
            'url' => route('admin.activity-logs.index'),
            'active' => request()->routeIs('admin.activity-logs.*'),
            'permission' => 'view-activitylog',
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
            'name' => 'Config General',
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
            'permission' => 'view-super-admin-config',
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
                    'name' => 'Colores Institucional',
                    'icon' => 'fa-solid fa-palette',
                    'url' => route('admin.settings.colors'),
                    'active' => request()->routeIs('admin.settings.colors'),
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

    // Analista: solo Directores (Departamentos, Listado, Solicitudes). Sin Panel/estadísticas.
    if (Auth::check() && Auth::user()->hasRole('Analista')) {
        $links = array_values(array_filter($links, function ($link) {
            if (isset($link['header'])) {
                return ($link['header'] ?? '') === 'Directores';
            }
            return ($link['permission'] ?? '') === 'view-director';
        }));
    }

    // Director: solo Solicitudes de su departamento y Perfil
    if (Auth::check() && Auth::user()->hasRole('Director')) {
        $links = [
            [
                'name' => 'Solicitudes de mi departamento',
                'icon' => 'fa-solid fa-file-lines',
                'url' => route('admin.solicitudes.index'),
                'active' => request()->routeIs('admin.solicitudes.*'),
                'permission' => 'view-solicitud',
            ],
            [
                'name' => 'Perfil',
                'icon' => 'fa-solid fa-user',
                'url' => route('admin.settings.profile'),
                'active' => request()->routeIs('admin.settings.profile'),
                'permission' => 'profile-setting',
            ],
        ];
    }

    // Operador: Panel, Beneficiarios, Inventario, Movimientos, Reportes, Mapa (no Config, Direcciones, Usuarios)
    if (Auth::check() && Auth::user()->hasRole('Operador')) {
        $operadorPerms = ['view-dashboard', 'view-beneficiary', 'view-inventory', 'view-movement', 'view-report', 'view-map'];
        $links = array_values(array_filter($links, function ($link) use ($operadorPerms) {
            $perm = $link['permission'] ?? '';
            if (isset($link['header'])) {
                return in_array($perm, $operadorPerms);
            }
            return in_array($perm, $operadorPerms);
        }));
    }

@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-[100dvh] pt-[3.5rem] sm:pt-[3.75rem] transition-transform -translate-x-full sm:translate-x-0"
    style="background-color: var(--color-bg-primary); border-right: 1px solid var(--color-border-primary);"
    :class="{
        'translate-x-0 ease-out': sidebarOpen,
        '-translate-x-full ease-in': !sidebarOpen
    }" aria-label="Sidebar">

    <!-- Contenido Principal del Sidebar -->
    <div class="flex flex-col overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-transparent" style="height: calc(100vh - 3.75rem);">
        @if (Auth::check() && Auth::user()->hasRole('Director') && !Auth::user()->password_changed_at)
            <a href="{{ route('admin.settings.profile') }}" wire:navigate
               class="mx-3 mt-3 mb-2 flex items-center gap-2 rounded-lg border border-amber-300 bg-amber-50 px-3 py-2.5 text-left text-sm font-medium text-amber-800 transition hover:bg-amber-100 dark:border-amber-600 dark:bg-amber-900/30 dark:text-amber-200 dark:hover:bg-amber-900/50">
                <i class="fa-solid fa-shield-halved shrink-0"></i>
                <span>No has actualizado tu contraseña <span class="text-amber-600 dark:text-amber-400">(recomendado)</span></span>
            </a>
        @endif
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

</aside>