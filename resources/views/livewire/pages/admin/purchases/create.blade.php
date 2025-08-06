<?php

use Livewire\Volt\Component;
use App\Models\Purchase;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Compra');
    }

    public function cancel()
    {
        $this->redirect(route('admin.purchases.index'), navigate: true);
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
                'name' => 'Compras',
                'route' => route('admin.purchases.index'),
            ],
            [
                'name' => 'Nuevo',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">

        <livewire:admin.purchasecreate />

    </x-container>
</div>
