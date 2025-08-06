<?php

namespace App\Livewire;

use Spatie\Permission\Models\Permission;
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

final class PermissionTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'permission-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [

            PowerGrid::exportable(fileName: 'permisos') 
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
        return Permission::query();
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
            ->add('guard_name')
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            Column::make('Id', 'id'),
            Column::make('Nombre', 'name'),
            Column::make('Guard Name', 'guard_name'),
            Column::make('Creado el', 'created_at')
                ->sortable(),
            Column::make('Actualizado el', 'updated_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('guard_name')->operators(['contains']),
            Filter::inputText('created_at')->operators(['contains']),
            Filter::inputText('updated_at')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Permission $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-permission')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.permissions.show', ['permission' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-permission')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.permissions.edit', ['permission' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('delete-permission')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;

    }

    public function header(): array
    {
        return [
            Button::add('group-wrapper')
                ->class('btn-group')
        ];
    }

}
