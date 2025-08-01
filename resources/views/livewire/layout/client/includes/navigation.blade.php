<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button x-on:click="sidebarOpen = !sidebarOpen" data-drawer-target="logo-sidebar"
                    data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="{{ ('/') }}" class="flex ms-2 md:me-24" target="_blank">
                    <img src="{{ asset(path: 'logo_nexa_18.png') }}" class="h-8 me-3" alt="Nevora Logo" />
                    <span
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white"></span>
                </a>
            </div>

            <div class="flex items-center">
                <div class="flex items-center ms-3">

                    <livewire:components.teme-switcher />

                    @if(Auth::check())
                    <x-dropdown>
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <x-button label="{{ Auth::user()->name }} {{ Auth::user()->last_name }}" flat slate
                                    right-icon="chevron-down" />
                            </span>
                        </x-slot>
                        <x-dropdown.header label="Configuración">
                            <x-dropdown.item icon="user" label="Mi Perfil" :href="route('admin.profile.index')" wire:navigate />
                        </x-dropdown.header>
                        <x-dropdown.item separator label="Cerrar Sesión" wire:click="logout" icon="arrow-right-start-on-rectangle" />
                    </x-dropdown>
                    @endif
                </div>
            </div>
        </div>
    </div>

</nav>