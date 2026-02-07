<?php

namespace App\Livewire;

use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use Illuminate\Support\Facades\Auth;

final class AppointmentTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public string $tableName = 'appointment-table';

    public function setUp(): array
    {
        return [

            PowerGrid::exportable(fileName: 'usuarios') 
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100, 500, 1000, 0])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Appointment::query()
        ->join('patients', function($patients){
            $patients->on('appointments.patient_id', '=', 'patients.id');
        })
        ->join('users', function($users){
            $users->on('patients.user_id', '=', 'users.id');
        })
        ->join('appointment_statuses', function($appointment_statuses){
            $appointment_statuses->on('appointments.appointment_status_id', '=', 'appointment_statuses.id');
        })
        ->select([
            'appointments.id',
            'appointments.date',
            'appointments.start_time',
            'appointments.end_time',
            'appointments.duration',
            'appointments.reason',
            'appointments.created_at',
            'appointments.updated_at',
            'appointment_statuses.name as appointment_status_name',
            'appointment_statuses.color as appointment_status_color',
            'users.name as name_patient',
            'users.last_name as last_name_patient',
            'users.phone as phone_patient',
        ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
        ->add('id')
        ->add('name_patient')
        ->add('last_name_patient')
        ->add('phone_patient')
        ->add('date', function ($appointment) {
            return Carbon::parse($appointment->date)->format('Y-m-d');
        })
        ->add('start_time')
        ->add('end_time')
        ->add('duration')
        ->add('reason')
        ->add('appointment_status_name', function ($row) {
            return [
                'template-appointment-status' => [
                    'id' => $row->id,
                    'name' => $row->appointment_status_name,
                    'color' => $row->appointment_status_color,
                ]
                ];
        })
        ->add('created_at')
        ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('Id', 'id', 'appointments.id')
                ->sortable()
                ->searchable(),

            Column::make('Paciente', 'name_patient', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Apellido', 'last_name_patient', 'users.last_name')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'phone_patient', 'users.phone')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date', 'appointments.date')
                ->sortable()
                ->searchable(),

            Column::make('Inicio', 'start_time', 'appointments.start_time')
                ->sortable()
                ->searchable(),

            Column::make('Fin', 'end_time', 'appointments.end_time')
                ->sortable()
                ->searchable(),

            Column::make('Duración', 'duration', 'appointments.duration')
                ->sortable()
                ->searchable(),

            Column::make('Motivo', 'reason', 'appointments.reason')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'appointment_status_name', 'appointment_statuses.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de creación', 'created_at', 'appointments.created_at')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de actualización', 'updated_at', 'appointments.updated_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('users.name')->operators(['contains']),
            Filter::inputText('users.last_name')->operators(['contains']),
            Filter::inputText('users.phone')->operators(['contains']),
            Filter::inputText('appointments.date')->operators(['contains']),
            Filter::inputText('appointments.start_time')->operators(['contains']),
            Filter::inputText('appointments.end_time')->operators(['contains']),
            Filter::inputText('appointments.duration')->operators(['contains']),
            Filter::inputText('appointments.reason')->operators(['contains']),
            Filter::inputText('appointment_statuses.name')->operators(['contains']),
            Filter::inputText('appointments.created_at')->operators(['contains']),
            Filter::inputText('appointments.updated_at')->operators(['contains']),
        ];
    }

    public function actions(Appointment $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-consultation')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-file-medical btn-group-icon"></i>')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.appointments.consultation', ['appointment' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-appointment')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.appointments.edit', ['appointment' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }
        if(Auth::check() && Auth::user()->can('delete-appointment')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;
    }

    public function rowTemplates(): array
    {
        return [
            'template-appointment-status' => '
                <span class="bg-{{ color }}-100 text-{{ color }}-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-{{ color }}-900 dark:text-{{ color }}-300">{{ name }}</span>
            ',
        ];
    }

}
