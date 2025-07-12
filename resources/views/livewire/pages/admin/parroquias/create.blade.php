<?php

use Livewire\Volt\Component;
use App\Models\Municipio;
use App\Models\Estado;
use App\Models\Parroquia;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Crear Parroquia');
    }
    
    public $estado_id = '';
    public $municipio_id = '';
    public $nombre_parroquia = '';
    public $estados;
    public $municipios = [];
    public $readonly = false;

    public function mount()
    {
        $this->estados = Estado::orderBy('estado')->get();
        $this->municipios = collect();
    }

    #[\Livewire\Attributes\On('estado-updated')]
    public function updatedEstadoId()
    {
        $this->municipio_id = ''; // Reset municipio selection

        if (!empty($this->estado_id)) {
            $this->municipios = Municipio::where('estado_id', $this->estado_id)->orderBy('municipio')->get();
        } else {
            $this->municipios = collect();
        }
    }

    public function rules()
    {
        return [
            'estado_id' => 'required|exists:estados,id',
            'municipio_id' => 'required|exists:municipios,id',
            'nombre_parroquia' => 'required|string|max:255|unique:parroquias,parroquia',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        try {
            Parroquia::create([
                'municipio_id' => $validated['municipio_id'],
                'parroquia' => $validated['nombre_parroquia'],
            ]);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Parroquia creada',
                'text' => 'La parroquia se ha creado correctamente',
            ]);

            $this->redirect(route('admin.parroquias.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear la parroquia',
            ]);
        }
    }

    public function cancel()
    {
        $this->redirect(route('admin.parroquias.index'), navigate: true);
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
                'name' => 'Parroquias',
                'route' => route('admin.parroquias.index'),
            ],
            [
                'name' => 'Crear',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Crear Parroquia
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Complete el formulario para crear una nueva parroquia.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600 mt-4"></div>

                @include('livewire.pages.admin.parroquias.partials.form')

                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Guardar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </div>
        </form>
    </x-card>
</x-container>
</div>
