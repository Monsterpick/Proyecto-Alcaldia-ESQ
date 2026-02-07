<?php

use Livewire\Volt\Component;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Configuración');
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
                'name' => 'Moneda',
            ],
        ]" />
    </x-slot>
    

    <x-container class="w-full px-4">
        <x-card>
            
                <livewire:settings.currencysettings />

        </x-card>
    </x-container>
</div>
