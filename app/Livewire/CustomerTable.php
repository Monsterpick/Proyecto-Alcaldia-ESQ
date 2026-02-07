<?php

namespace App\Livewire;

use App\Models\Customer;
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

final class CustomerTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'customer-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'clientes')
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
        return Customer::query()
            ->join('identities',  function ($identities) {
                $identities->on('customers.identity_id', '=', 'identities.id');
            })
            ->select([
                'customers.id',
                'identities.name as identity_name',
                'customers.document_number as document_number',
                'customers.name as customer_name',
                'customers.phone as phone',
                'customers.created_at as created_at',
                'customers.updated_at as updated_at',
            ]);
    }

    public function relationSearch(): array
    {
        return [
            'identity' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('identity_name')
            ->add('document_number')
            ->add('customer_name')
            ->add('phone')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Identity', 'identity_name'),
            Column::make('Document number', 'document_number')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'customer_name')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
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
            Filter::inputText('customer_name', 'customers.name')->operators(['contains']),
            Filter::inputText('document_number', 'customers.document_number')->operators(['contains']),
            Filter::inputText('phone', 'customers.phone')->operators(['contains']),
            Filter::inputText('created_at', 'customers.created_at')->operators(['contains']),
            Filter::inputText('updated_at', 'customers.updated_at')->operators(['contains']),
        ];
    }

    public function actions(Customer $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-customer')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i>')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.customers.show', ['customer' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-customer')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.customers.edit', ['customer' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }
        if(Auth::check() && Auth::user()->can('delete-customer')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;

    }


}
