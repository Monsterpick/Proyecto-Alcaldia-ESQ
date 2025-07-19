<?php

use Livewire\Volt\Component;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Patient;

new class extends Component {

    public Appointment $appointment;
    public Consultation $consultation;
    public Patient $patient;

    public $previousConsultation;
    public $form = [
        'diagnosis' => '',
        'treatment' => '',
        'notes' => '',
        'prescriptions' => [],
    ];

    public function mount(Appointment $appointment)
    {
        $this->consultation = $appointment->consultation;
        $this->patient = $appointment->patient;

        $this->previousConsultation = Consultation::whereHas('appointment', function ($query) {
            $query->where('patient_id', $this->patient->id);
        })
            ->where('id', '!=', $this->consultation->id)
            ->where('created_at', '<', $this->consultation->created_at)
            ->latest()
            ->get();


        $this->form = [
            'diagnosis' => $this->consultation->diagnosis,
            'treatment' => $this->consultation->treatment,
            'notes' => $this->consultation->notes,
            'prescriptions' => $this->consultation->prescriptions ?? [
                [
                    'medicine' => '',
                    'dosage' => '',
                    'frequency' => '',
                ]
            ],
        ];
    }

    public function save()
    {
        $this->validate([
            'form.diagnosis' => 'required|string|max:255',
            'form.treatment' => 'required|string|max:255',
            'form.notes' => 'nullable|string|max:1000',
            'form.prescriptions' => 'required|array|min:1',
            'form.prescriptions.*.medicine' => 'required|string|max:255',
            'form.prescriptions.*.dosage' => 'required|string|max:255',
            'form.prescriptions.*.frequency' => 'required|string|max:255',
        ]);

        $this->consultation->update($this->form);

        $this->appointment->appointment_status_id = 4;
        $this->appointment->save();

        $this->dispatch('swal', [
            'title' => 'Consulta actualizada correctamente',
            'text' => 'La consulta ha sido actualizada correctamente',
            'icon' => 'success',
        ]);
    }

    /* Esta funcion lo que hace es agregar una nueva prescripcion al array de prescripciones */
    public function addPrescription()
    {
        $this->form['prescriptions'][] = [
            'medicine' => '',
            'dosage' => '',
            'frequency' => '',
        ];
    }

    /* Esta funcion lo que hace es eliminar una prescripcion del array de prescripciones */
    public function removePrescription($index)
    {
        /* Primero se elimina la prescripcion del array de prescripciones */
        unset($this->form['prescriptions'][$index]);
        /* Luego se actualiza el array de prescripciones para que no haya espacios vacios en el array y todos los numeros sean consecutivos */
        $this->form['prescriptions'] = array_values($this->form['prescriptions']);
    }



}; ?>

