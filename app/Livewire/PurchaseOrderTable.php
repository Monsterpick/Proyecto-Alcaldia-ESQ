<?php

namespace App\Livewire;

use App\Models\PurchaseOrder;
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

final class PurchaseOrderTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'purchase-order-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'purchase-orders')
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
        return PurchaseOrder::query()
            ->join('suppliers', function($suppliers){
                $suppliers->on('purchase_orders.supplier_id', '=', 'suppliers.id');
            })
            ->select([
                'purchase_orders.id',
                'purchase_orders.date',
                'purchase_orders.serie',
                'purchase_orders.correlative',
                'purchase_orders.total',
                'purchase_orders.discount',
                'purchase_orders.tax',
                'purchase_orders.observation',
                'suppliers.document_number as document_number',
                'suppliers.name as name'
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
            ->add('serie')
            ->add('correlative')
            ->add('date', fn ($item) => Carbon::parse($item->date))
            ->add('date_formatted', fn ($item) => Carbon::parse($item->date)->format('Y-m-d'))
            ->add('document_number')
            ->add('name')
            ->add('total');
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),

            Column::make('Id', 'id'),

            Column::make('Fecha', 'date_formatted', 'date')
                ->sortable()
                ->searchable(),

            Column::make('Serie', 'serie')
                ->sortable()
                ->searchable(),

            Column::make('CORRELATIVO', 'correlative')
                ->sortable()
                ->searchable(),


            Column::make('DOCUMENTO', 'document_number'),
            Column::make('PROVEEDOR', 'name'),
            Column::make('Total', 'total')
                ->sortable()
                ->searchable(),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('date')->operators(['contains']),
            Filter::inputText('serie')->operators(['contains']),
            Filter::inputText('correlative')->operators(['contains']),
            Filter::inputText('document_number')->operators(['contains']),
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('total')->operators(['contains']),
            Filter::inputText('discount')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(PurchaseOrder $row): array
    {
        $actions = [];

       
        if(Auth::check() && Auth::user()->can('delete-purchase-order')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;

    }
}
