<?php
use Livewire\Volt\Component;
use App\Models\Municipio;
use App\Models\Estado;
use App\Models\Parroquia;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Editar Parroquia');
    }

    public $parroquia;
    public $estado_id = '';
    public $municipio_id = '';
    public $nombre_parroquia = '';
    public $estados;
    public $municipios = [];

    public function mount(Parroquia $parroquia)
    {
        // Cargamos la parroquia con sus relaciones
        $this->parroquia = Parroquia::with(['municipio.estado'])->find($parroquia->id);
        
        if (!$this->parroquia || !$this->parroquia->municipio) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se encontró la información de la parroquia',
            ]);
            return $this->redirect(route('admin.parroquias.index'), navigate: true);
        }

        $this->estados = Estado::all();
        $this->estado_id = $this->parroquia->municipio->estado_id;
        $this->municipios = Municipio::where('estado_id', $this->estado_id)->get();
        $this->municipio_id = $this->parroquia->municipio_id;
        $this->nombre_parroquia = $this->parroquia->parroquia;
    }

    public function updatedEstadoId($value)
    {
        if (!empty($value)) {
            $this->municipios = Municipio::where('estado_id', $value)->get();
            $this->municipio_id = ''; // Reset municipio selection
        } else {
            $this->municipios = [];
            $this->municipio_id = '';
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'municipio_id' => 'required|exists:municipios,id',
            'nombre_parroquia' => 'required|string|max:255',
        ]);

        $this->parroquia->update([
            'municipio_id' => $validated['municipio_id'],
            'parroquia' => $validated['nombre_parroquia'],
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Parroquia actualizada',
            'text' => 'La parroquia se ha actualizado correctamente',
        ]);

        $this->redirect(route('admin.parroquias.index'), navigate: true);
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
                'name' => $this->parroquia->parroquia,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit="save">
                <h1 class="text-2xl font-bold">
                    Editar Parroquia
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información de la parroquia.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.parroquias.partials.form', [
                    'showForm' => true, 
                    'editForm' => true,
                    'parroquia' => $nombre_parroquia
                ])

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
