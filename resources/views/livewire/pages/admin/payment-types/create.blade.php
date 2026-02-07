<?php
use Livewire\Volt\Component;
use App\Models\PaymentType;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Crear Tipo de pago');
    }

    public $name;
    public $description;
    public $is_active;

    public function mount()
    {
        $this->is_active = true;
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'description' => 'required',
            'is_active' => 'required|boolean'
        ]);

        $paymentType = PaymentType::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Tipo de pago creado',
            'text' => 'El tipo de pago se ha creado correctamente',
        ]);

        $this->redirect(route('admin.payment-types.index'), navigate: true);
    }

    public function cancel()
    {
        $this->redirect(route('admin.payment-types.index'), navigate: true);
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
                'name' => 'Tipos de pago',
                'route' => route('admin.payment-types.index'),
            ],
            [
                'name' => 'Crear Tipo de pago',
            ],
        ]" />
    </x-slot>
    

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit="save">
                <h1 class="text-2xl font-bold">
                    Información del Tipo de Pago
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Registre la información del tipo de pago.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                    @include('livewire.pages.admin.payment-types.partials.form', ['showForm' => true, 'editForm' => false])

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
