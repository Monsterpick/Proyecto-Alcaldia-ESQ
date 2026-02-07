<?php

namespace App\Livewire;

use App\Models\Product;
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

final class ProductTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'product-table';

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
        return Product::query()
            ->join('categories', function($categories){
                $categories->on('products.category_id', '=', 'categories.id');
            })
            ->select([
                'products.id',
                'products.name',
                'products.price',
                'categories.name as category_name',
                'products.expedition_date',
                'products.expiration_date',
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
            ->add('avatar', fn ($item) => '<img class="w-10 h-10 shrink-0 grow-0 rounded-full" src="' . asset($item->image) . '">')
            ->add('name')
            ->add('category_name')
            ->add('price')
            ->add('expedition_date')
            ->add('expiration_date');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            
            Column::make('Imagen', 'avatar'),
            Column::make('ID', 'id'),
            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Categoría', 'category_name')
                ->sortable()
                ->searchable(),
            Column::make('Precio', 'price')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('category_name')->operators(['contains']),
            Filter::inputText('price')->operators(['contains'])
        ];
    }


    public function actions(Product $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-product')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i>')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.products.show', ['product' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-product')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.products.edit', ['product' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }
        if(Auth::check() && Auth::user()->can('delete-product')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;

    }

}
