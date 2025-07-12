<?php

use Livewire\Volt\Component;
use App\Models\PaymentOrigin;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

new #[Layout('layouts.tenancy')]
class extends Component {

    public function rendering(View $view)
    {
        $view->title('Origen de pago');
    }

    public $name;
    public $description;
    public $is_active;
    public $paymentOrigin;

    public function mount(PaymentOrigin $paymentOrigin)
    {
        $this->paymentOrigin = $paymentOrigin;
        $this->name = $paymentOrigin->name;
        $this->description = $paymentOrigin->description;
        $this->is_active = $paymentOrigin->is_active;
    }


    public function cancel()
    {
        $this->redirect(route('admin.specialities.index'), navigate: true);
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
                'name' => 'Origenes de pago',
                'route' => route('admin.specialities.index'),
            ],
            [
                'name' => $this->paymentOrigin->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
                <h1 class="text-2xl font-bold">
                    Información del Origen de pago
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Muestra la información del origen de pago.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.specialities.partials.form', ['showForm' => true, 'editForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button slate label="Atras" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
        </x-card>
    </x-container>
</div>
