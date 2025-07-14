<?php

use Livewire\Volt\Component;
use App\Models\Doctor;
use Livewire\Attributes\Computed;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Models\Schedule;

new class extends Component {
    public Doctor $doctor;
    public $schedule = [];

    public $days = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miercoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sabado',
        0 => 'Domingo',
    ];
    public $start_time = '08:00:00';

    public $apointment_duration = 15;
    public $intervals;

    public function mount()
    {
        $this->days = config('schedule.days');
        $this->apointment_duration = config('schedule.appointment_duration');
        $this->start_time = config('schedule.start_time');

        $this->intervals = 60 / $this->apointment_duration;
        $this->initializeSchedule();
    }

    public function initializeSchedule()
    {
        $schedules = $this->doctor->schedules;

        foreach ($this->hourBlocks as $hourBlock) {
            $period = CarbonPeriod::create($hourBlock->copy(), $this->apointment_duration . ' minutes', $hourBlock->copy()->addHour());

            foreach ($period as $time) {
                /* Por cada periodo de tiempo generado, se compara con lo que esta en la base de datos  */
                foreach ($this->days as $index => $day) {
                    $this->schedule[$index][$time->format('H:i:s')] = $schedules->contains(function ($schedule) use ($index, $time) {
                        if ($schedule->day_of_week == $index && $schedule->start_time->format('H:i:s') == $time->format('H:i:s')) {
                            return true;
                        }

                        return false;
                    });
                }
            }
        }
    }

    /* Aquí se configura el horario de inicio diario y cada cuando se generan los bloques de hora */
    #[Computed]
    public function hourBlocks()
    {
        return CarbonPeriod::create(Carbon::createFromTimeString(config('schedule.start_time')), '1 hour', Carbon::createFromTimeString(config('schedule.end_time')))->excludeEndDate();
    }

    public function save()
    {
        /* Eliminamos los horarios existentes */
        $this->doctor->schedules()->delete();

        foreach ($this->schedule as $day_of_week => $intervals) {
            foreach ($intervals as $start_time => $isChecked) {
                if ($isChecked) {
                    Schedule::create([
                        'doctor_id' => $this->doctor->id,
                        'day_of_week' => $day_of_week,
                        'start_time' => $start_time,
                    ]);
                }
            }
        }

        $this->dispatch('swal', [
            'title' => '¡Exito!',
            'icon' => 'success',
            'text' => 'El horario se ha guardado correctamente',
        ]);
    }
}; ?>

<div x-data="data()">
    <x-card>
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold mb-4 dark:text-white text-gray-900">
                Gestor de horarios
            </h1>
            <x-button info label="Guardar horario" icon="check" interaction="positive" wire:click="save" />

        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Día/Hora</th>
                        {{-- Se generan columnas para cada dia de la semana --}}
                        @foreach ($days as $day)
                            <th
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ $day }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    {{-- Accedemos a la propiedad computada hourBlocks para generar las filas de horas --}}
                    @foreach ($this->hourBlocks as $hourBlock)
                        @php
                            $hour = $hourBlock->format('H:i:s');
                        @endphp

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <label>
                                    <input type="checkbox"
                                        x-on:click="toggleFullHourBlock('{{ $hour }}', $el.checked)"
                                        :checked="isFullHourBlockChecked('{{ $hour }}')"
                                        class="h-4 w-4 text-blue-600 dark:text-blue-500 rounded-full">
                                    <span class="ml-2 font-bold text-gray-900 dark:text-white">
                                        {{ $hour }}
                                    </span>
                                </label>
                            </td>
                            {{-- Se generan columnas para cada dia de la semana --}}
                            @foreach ($days as $indexDay => $day)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="space-y-2">
                                        <div class="flex flex-col space-y-2">
                                            <label>
                                                <input type="checkbox"
                                                    x-on:click="toggleHourBlock('{{ $indexDay }}', '{{ $hour }}', $el.checked)"
                                                    {{-- $el.checked me da el valor actual del checkbox --}}
                                                    :checked="isHourBlockChecked('{{ $indexDay }}', '{{ $hour }}')"
                                                    class="h-4 w-4 text-blue-600 dark:text-blue-500 rounded-full">
                                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                                    Todos
                                                </span>
                                            </label>
                                            {{-- Aqui se establece la cantidad de intervalos por hora es decir si las citas duran 15 minutos serian 4 intervalos en una hora --}}
                                            @for ($i = 0; $i < $this->intervals; $i++)
                                                @php
                                                    $startTime = $hourBlock
                                                        ->copy()
                                                        ->addMinutes($i * $this->apointment_duration);
                                                    $endTime = $startTime
                                                        ->copy()
                                                        ->addMinutes($this->apointment_duration);
                                                @endphp
                                                <label>
                                                    <input type="checkbox"
                                                        x-model="schedule['{{ $indexDay }}']['{{ $startTime->format('H:i:s') }}']"
                                                        class="h-4 w-4 text-blue-600 dark:text-blue-500">
                                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $startTime->format('H:i') }} -
                                                        {{ $endTime->format('H:i') }}
                                                    </span>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                </td>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                    @endforeach

                </tbody>
            </table>

        </div>

        <div class="flex justify-end mt-4">
            <x-button info label="Guardar horario" icon="check" interaction="positive" wire:click="save" />
        </div>
    </x-card>

    @push('scripts')
        <script>
            /* Llamamos a la funcion data() para enlazarla con el x-data de alpinejs al comienzo de la pagina */
            function data() {
                return {
                    /* Enlaza la propiedad schedule con el componente livewire */
                    schedule: @entangle('schedule'),
                    apointment_duration: @entangle('apointment_duration'),
                    intervals: @entangle('intervals'),
                    days: @entangle('days'),

                    toggleHourBlock(indexDay, hourBlock, checked) {
                        /* Transformamos la hora del toggle a formato fecha */
                        let hour = new Date(`1970-01-01T${hourBlock}`);

                        for ($i = 0; $i < this.intervals; $i++) {
                            let startTime = new Date(hour.getTime() + ($i * this.apointment_duration * 60000));
                            let formattedStartTime = startTime.toTimeString().split(' ')[0];

                            this.schedule[indexDay][formattedStartTime] = checked;
                        }
                    },

                    isHourBlockChecked(indexDay, hourBlock) {
                        /* Transformamos la hora del toggle a formato fecha */
                        let hour = new Date(`1970-01-01T${hourBlock}`);

                        for ($i = 0; $i < this.intervals; $i++) {
                            let startTime = new Date(hour.getTime() + ($i * this.apointment_duration * 60000));
                            let formattedStartTime = startTime.toTimeString().split(' ')[0];

                            if (!this.schedule[indexDay][formattedStartTime]) {
                                return false;
                            }
                        }
                        return true;
                    },

                    toggleFullHourBlock(hourBlock, checked) {
                        Object.keys(this.days).forEach(indexDay => {
                            this.toggleHourBlock(indexDay, hourBlock, checked);
                        });
                    },

                    isFullHourBlockChecked(hourBlock) {
                        return Object.keys(this.days).every(indexDay => {
                            return this.isHourBlockChecked(indexDay, hourBlock);
                        });
                    }
                }
            }
        </script>
    @endpush
</div>
