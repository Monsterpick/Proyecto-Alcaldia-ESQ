<?php

namespace App\Livewire;

use App\Models\Parroquia;
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

final class ParroquiaTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'parroquia-table';

    public function setUp(): array
    {
        return [

            PowerGrid::exportable(fileName: 'roles') 
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
        return Parroquia::query()
        ->join('municipios', 'municipios.id', '=', 'parroquias.municipio_id')
        ->join('estados', 'estados.id', '=', 'municipios.estado_id')
        ->select('parroquias.*', 'municipios.municipio as nombre_municipio', 'estados.estado as nombre_estado');
    }

    public function relationSearch(): array
    {
        return [
            'municipio' => [
                'municipio',
                'estado',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre_estado')
            ->add('nombre_municipio')
            ->add('parroquia')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            Column::make('Id', 'id'),
            Column::make('Estado', 'nombre_estado')
                ->sortable()
                ->searchable(),
            Column::make('Municipio', 'nombre_municipio')
                ->sortable()
                ->searchable(),

            Column::make('Parroquia', 'parroquia')
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

            Filter::select('nombre_municipio', 'municipio_id')
                ->dataSource(Municipio::all())
                ->optionValue('id')
                ->optionLabel('municipio'),

            Filter::inputText('parroquia')
                ->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Parroquia $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-parroquia')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.parroquias.show', ['parroquia' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-parroquia')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.parroquias.edit', ['parroquia' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('delete-parroquia')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;
    }

}
