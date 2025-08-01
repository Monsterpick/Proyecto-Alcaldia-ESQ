<?php

use Livewire\Volt\Component;
use App\Models\Speciality;
use Livewire\Attributes\Computed;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Services\AppointmentService;
use App\Models\Appointment;
use App\Events\NotificationSend;

new class extends Component {
    /* Si se recibe el parametro appointmentEdit, se usa para editar una cita, es del tipo appointment y se coloca null porque no siempre se va a enviar */
    public ?Appointment $appointmentEdit = null;

    public $search = [
        'date' => '',
        'hour' => '',
        'speciality_id' => '',
    ];

    public $selectedSchedules = [
        'doctor_id' => '',
        'schedules' => [],
    ];

    public $specialities = [];

    public $availabilies = [];

    public $appointment = [
        'patient_id' => '',
        'doctor_id' => '',
        'date' => '',
        'start_time' => '',
        'end_time' => '',
        'duration' => '',
        'appointment_status_id' => '',
        'reason' => '',
    ];

    #[Computed]
    public function hourBlocks()
    {
        return CarbonPeriod::create(Carbon::createFromTimeString(config('schedule.start_time')), '1 hour', Carbon::createFromTimeString(config('schedule.end_time')))->excludeEndDate();
    }

    #[Computed]
    public function doctorName()
    {
        return $this->appointment['doctor_id'] ? $this->availabilies[$this->appointment['doctor_id']]['doctor']->title . ' ' . $this->availabilies[$this->appointment['doctor_id']]['doctor']->user->name . ' ' . $this->availabilies[$this->appointment['doctor_id']]['doctor']->user->last_name : 'Por definir';
    }

    public function save()
    {
        $this->validate([
            'appointment.patient_id' => 'required|exists:users,id',
            'appointment.doctor_id' => 'required|exists:doctors,id',
            'appointment.reason' => 'required|string|max:255',
            'appointment.date' => 'required|date|after_or_equal:today',
            'appointment.start_time' => 'required|date_format:H:i:s',
            'appointment.end_time' => 'required|date_format:H:i:s',
            'appointment.duration' => 'required|integer',
            'appointment.appointment_status_id' => 'required|exists:appointment_statuses,id',
        ]);

        if($this->appointmentEdit)
        {
            $this->appointmentEdit->update($this->appointment);

            $this->dispatch('swal', [
                'title' => 'Cita actualizada correctamente',
                'text' => 'La cita ha sido actualizada correctamente',
                'icon' => 'success',
            ]);

            $this->searchAvailability( new AppointmentService());

            return;
        }

        Appointment::create($this->appointment)
            ->consultation()->create([]);

        session()->flash('swal', [
            'title' => 'Cita agendada correctamente',
            'text' => 'La cita ha sido agendada correctamente',
            'icon' => 'success',
        ]);

        /* Se emite el evento para que se envie la notificación */
        NotificationSend::dispatch();

        return $this->redirect(route('admin.appointments.index'), navigate: true);
        
    }

    public function updated($property, $value)
    {
        if ($property === 'selectedSchedules') {
            $this->fillAppointment($value);
        }
    }

    public function mount()
    {
        $this->specialities = Speciality::all();
        //Si la hora es mayor o igual a 12, se agrega un día a la fecha, de lo contrario se deja la fecha actual
        $this->search['date'] = now()->hour >= 12 ? now()->addDay()->format('Y-m-d') : now()->format('Y-m-d');

        if ($this->appointmentEdit) {
            $this->appointment['patient_id'] = $this->appointmentEdit->patient_id;
        }
    }

    public function searchAvailability(AppointmentService $service)
    {
        $this->validate([
            'search.date' => 'required|date|after_or_equal:today',
            'search.hour' => ['required', 'date_format:H:i:s', Rule::when($this->search['date'] === now()->format('Y-m-d'), ['after_or_equal:' . now()->format('H:i:s')])],
        ]);

        $this->appointment['date'] = $this->search['date'];

        //Buscar disponibilidad, con los tres puntos estructuramos el array para que se pase como parametros
        $this->availabilies = $service->searchAvailability(...$this->search);
    }

    public function fillAppointment($selectedSchedules)
    {
        $schedules = collect($selectedSchedules['schedules'])
            ->sort()
            ->values()
            ->map(function ($schedule) {
                return Carbon::parse($schedule)->format('H:i:s');
            });

        if ($schedules->count()) {
            $this->appointment['doctor_id'] = $selectedSchedules['doctor_id'];
            $this->appointment['start_time'] = $schedules->first();
            $this->appointment['end_time'] = Carbon::parse($schedules->last())->addMinutes(config('schedule.appointment_duration'))->format('H:i:s');
            $this->appointment['duration'] = $schedules->count() * config('schedule.appointment_duration');
            $this->appointment['appointment_status_id'] = config('schedule.appointment_status_id');

            return;
        }

        $this->appointment['doctor_id'] = '';
        $this->appointment['start_time'] = '';
        $this->appointment['end_time'] = '';
        $this->appointment['duration'] = '';
        $this->appointment['appointment_status_id'] = '';
    }
}; ?>

