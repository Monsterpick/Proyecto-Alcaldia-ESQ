<?php

use Livewire\Volt\Component;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Permisos');
    }
    
}; ?>

<div>

    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Permisos',
            ],
        ]" />
    </x-slot>

    @can('create-permission')
        <x-slot name="action">
            <x-button info href="{{ route('admin.permissions.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:permission-table />

    </x-container>

    @push('scripts')
    @endpush
</div>
