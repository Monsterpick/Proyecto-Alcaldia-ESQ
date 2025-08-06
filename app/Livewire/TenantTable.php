<?php

namespace App\Livewire;

use App\Models\Tenant;
use App\Models\Estatus;
use App\Models\Plan;
use App\Models\Actividad;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

final class TenantTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'tenant-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'tenants') 
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
        return Tenant::query()
        ->with(['actividad', 'estatus', 'plan'])
        ->select([
            'tenants.id',
            'tenants.domain',
            'tenants.name as tenant_name',
            'tenants.razon_social',
            'tenants.rif',
            'tenants.telefono_principal',
            'tenants.telefono_secundario',
            'tenants.email_principal',
            'tenants.email_secundario',
            'tenants.created_at as tenant_created_at',
            'tenants.updated_at as tenant_updated_at',
            'actividads.name as actividad_name',
            'estatuses.name as estatus_name',
            'plans.name as plan_name',
        ])
        
        ->join('actividads', 'tenants.actividad_id', '=', 'actividads.id')
        ->join('estatuses', 'tenants.estatus_id', '=', 'estatuses.id')
        ->join('plans', 'tenants.plan_id', '=', 'plans.id');
    }

    public function relationSearch(): array
    {
        return [
            'actividad' => ['name'],
            'estatus' => ['name'],
            'plan' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('domain')
            /* ->add('domain', function ($row) {
                if (request()->is()) {
                    return $row->domain;
                }
                return [
                    'template-domain' => [
                        'domain' => $row->domain,
                    ]
                ];
            }) */
            ->add('actividad_name')
            ->add('tenant_name')
            ->add('razon_social')
            ->add('rif')
            ->add('telefono_principal')
            ->add('telefono_secundario')
            ->add('email_principal')
            ->add('email_secundario')
            ->add('estatus_name')
            ->add('plan_name')
            ->add('tenant_created_at')
            ->add('tenant_updated_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            Column::make('Id', 'id')->sortable()->searchable(),
            Column::make('Dominio', 'domain')->sortable()->searchable(),
            Column::make('Actividad', 'actividad_name')->sortable()->searchable(),
            Column::make('Plan', 'plan_name')->sortable()->searchable(),
            Column::make('Estatus', 'estatus_name')->sortable()->searchable(),
            Column::make('Nombre', 'tenant_name')->sortable()->searchable(),
            Column::make('Razón Social', 'razon_social')->sortable()->searchable(),
            Column::make('RIF', 'rif')->sortable()->searchable(),
            Column::make('Teléfono Principal', 'telefono_principal')->sortable()->searchable(),
            Column::make('Correo Principal', 'email_principal')->sortable()->searchable(),
            Column::make('Created at', 'tenant_created_at')->sortable()->searchable(),
            Column::make('Updated at', 'tenant_updated_at')->sortable()->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('domain')->operators(['contains']),
            Filter::select('actividad_name', 'actividad_id')->dataSource(Actividad::all())->optionValue('id')->optionLabel('name'),
            Filter::select('estatus_name', 'estatus_id')->dataSource(Estatus::all())->optionValue('id')->optionLabel('name'),
            Filter::select('plan_name', 'plan_id')->dataSource(Plan::all())->optionValue('id')->optionLabel('name'),
            Filter::inputText('tenant_name', 'tenants.name')->operators(['contains']),
            Filter::inputText('razon_social')->operators(['contains']),
            Filter::inputText('rif')->operators(['contains']),
            Filter::inputText('telefono_principal')->operators(['contains']),
            Filter::inputText('telefono_secundario')->operators(['contains']),
            Filter::inputText('email_principal')->operators(['contains']),
            Filter::inputText('email_secundario')->operators(['contains']),
            Filter::inputText('tenant_created_at', 'tenants.created_at')->operators(['contains']),
            Filter::inputText('tenant_updated_at', 'tenants.updated_at')->operators(['contains']),
        ];
    }

    public function actions(Tenant $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-tenant')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.tenants.show', ['tenant' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-tenant')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.tenants.edit', ['tenant' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('view-tenant-payment')){
            $actions[] = Button::add('view-tenant-payment')
                ->slot('<i class="fas fa-coins btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->attributes(['onclick' => 'showPaymentModal(\'' . $row->id . '\')']);
        }

        if(Auth::check() && Auth::user()->can('delete-tenant')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete(\'' . $row->id . '\')']);
        }

        return $actions;
        
    }

    public function rowTemplates(): array
    {
        return [
            'template-domain' => '<a href="https://{{ domain }}" target="_blank">{{ domain }}</a>',
        ];
    }
}