<div>
    <div class="lg:flex lg:justify-between lg:items-center mb-4">

        <div class="">
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ $appointment->patient->user->name }} {{ $appointment->patient->user->last_name }}
            </p>
            <p class="mb-1 text-sm font-semibold text-gray-500 dark:text-gray-400">
                Documento: {{ $appointment->patient->user->document ?? 'N/A' }}
            </p>

        </div>
        <div class="lg:flex lg:space-x-3 space-y-2 lg:space-y-0 mt-4 lg:mt-0">
            <x-button class="w-full lg:w-auto" x-on:click="$openModal('history-modal')" gray outline sm interaction="info">
                <i class="fa-solid fa-notes-medical"></i> Ver historia médica
            </x-button>
            <x-button class="w-full lg:w-auto" gray outline interaction="info" sm x-on:click="$openModal('previusConsultationsModal')">
                <i class="fa-solid fa-clock-rotate-left"></i> Consultas anteriores
            </x-button>
        </div>
    </div>

    <x-card>
        <x-tabs active="receta">
            <x-slot name="header">
                <x-tab-link tab="consulta"><i class="fa-solid fa-notes-medical me-2"></i> Consulta</x-tab-link>
                <x-tab-link tab="receta"><i class="fa-solid fa-file-prescription me-2"></i> Receta</x-tab-link>
            </x-slot>
            <x-tab-content tab="consulta">
                <div class="space-y-4">
                    <x-textarea label="Diagnóstico" wire:model="form.diagnosis"
                        placeholder="Describa el diagnóstico del paciente aquí" />
                    <x-textarea label="Tratamiento" wire:model="form.treatment"
                        placeholder="Describa el tratamiento del paciente aquí" />
                    <x-textarea label="Notas" wire:model="form.notes"
                        placeholder="Describa las notas de la consulta aquí" />

                </div>
            </x-tab-content>



            <x-tab-content tab="receta">
                <div class="space-y-4">
                    @forelse ($form['prescriptions'] as $index => $prescription)
                        <div class=" bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border lg:flex gap-4 space-y-2 lg:space-y-0"
                            wire:key="prescription-{{ $index }}"> {{-- Estas llaves se agregan para que se haga un correcto
                            seguimiento a los elementos --}}
                            <div class="flex-1">
                                <x-input label="Medicamento" placeholder=" Ej: Amoxicilina 500mg"
                                    wire:model="form.prescriptions.{{ $index }}.medicine" />
                            </div>
                            <div class="lg:w-32">
                                <x-input label="Dosis" placeholder=" Ej: 1 cápsula"
                                    wire:model="form.prescriptions.{{ $index }}.dosage" />
                            </div>
                            <div class="flex-1">
                                <x-input label="Frecuencia" placeholder=" Ej: Cada 8 horas por 10 días"
                                    wire:model="form.prescriptions.{{ $index }}.frequency" />
                            </div>
                            <div class="flex-shrink-0 lg:pt-6.5">
                                <x-mini-button sm red wire:click="removePrescription({{ $index }})" icon="trash"
                                    spinner="removePrescription" />
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            No hay medicamentos añadidos a la receta
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    <x-button sm outline secondary wire:click="addPrescription" spinner="addPrescription">
                        <i class="fa-solid fa-plus me-2"></i> Añadir medicamento
                    </x-button>
                </div>





            </x-tab-content>


        </x-tabs>

        <div class="flex justify-end mt-6">
            <x-button info wire:click="save" spinner="save" :disabled="!$appointment->isEditable()">
                <i class="fa-solid fa-save me-2"></i> Guardar consulta
            </x-button>
        </div>
    </x-card>


    <x-modal-card title="Historia médica del paciente" name="history-modal" width="5xl">

        <div class="grid lg:grid-cols-4 gap-5">
            <div class="">
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de Sangre</p>
                <p class=" font-semibold text-gray-900 dark:text-gray-100">
                    {{ $patient->bloodType ? $patient->bloodType->name : 'No registrado' }}</p>
            </div>
            <div class="">
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Alergias</p>
                <p class=" font-semibold text-gray-900 dark:text-gray-100">
                    {{ $patient->allergies ? $patient->allergies : 'No registrado' }}</p>
            </div>
            <div class="">
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Enfermedades crónicas</p>
                <p class=" font-semibold text-gray-900 dark:text-gray-100">
                    {{ $patient->chronic_diseases ? $patient->chronic_diseases : 'No registrado' }}</p>
            </div>
            <div class="">
                <p class="font-medium text-gray-500 dark:text-gray-400 mb-1">Antecedentes quirúrgicos</p>
                <p class=" font-semibold text-gray-900 dark:text-gray-100">
                    {{ $patient->surgical_history ? $patient->surgical_history : 'No registrado' }}</p>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end">
                <a class="text-blue-500 hover:text-blue-700 font-semibold"
                    href="{{ route('admin.patients.edit', $patient->id) }}" target="_blank">
                    Ver / Editar Historia Médica
                </a>
            </div>
        </x-slot>


    </x-modal-card>

    <x-modal-card title="Consultas anteriores" name="previusConsultationsModal" width="4xl">
        @forelse ($previousConsultation as $consultation)
            <a target="_blank" href="{{ route('admin.appointments.show', $consultation->appointment->id) }}"
                class="block p-5 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 hover:border-blue-500 hover:shadow-blue-400 transition-all duration-200">
                <div class="lg:flex items-center justify-between space-y-4 lg:space-y-0">
                    <div class="">
                        <p class="text-gray-500 dark:text-gray-400 font-semibold flex items-center">
                            <i class="fa-solid fa-calendar-days me-2"></i>
                            {{ $consultation->appointment->date->format('d/m/Y H:i') }}
                        </p>
                        <p>
                            Atendido por:
                            {{ $consultation->appointment->doctor->title }}
                            {{ $consultation->appointment->doctor->user->name }}
                            {{ $consultation->appointment->doctor->user->last_name }}
                        </p>
                    </div>
                    <div class="">
                        <x-button info class="w-full lg:w-auto">
                            <i class="fa-solid fa-eye me-2"></i> Ver detalles
                        </x-button>
                    </div>
                </div>

            </a>
        @empty
            <div
                class="text-center py-10 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-inbox text-2xl"></i>
                <p class="mt-4 font-medium text-gray-500 dark:text-gray-400 text-sm">
                    No hay consultas anteriores para este paciente
                </p>
            </div>
        @endforelse
        <x-slot name="footer">
            <div class="flex justify-end">
                <x-button gray outline sm x-on:click="$closeModal('previusConsultationsModal')">
                    <i class="fa-solid fa-xmark me-2"></i> Cerrar
                </x-button>
            </div>
        </x-slot>
    </x-modal-card>
</div>