<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
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
                    <img src="{{ asset(path: 'logo_nexa_18.png') }}" class="h-8 me-3" alt="FlowBite Logo" />
                    <span
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white"></span>
                </a>
            </div>
            
            <div class="flex items-center">
                <div class="flex items-center ms-3">

                    <livewire:components.teme-switcher />

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                    {{ Auth::user()->name }} {{ Auth::user()->last_name }} &nbsp;

                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ Auth::user()->roles->pluck('name')->implode(', ') }}
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Administrar Cuenta
                            </div>

                            <x-link :href="route('admin.settings.profile')" wire:navigate>
                                {{ __('Profile') }}
                            </x-link>

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-link>
                                    {{ __('Log Out') }}
                                </x-link>
                            </button>

                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>

</nav>
