<?php

use Livewire\Volt\Component;
use App\Models\Appointment;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Calendario');
    }


}; ?>

@push('styles')
    <style>
        .fc-event {
            cursor: pointer;
        }
    </style>
@endpush
<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Calendario',
            ],
            ]" />
    </x-slot>
    
    @can('create-appointment')
    <x-slot name="action">
        <x-button info href="{{ route('admin.calendar.create') }}" wire:navigate>
            <i class="fa-solid fa-plus"></i>
            Nuevo
        </x-button>
    </x-slot>
    @endcan
    
    <x-container class="w-full px-6">
        <div id='calendar'></div>
    </x-container>

    <x-modal-card title="Cita Médica" name="appointmentModal">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <strong>Fecha y Hora</strong>
                <span id="modal-datetime"></span>
            </div>
            <div class="flex flex-col gap-2">
                <strong>Paciente</strong>
                <span id="modal-patient"></span>
            </div>
            <div class="flex flex-col gap-2">
                <strong>Doctor</strong>
                <span id="modal-doctor"></span>
            </div>
            <div class="flex flex-col gap-2">
                <strong>Estado</strong>
                <span id="modal-status" class=""></span>
            </div>
            <div class="flex justify-end">
                <a id="modal-url" class="w-full text-white px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 transition-colors duration-300" href="" target="_blank">
                    <i class="fa-solid fa-notes-medical"></i>
                    Gestionar Cita
                </a>
            </div>
        </div>
    </x-modal-card>
</div>

@push('scripts')
<script type="module">
    let calendarEl = document.getElementById('calendar');
    let calendar = new Calendar(calendarEl, {
        locale: 'es',
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Dia',
            list: 'Lista'
        },
        allDayText: 'Todo el dia',
        noEventsText: 'No hay elementos para mostrar',
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        slotMinTime: '{{ config('schedule.start_time') }}',
        slotMaxTime: '{{ config('schedule.end_time') }}',
        slotDuration: '{{ config('schedule.appointment_duration_time') }}',
        scrollTime: '{{ date('H:i:s') }}',
        events: {
            url: '{{ route('api.appointments.index') }}',
            method: 'GET',
            failure: function() {
                alert('Hubo un error al cargar los eventos');
            }
        },
        eventClick: function(info) {
            // Formatear la fecha para mostrarla más amigable
            const fecha = info.event.start.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Actualizar el contenido del modal
            document.getElementById('modal-datetime').textContent = fecha;
            document.getElementById('modal-patient').textContent = info.event.extendedProps.patient;
            document.getElementById('modal-doctor').textContent = info.event.extendedProps.doctor;
            document.getElementById('modal-status').textContent = info.event.extendedProps.status;
            document.getElementById('modal-url').href = info.event.extendedProps.url;
            // Abrir el modal
            $openModal('appointmentModal');
        }
    });
    calendar.render();

    // Reinicializar el calendario cuando se navega con Livewire
    /* document.addEventListener('livewire:navigated', () => {
        if (document.getElementById('calendar')) {
            calendar = new Calendar(document.getElementById('calendar'));
            calendar.render();
        }
    }); */
</script>
@endpush