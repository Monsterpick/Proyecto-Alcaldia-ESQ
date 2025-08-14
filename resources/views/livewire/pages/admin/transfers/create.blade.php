<?php

use Livewire\Volt\Component;
use App\Models\Sale;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Transferencia');
    }

    public function cancel()
    {
        $this->redirect(route('admin.transfers.index'), navigate: true);
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
                'name' => 'Transferencias',
                'route' => route('admin.transfers.index'),
            ],
            [
                'name' => 'Nuevo',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">

        <livewire:admin.transfercreate />

    </x-container>
</div>
