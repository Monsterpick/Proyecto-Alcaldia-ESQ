<?php

namespace App\Livewire;

use App\Models\Warehouse;
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

final class WarehouseTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'warehouse-table';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'almacenes')
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
        return Warehouse::query();
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
            ->add('location')
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Location', 'location')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable(),

            Column::make('Updated at', 'updated_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'name')->operators(['contains']),
            Filter::inputText('location', 'location')->operators(['contains']),
            Filter::inputText('created_at', 'created_at')->operators(['contains']),
            Filter::inputText('updated_at', 'updated_at')->operators(['contains']),
        ];
    }

    public function actions(Warehouse $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-warehouse')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i>')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.warehouses.show', ['warehouse' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-warehouse')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.warehouses.edit', ['warehouse' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }
        if(Auth::check() && Auth::user()->can('delete-warehouse')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;
    }
}
