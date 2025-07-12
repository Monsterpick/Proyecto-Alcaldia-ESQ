<?php

namespace App\Livewire;

use App\Models\Doctor;
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

final class DoctorTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'doctor-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'doctores')
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
        return Doctor::query()
            ->join('users',  function ($users) {
                $users->on('doctors.user_id', '=', 'users.id');
            })
            ->select([
                'doctors.id',
                'users.name as name',
                'users.last_name as last_name',
                'users.document as document',
                'users.phone as phone',
                'users.email as email',
                'users.created_at as created_at',
                'users.updated_at as updated_at',
                'doctors.speciality_id as speciality_id',
                'doctors.medical_license_number as medical_license_number',
                'doctors.medical_college_number as medical_college_number',
                'doctors.title as title',
                'doctors.biography as biography',
                'doctors.is_active as is_active',
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
            ->add('name')
            ->add('last_name')
            ->add('document')
            ->add('speciality_id')
            ->add('phone')
            ->add('email')
            ->add('is_active')
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('Id', 'id'),
            Column::make('Nombres', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Apellidos', 'last_name')
                ->sortable()
                ->searchable(),

            Column::make('Documento', 'document')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Correo', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Especialidad', 'speciality_id')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'is_active')
                ->sortable()
                ->searchable(),

            Column::make('Creado el', 'created_at')
                ->sortable()
                ->searchable(),

            Column::make('Actualizado el', 'updated_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('last_name')->operators(['contains']),
            Filter::inputText('document')->operators(['contains']),
            Filter::inputText('phone')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('created_at')->operators(['contains']),
            Filter::inputText('updated_at')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Doctor $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-doctor')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.doctors.show', ['doctor' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-doctor')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.doctors.edit', ['doctor' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }
        if(Auth::check() && Auth::user()->can('delete-doctor')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;

    }

}
