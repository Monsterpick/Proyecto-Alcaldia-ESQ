<?php

use Livewire\Volt\Component;
use App\Models\Supplier;
use App\Models\Identity;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Editar Proveedor');
    }

    public $supplier;
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

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'document_number' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        $this->supplier->update([
            'name' => $this->name,
            'document_number' => $this->document_number,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Proveedor actualizado',
            'text' => 'El proveedor se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.suppliers.index'), navigate: true);
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
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información del Proveedor
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información del proveedor.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.suppliers.partials.form', ['showForm' => true, 'editForm' => true])

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
