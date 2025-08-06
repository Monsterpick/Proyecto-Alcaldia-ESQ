<?php

use Livewire\Volt\Component;
use App\Models\Customer;
use App\Models\Identity;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Cliente');
    }

    public $name;
    public $document_number;
    public $phone;
    public $email;
    public $address;
    public $identity_id;
    public $identity_type = [];

    public function mount()
    {
        $this->name = '';
        $this->document_number = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->identity_id = '';
        $this->identity_type = Identity::orderBy('name')->get();
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required|max:255|min:3',
            'document_number' => 'required|max:255|min:3|unique:customers,document_number',
            'phone' => 'max:255|min:3',
            'email' => 'email|max:255|min:3',
            'address' => 'max:255|min:3',
            'identity_id' => 'required|exists:identities,id',
        ]);

        $customer = Customer::create([
            'name' => $this->name,
            'document_number' => $this->document_number,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'identity_id' => $this->identity_id,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cliente creado',
            'text' => 'El cliente se ha creado correctamente',
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
                'name' => 'Crear Cliente',
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
                    Registre la información del cliente.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.customers.partials.form', ['showForm' => true, 'editForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Guardar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>
