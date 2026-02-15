<?php

use App\Livewire\Actions\Logout;
use App\Models\Setting;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function irAPaginaInicio(bool $cerrarSesion, Logout $logout): void
    {
        if ($cerrarSesion) {
            $logout();
        }
        // navigate: false para recarga completa; así el Dashboard redirige al panel normalmente
        $this->redirect('/', navigate: false);
    }
}; ?>

@php
    $navColors = cache()->remember('app_colors', 60, fn() => [
        'primary' => Setting::get('color_primary', '#5C0A1E'),
        'secondary' => Setting::get('color_secondary', '#7A1232'),
    ]);

    // Cargar datos institucionales desde Settings (con cache)
    $branding = cache()->remember('app_branding', 60, fn() => [
        'name' => Setting::get('name', 'Sistema Web de Gestión'),
        'description' => Setting::get('description', 'Alcaldía del Municipio Escuque'),
        'logo' => Setting::get('logo', ''),
    ]);
    $brandLogo = !empty($branding['logo']) ? asset('storage/' . $branding['logo']) : asset('logo-alcaldia-escuque.png');
@endphp

<nav class="fixed top-0 z-50 w-full backdrop-blur-md border-b border-white/20 dark:border-gray-700 shadow-lg"
     data-color-primary="{{ $navColors['primary'] }}"
     data-color-secondary="{{ $navColors['secondary'] }}"
     style="background: linear-gradient(to right, {{ $navColors['primary'] }}, {{ $navColors['secondary'] }}, {{ $navColors['primary'] }});"
     x-bind:style="darkMode
        ? 'background: linear-gradient(to right, #111827, #1f2937, #374151);'
        : 'background: linear-gradient(to right, ' + ($el.dataset.colorPrimary || '{{ $navColors['primary'] }}') + ', ' + ($el.dataset.colorSecondary || '{{ $navColors['secondary'] }}') + ', ' + ($el.dataset.colorPrimary || '{{ $navColors['primary'] }}') + ');'">
    <div class="px-3 sm:px-4 md:px-5 lg:px-6 py-2 sm:py-3">
        <div class="flex items-center justify-between w-full gap-2 sm:gap-3 md:gap-4">

            <!-- IZQUIERDA: Hamburguesa + Logo + Nombre -->
            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-shrink-0">

                <!-- Botón hamburguesa mobile -->
                <button
                    x-on:click="sidebarOpen = !sidebarOpen"
                    class="inline-flex items-center justify-center p-1.5 sm:p-2 text-white rounded-lg sm:hidden hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/40 transition-all duration-300"
                >
                    <i class="fa-solid fa-bars text-base sm:text-lg"></i>
                </button>

                <!-- Logo + Título -->
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2 sm:gap-3 group flex-shrink-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 md:w-11 md:h-11 flex items-center justify-center overflow-hidden rounded-full bg-white/15 p-1">
                        <img
                            src="{{ $brandLogo }}"
                            alt="{{ $branding['name'] }}"
                            class="logo-img w-full h-full object-contain"
                            style="filter: brightness(0) invert(1);"
                        >
                    </div>

                    <!-- Título dinámico desde Settings -->
                    <div class="hidden sm:flex items-center">
                        <span class="logo-text font-extrabold text-white text-base md:text-lg lg:text-xl group-hover:text-rose-100 transition-all duration-300 drop-shadow-[0_2px_4px_rgba(0,0,0,0.3)] leading-snug">
                            {{ $branding['name'] }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- DERECHA: Theme switcher + Usuario -->
            <div class="flex items-center gap-1 sm:gap-2 md:gap-3 ml-auto flex-shrink-0">
                <!-- Ir a página de inicio -->
                @if(Auth::check())
                <button
                    type="button"
                    x-data
                    x-on:click="
                        Swal.fire({
                            title: 'Ir a página de inicio',
                            html: 'Irás a la página de inicio. Puedes <strong>cerrar sesión</strong> o <strong>continuar con la sesión activa</strong>.',
                            icon: 'info',
                            showCancelButton: true,
                            showDenyButton: true,
                            confirmButtonText: 'Continuar sin cerrar sesión',
                            denyButtonText: 'Cerrar sesión e ir',
                            confirmButtonColor: '#2563eb',
                            denyButtonColor: '#dc2626',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.irAPaginaInicio(false);
                            } else if (result.isDenied) {
                                $wire.irAPaginaInicio(true);
                            }
                        });
                    "
                    class="text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/30 rounded-lg text-sm p-2 sm:p-2.5 transition-all duration-300"
                    title="Ir a página de inicio">
                    <i class="fa-solid fa-house w-5 h-5"></i>
                </button>
                @endif
                <!-- Theme switcher -->
                <button
                    @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    type="button"
                    class="text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/30 rounded-lg text-sm p-2 sm:p-2.5 transition-all duration-300"
                >
                    <svg x-show="!darkMode" x-cloak class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Dropdown de usuario -->
                @if(Auth::check())
                <div x-data="{ userMenuOpen: false }" class="relative">
                    <button @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center gap-2 text-white hover:bg-white/10 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 transition-all duration-300 text-xs sm:text-sm font-medium">
                        <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-full bg-white/20 text-white font-semibold text-xs sm:text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden md:inline">{{ Auth::user()->name }} {{ Auth::user()->last_name ?? '' }}</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }"></i>
                    </button>

                    <!-- Menu desplegable -->
                    <div x-show="userMenuOpen"
                         @click.outside="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-xl shadow-xl border z-50 overflow-hidden"
                         style="background-color: var(--color-bg-primary); border-color: var(--color-border-primary);"
                         x-cloak>
                        <!-- Info usuario -->
                        <div class="px-4 py-3 border-b" style="border-color: var(--color-border-primary); background-color: var(--color-bg-secondary);">
                            <p class="text-sm font-semibold truncate" style="color: var(--color-text-primary);">
                                {{ Auth::user()->name }} {{ Auth::user()->last_name ?? '' }}
                            </p>
                            <p class="text-xs truncate mt-0.5" style="color: var(--color-text-tertiary);">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('admin.settings.profile') }}" wire:navigate
                               class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors"
                               style="color: var(--color-text-secondary);"
                               onmouseover="this.style.backgroundColor='var(--color-bg-hover)'; this.style.color='var(--color-blue-600)'"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-text-secondary)'">
                                <i class="fa-solid fa-user w-4 text-center"></i>
                                <span>Mi Perfil</span>
                            </a>
                            @can('view-super-admin-config')
                            <a href="{{ route('admin.settings.general') }}" wire:navigate
                               class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors"
                               style="color: var(--color-text-secondary);"
                               onmouseover="this.style.backgroundColor='var(--color-bg-hover)'; this.style.color='var(--color-blue-600)'"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-text-secondary)'">
                                <i class="fa-solid fa-gear w-4 text-center"></i>
                                <span>Config General</span>
                            </a>
                            @endcan
                        </div>

                        <div class="border-t" style="border-color: var(--color-border-primary);">
                            <button wire:click="logout"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm w-full transition-colors"
                                style="color: var(--color-red-600);"
                                onmouseover="this.style.backgroundColor='var(--color-red-50)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</nav>

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">

<style>
    .logo-text {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        letter-spacing: 0.02em;
    }
</style>
@endpush
