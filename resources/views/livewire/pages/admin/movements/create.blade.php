<?php

use Livewire\Volt\Component;
use App\Models\Sale;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Venta');
    }

    public function cancel()
    {
        $this->redirect(route('admin.sales.index'), navigate: true);
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
                'name' => 'Ventas',
                'route' => route('admin.sales.index'),
            ],
            [
                'name' => 'Nuevo',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">

        <livewire:admin.salecreate />

    </x-container>
</div>
