<?php

namespace App\Livewire;

use App\Models\Movement;
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

final class MovementTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'movement-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'movements')
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
        return Movement::query()
            ->join('warehouses', function($warehouses){
                $warehouses->on('movements.warehouse_id', '=', 'warehouses.id');
            })
            ->join('reasons', function($reasons){
                $reasons->on('movements.reason_id', '=', 'reasons.id');
            })
            ->select([
                'movements.id',
                'movements.date',
                'movements.serie',
                'movements.correlative',
                'movements.type',
                'movements.total',
                'movements.discount',
                'movements.tax',
                'movements.observation',
                'warehouses.name as warehouse_name',
                'reasons.name as reason_name'
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
            ->add('reason_name')
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


            Column::make('ALMACEN', 'warehouse_name'),
            Column::make('RAZON', 'reason_name'),
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
            Filter::inputText('reason_name')->operators(['contains']),
            Filter::inputText('total')->operators(['contains']),
            Filter::inputText('discount')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Movement $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('delete-movement')){
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
