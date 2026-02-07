<?php

namespace App\Livewire;

use App\Models\Supplier;
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

final class SupplierTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'supplier-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'proveedores')
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
        return Supplier::query()
            ->join('identities',  function ($identities) {
                $identities->on('suppliers.identity_id', '=', 'identities.id');
            })
            ->select([
                'suppliers.id',
                'identities.name as identity_name',
                'suppliers.document_number as document_number',
                'suppliers.name as supplier_name',
                'suppliers.address as address',
                'suppliers.phone as phone',
                'suppliers.email as email',
                'suppliers.created_at as created_at',
                'suppliers.updated_at as updated_at',
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
            ->add('supplier_name')
            ->add('address')
            ->add('phone')
            ->add('email')
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

            Column::make('Name', 'supplier_name')
                ->sortable()
                ->searchable(),

            Column::make('Address', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable(),

            Column::make('Updated at', 'updated_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('identity_name', 'Identity')->operators(['contains']),
            Filter::inputText('document_number', 'Document number')->operators(['contains']),
            Filter::inputText('supplier_name', 'Name')->operators(['contains']),
            Filter::inputText('address', 'Address')->operators(['contains']),
            Filter::inputText('phone', 'Phone')->operators(['contains']),
            Filter::inputText('email', 'Email')->operators(['contains']),
        ];
    }

    public function actions(Supplier $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-supplier')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i>')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.suppliers.show', ['supplier' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-supplier')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.suppliers.edit', ['supplier' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }
        if(Auth::check() && Auth::user()->can('delete-supplier')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;
    }

}
