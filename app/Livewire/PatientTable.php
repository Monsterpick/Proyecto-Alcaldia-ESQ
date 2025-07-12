<?php

namespace App\Livewire;

use App\Models\Patient;
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

final class PatientTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'patient-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'pacientes') 
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100, 500, 1000, 0])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        //return Patient::query()->with('user');

        return Patient::query()
                        ->join('users',  function($users){
                            $users->on('patients.user_id', '=', 'users.id');
                        })
                        ->select([
                            'patients.id',
                            'users.name as name',
                            'users.last_name as last_name',
                            'users.document as document',
                            'users.phone as phone',
                            'users.email as email',
                            'users.created_at as created_at',
                            'users.updated_at as updated_at',
                        ]);
    }

   /*  public function relationSearch(): array
    {
        return [
            'user' => [
                'name',
                'last_name',
                'document',
                'phone',
                'email',
                'created_at',
                'updated_at',
            ]
        ];
    } */

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('last_name')
            ->add('document')
            ->add('phone')
            ->add('email')
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

            Column::make('Creado el', 'created_at', 'created_at')
                ->sortable(),

            Column::make('Actualizado el', 'updated_at', 'updated_at')
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
        $this->js('alert('.$rowId.')');
    }

    public function actions(Patient $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-patient')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.patients.show', ['patient' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-patient')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.patients.edit', ['patient' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }
        if(Auth::check() && Auth::user()->can('delete-patient')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;

    }

}
