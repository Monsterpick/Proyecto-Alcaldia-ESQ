<?php

use Livewire\Volt\Component;
use App\Models\Quote;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Cotización');
    }

    public function cancel()
    {
        $this->redirect(route('admin.quotes.index'), navigate: true);
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
                'name' => 'Cotizaciones',
                'route' => route('admin.quotes.index'),
            ],
            [
                'name' => 'Crear Cotización',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
                

                <livewire:admin.quotecreate />


    </x-container>
</div>
