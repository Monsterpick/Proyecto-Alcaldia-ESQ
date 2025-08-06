<?php

namespace App\Livewire;

use App\Models\Category;
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

final class CategoryTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'category-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'categories')
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
        return Category::query();
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
            ->add('description')
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
            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'description')
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
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('description')->operators(['contains']),
            Filter::inputText('created_at')->operators(['contains']),
            Filter::inputText('updated_at')->operators(['contains']),
        ];
    }

    public function actions(Category $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-category')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i>')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.categories.show', ['category' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-category')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.categories.edit', ['category' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }
        if(Auth::check() && Auth::user()->can('delete-category')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;

    }

}
