<?php

use Livewire\Volt\Component;
use App\Models\Quote;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Cotizaciones');
    }

    public function deleteQuote(Quote $quote)
    {
        if ($quote->inventories->count() > 0) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar la cotización porque tiene inventarios asociados',
            ]);
            return;
        }

        try {
            $quote->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Cotización eliminada',
                'text' => 'La cotización se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-quote-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar la cotización',
            ]);
        }
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
            ],
        ]" />
    </x-slot>

    @can('create-customer')
        <x-slot name="action">
            <x-button info href="{{ route('admin.quotes.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:quote-table />
        
    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(quote_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteQuote', quote_id);
                    }
                });
            }
        </script>
    @endpush
</div>
