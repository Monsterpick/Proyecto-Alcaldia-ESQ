<?php

use Livewire\Volt\Component;
use App\Models\Supplier;
use Illuminate\View\View;
use App\Models\Identity;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Proveedor');
    }

    public $customer;
    public $name;
    public $document_number;
    public $phone;
    public $email;
    public $address;
    public $identity_id;
    public $identity_type = [];

    public function mount(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->name = $supplier->name;
        $this->document_number = $supplier->document_number;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->address = $supplier->address;
        $this->identity_id = $supplier->identity_id;
        $this->identity_type = Identity::orderBy('name')->get();
    }

    public function cancel()
    {
        $this->redirect(route('admin.suppliers.index'), navigate: true);
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
                'name' => 'Proveedores',
                'route' => route('admin.suppliers.index'),
            ],
            [
                'name' => $this->supplier->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <h1 class="text-2xl font-bold">
                Información del Proveedor
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                Muestra la información del proveedor.
            </p>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>

            
            @include('livewire.pages.admin.suppliers.partials.form', ['showForm' => true, 'editForm' => true])

            <div class="border-t border-gray-200 dark:border-gray-600"></div>
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                </div>
            </x-slot>
        </x-card>
    </x-container>
</div>