<div x-data="data()">

    <x-card class="mb-8">

        <p class="text-xl font-semibold mb-1 text-slate-800 dark:text-gray-200 py-4">
            Buscar disponibilidad de citas.
        </p>
        <p class="mb-4">
            Encuentra el horario perfecto para tu cita.
        </p>
        <div class="border-t border-gray-200 dark:border-gray-600 mb-4"></div>
        <div class="grid lg:grid-cols-4 gap-4">

            <x-input label="Fecha" id="date" class="block w-full" type="date" wire:model="search.date"
                placeholder="Selecciona una fecha" />

            <x-select label="Hora" wire:model="search.hour" placeholder="Selecciona una hora">
                @foreach ($this->hourBlocks as $hourBlock)
                    <x-select.option :value="$hourBlock->format('H:i:s')" :label="$hourBlock->format('H:i:s') .
                        ' - ' .
                        $hourBlock->copy()->addMinutes(60)->format('H:i:s')" />
                @endforeach
            </x-select>

            <x-select label="Especialidad" wire:model="search.speciality_id" placeholder="Selecciona una especialidad">
                @foreach ($this->specialities as $speciality)
                    <x-select.option :value="$speciality->id" :label="$speciality->name" />
                @endforeach
            </x-select>

            <div class="lg:pt-6.5">
                <x-button
                    wire:click="searchAvailability" 
                    icon="magnifying-glass" 
                    class="w-full" 
                    info
                    spinner="searchAvailability"
                    :disabled="$appointmentEdit && !$appointmentEdit->isEditable()"
                    >
                    Buscar disponibilidad
                </x-button>

            </div>
        </div>


    </x-card>

    @if ($appointment['date'])

        @if (count($availabilies))

            <div class="grid lg:grid-cols-3 gap-4 lg:gap-8">
                <div class="col-span-2">

                    @foreach ($availabilies as $availability)
                        <x-card class="mb-4">
                            <div class="flex items-center space-x-6">
                                <img src="{{ Storage::url($availability['doctor']->user->image_url) }}"
                                    alt={{ $availability['doctor']->title }} {{ $availability['doctor']->user->name }}
                                    {{ $availability['doctor']->user->last_name }}" class="w-12 h-12 rounded-full">

                                <div class="">
                                    <p class="text-xl font-bold text-slate-800 dark:text-gray-200 mb-2">
                                        {{ $availability['doctor']->title }} {{ $availability['doctor']->user->name }}
                                        {{ $availability['doctor']->user->last_name }}
                                    </p>
                                    <x-badge outline info
                                        label="{{ $availability['doctor']->speciality?->name ?? 'Sin especialidad' }}" />
                                </div>
                            </div>

                            <hr class="my-5">
                            <div class="">
                                <p class="text-sm text-slate-800 dark:text-gray-200 mb-2 font-semibold">
                                    Horarios disponibles:
                                </p>

                                <ul class="grid md:grid-cols-2 lg:grid-cols-4 gap-2">
                                    @foreach ($availability['schedules'] as $schedule)
                                        <li class="text-sm text-slate-800 dark:text-gray-200">
                                            <x-button 
                                            :disabled="$schedule['disabled']"
                                            :color="$schedule['disabled'] ? 'gray' : 'info'"
                                                x-on:click="selectSchedule({{ $availability['doctor']->id }}, '{{ $schedule['start_time'] }}')"
                                                x-bind:class="selectedSchedules.doctor_id === {{ $availability['doctor']->id }} &&
                                                    selectedSchedules.schedules.includes(
                                                        '{{ $schedule['start_time'] }}') ? 'opacity-50' : ''"
                                                class="w-full" info>
                                                {{ $schedule['start_time'] }}
                                            </x-button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </x-card>
                    @endforeach
                </div>

                <div class="col-span-1">
                    {{-- @json($selectedSchedules) --}}

                    <x-card>
                        <p class="text-xl font-semibold mb-1 text-slate-800 dark:text-gray-200 py-4">
                            Resumen de la cita
                        </p>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">Asignación:</span>
                                <span
                                    class="font-semibold text-slate-800 dark:text-gray-200">{{ $this->doctorName }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-semibold">Fecha:</span>
                                <span
                                    class="font-semibold text-slate-800 dark:text-gray-200">{{ $appointment['date'] }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-semibold">Horario:</span>
                                <span class="font-semibold text-slate-800 dark:text-gray-200">
                                    @if ($appointment['duration'])
                                        {{ $appointment['start_time'] }} - {{ $appointment['end_time'] }}
                                    @else
                                        Por definir
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-semibold">Duración:</span>
                                <span class="font-semibold text-slate-800 dark:text-gray-200">
                                    {{ $appointment['duration'] ?: 0 }} minutos
                                </span>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-600 my-4"></div>

                            <div class="space-y-6">
                                <x-select label="Paciente" placeholder="Selecciona un paciente" :async-data="route('api.patients.index')"
                                    wire:model="appointment.patient_id" option-label="name" option-value="id"
                                    :disabled="$appointmentEdit ? true : false" />
                            </div>

                            <x-textarea label="Motivo de la cita" wire:model="appointment.reason" 
                                placeholder="Escribe el motivo de la cita" />

                            <x-button wire:click="save" class="w-full" info spinner="save" icon="check">Agendar
                                cita</x-button>
                        </div>
                    </x-card>
                </div>

            </div>
        @else
            <x-card>
                <p class="text-xl font-semibold mb-1 text-slate-800 dark:text-gray-200 py-4">
                    No hay disponibilidad para la fecha y hora seleccionada.
                </p>
            </x-card>
        @endif

    @endif

    @push('scripts')
        <script>
            function data() {
                return {
                    selectedSchedules: @entangle('selectedSchedules').live,
                    /* Se usa live para que se actualice el estado del componente automaticamente y livewire detecte el cambio */
                    selectSchedule(doctorId, schedule) {

                        if (this.selectedSchedules.doctor_id !== doctorId) {
                            this.selectedSchedules = {
                                doctor_id: doctorId,
                                schedules: [schedule],
                            }
                            return;
                        }

                        /* this.selectedSchedule.doctor_id = doctorId;
                        this.selectedSchedule.schedules.push(schedule); */
                        let currentSchedules = this.selectedSchedules.schedules;
                        let newSchedules = [];

                        if (currentSchedules.includes(schedule)) {
                            newSchedules = currentSchedules.filter(s => s !== schedule);
                        } else {
                            newSchedules = [...currentSchedules, schedule];
                        }

                        if (this.isContiguous(newSchedules)) {
                            this.selectedSchedules = {
                                doctor_id: doctorId,
                                schedules: newSchedules,
                            };
                        } else {
                            this.selectedSchedules = {
                                doctor_id: doctorId,
                                schedules: [schedule],
                            };
                        }

                        //this.isContiguous(newSchedules);


                    },

                    isContiguous(schedules) {
                        if (schedules.length < 2) {
                            return true;
                        }

                        let sortedSchedules = schedules.sort();

                        for (let i = 0; i < sortedSchedules.length - 1; i++) {
                            let currentTime = sortedSchedules[i];
                            let nextTime = sortedSchedules[i + 1];

                            if (this.calculateNextTime(currentTime) !== nextTime) {
                                return false;
                            }
                        }

                        return true;
                    },

                    /* Determina cual es el siguiente elemento */
                    calculateNextTime(time) {
                        let date = new Date(`1970-01-01T${time}`);

                        let duration = parseInt("{{ config('schedule.appointment_duration') }}");


                        date.setMinutes(date.getMinutes() + duration);
                        return date.toTimeString().split(' ')[0];
                    }
                }
            }
        </script>
    @endpush
</div>
