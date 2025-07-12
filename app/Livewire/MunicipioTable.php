<?php

namespace App\Livewire;

use App\Models\Municipio;
use App\Models\Estado;
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

final class MunicipioTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'municipio-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'municipios') 
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
        return Municipio::query()
            ->join('estados', 'estados.id', '=', 'municipios.estado_id')
            ->select('municipios.*', 'estados.estado as nombre_estado');
    }

    public function relationSearch(): array
    {
        return [
            'estado' => [
                'estado',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre_estado')
            ->add('municipio')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            
            Column::make('Id', 'id')
                ->sortable(),

            Column::make('Estado', 'nombre_estado')
                ->sortable()
                ->searchable(),

            Column::make('Municipio', 'municipio')
                ->sortable()
                ->searchable(),

            Column::make('Creado el', 'created_at')
                ->sortable(),

            Column::make('Actualizado el', 'updated_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('nombre_estado', 'estado_id')
                ->dataSource(Estado::all())
                ->optionValue('id')
                ->optionLabel('estado'),

            Filter::inputText('municipio')
                ->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Municipio $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-municipio')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.municipios.show', ['municipio' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-municipio')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.municipios.edit', ['municipio' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('delete-municipio')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;
    }

}
