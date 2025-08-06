<?php

use Livewire\Volt\Component;
use App\Models\Customer;
use Illuminate\View\View;
use App\Models\Identity;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Cliente');
    }

    public $customer;
    public $name;
    public $document_number;
    public $phone;
    public $email;
    public $address;
    public $identity_id;
    public $identity_type = [];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->name = $customer->name;
        $this->document_number = $customer->document_number;
        $this->phone = $customer->phone;
        $this->email = $customer->email;
        $this->address = $customer->address;
        $this->identity_id = $customer->identity_id;
        $this->identity_type = Identity::orderBy('name')->get();
    }

    public function cancel()
    {
        $this->redirect(route('admin.customers.index'), navigate: true);
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
                'name' => 'Clientes',
                'route' => route('admin.customers.index'),
            ],
            [
                'name' => $this->customer->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <h1 class="text-2xl font-bold">
                Información del Cliente
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                Muestra la información del cliente.
            </p>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>

            
            @include('livewire.pages.admin.customers.partials.form', ['showForm' => true, 'editForm' => false])

            <div class="border-t border-gray-200 dark:border-gray-600"></div>
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>
    </x-container>
</div>
