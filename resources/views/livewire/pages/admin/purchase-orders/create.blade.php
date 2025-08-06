<?php

use Livewire\Volt\Component;
use App\Models\PurchaseOrder;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Orden de Compra');
    }

    public function cancel()
    {
        $this->redirect(route('admin.purchase-orders.index'), navigate: true);
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
                'name' => 'Ordenes de Compra',
                'route' => route('admin.purchase-orders.index'),
            ],
            [
                'name' => 'Crear Orden de Compra',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
                

                <livewire:admin.purchaseordercreate />


    </x-container>
</div>
