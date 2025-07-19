<?php

use Livewire\Volt\Component;
use App\Models\Appointment;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Consultas');
    }

    public $appointment;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
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
                'name' => 'Citas',
                'route' => route('admin.appointments.index'),
            ],
            [
                'name' => 'Consulta',
            ],
        ]" />
    </x-slot>

    @can('create-appointment')
        <x-slot name="action">
            <x-button info href="{{ route('admin.appointments.create') }}" wire:navigate>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>
    @endcan
    <x-container class="w-full px-4">

        <livewire:pages.admin.appointments.consultationmanager :appointment="$appointment" />

    </x-container>

    @push('scripts')
        <script>
            
        </script>
    @endpush
</div>
