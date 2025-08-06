<?php

use Livewire\Volt\Component;
use App\Models\Customer;
use App\Models\Identity;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Editar Cliente');
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
        $this->description = $customer->description;
        $this->document_number = $customer->document_number;
        $this->phone = $customer->phone;
        $this->email = $customer->email;
        $this->address = $customer->address;
        $this->identity_id = $customer->identity_id;
        $this->identity_type = Identity::orderBy('name')->get();
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'document_number' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        $this->customer->update([
            'name' => $this->name,
            'document_number' => $this->document_number,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cliente actualizado',
            'text' => 'El cliente se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.customers.index'), navigate: true);
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
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Cliente
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información del cliente.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.customers.partials.form', ['showForm' => true, 'editForm' => true])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Actualizar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>
