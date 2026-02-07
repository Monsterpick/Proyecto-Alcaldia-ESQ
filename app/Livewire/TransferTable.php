<?php

namespace App\Livewire;

use App\Models\Transfer;
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

final class TransferTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'transfer-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'transfers')
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
        return Transfer::query()
            ->join('warehouses', function($warehouses){
                $warehouses->on('transfers.origin_warehouse_id', '=', 'warehouses.id');
            })
            ->join('warehouses as destination_warehouses', function($warehouses){
                $warehouses->on('transfers.destination_warehouse_id', '=', 'destination_warehouses.id');
            })
            ->select([
                'transfers.id',
                'transfers.type',
                'transfers.serie',
                'transfers.correlative',
                'transfers.date',
                'transfers.total',
                'transfers.discount',
                'transfers.tax',
                'transfers.observation',
                'warehouses.name as warehouse_name',
                'destination_warehouses.name as destination_warehouse_name',
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
            ->add('type')
            ->add('serie')
            ->add('correlative')
            ->add('date', fn ($item) => Carbon::parse($item->date))
            ->add('date_formatted', fn ($item) => Carbon::parse($item->date)->format('Y-m-d'))
            ->add('warehouse_name')
            ->add('destination_warehouse_name')
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

            Column::make('Tipo', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Serie', 'serie')
                ->sortable()
                ->searchable(),

            Column::make('CORRELATIVO', 'correlative')
                ->sortable()
                ->searchable(),


            Column::make('ALMACEN ORIGEN', 'warehouse_name'),
            Column::make('ALMACEN DESTINO', 'destination_warehouse_name'),
            
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
            Filter::inputText('warehouse_name')->operators(['contains']),
            Filter::inputText('destination_warehouse_name')->operators(['contains']),
            Filter::inputText('total')->operators(['contains']),
            Filter::inputText('discount')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Transfer $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('delete-transfer')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')'])
                ->tooltip('Eliminar');
        }

        return $actions;

    }
}
