<?php

namespace App\Livewire;

use App\Models\PaymentOrigin;
use App\Models\PaymentType;
use App\Models\TenantPayment;
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
use Illuminate\Support\Facades\Storage;

final class TenantPaymentTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'tenant-payment-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'tenant-payments') 
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
        return TenantPayment::query()
            ->with(['paymentType', 'paymentOrigin'])
            ->select([
                'tenant_payments.id',
                'tenant_payments.tenant_id',
                'tenant_payments.payment_type_id',
                'tenant_payments.payment_origin_id',
                'tenant_payments.amount',
                'tenant_payments.reference_number',
                'tenant_payments.payment_date',
                'tenant_payments.period_start',
                'tenant_payments.period_end',
                'tenant_payments.notes',
                'tenant_payments.status',
                'tenant_payments.currency',
                'tenant_payments.image_path',
                'tenant_payments.created_at',
                'tenant_payments.updated_at',
                'payment_types.name as payment_type_name',
                'payment_origins.name as payment_origin_name',
            ])
            ->join('payment_types', 'tenant_payments.payment_type_id', '=', 'payment_types.id')
            ->join('payment_origins', 'tenant_payments.payment_origin_id', '=', 'payment_origins.id');
    }

    public function relationSearch(): array
    {
        return [
            'paymentType' => ['name'],
            'paymentOrigin' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tenant_id')
            ->add('payment_type_name')
            ->add('payment_origin_name')
            ->add('amount')
            ->add('reference_number')
            ->add('payment_date_formatted', fn (TenantPayment $model) => Carbon::parse($model->payment_date)->format('d/m/Y'))
            ->add('period_start_formatted', fn (TenantPayment $model) => Carbon::parse($model->period_start)->format('d/m/Y'))
            ->add('period_end_formatted', fn (TenantPayment $model) => Carbon::parse($model->period_end)->format('d/m/Y'))
            ->add('notes')
            ->add('status')
            ->add('currency')
            ->add('image_path', function ($row) {
                return [
                    'template-image-path' => [
                        'id' => $row->id,
                        'image_path' => $row->image_path,
                        'url' => Storage::url($row->image_path)
                    ]
                    ];
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            Column::make('Id', 'id'),
            Column::make('Tenant id', 'tenant_id')
                ->sortable()
                ->searchable(),

            Column::make('Tipo de pago', 'payment_type_name')
                ->sortable()
                ->searchable(),

            Column::make('Origen de pago', 'payment_origin_name')
                ->sortable()
                ->searchable(),

            Column::make('Monto', 'amount')
                ->sortable()
                ->searchable(),

            Column::make('Número de referencia', 'reference_number')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de pago', 'payment_date_formatted', 'payment_date')
                ->sortable(),

            Column::make('Periodo inicio', 'period_start_formatted', 'period_start')
                ->sortable(),

            Column::make('Periodo fin', 'period_end_formatted', 'period_end')
                ->sortable(),

            Column::make('Notas', 'notes')
                ->sortable()
                ->searchable(),

            Column::make('Estatus', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Moneda', 'currency')
                ->sortable()
                ->searchable(),

            Column::make('Comprobante', 'image_path')->visibleInExport(false)
                ->template(),

            Column::make('Creado el', 'created_at')
                ->sortable(),

            Column::make('Actualizado el', 'updated_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('tenant_id')->operators(['contains']),
            Filter::select('payment_type_name', 'payment_type_id')
                ->dataSource(PaymentType::all())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::select('payment_origin_name', 'payment_origin_id')
                ->dataSource(PaymentOrigin::all())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::inputText('amount')->operators(['contains']),
            Filter::inputText('reference_number')->operators(['contains']),
            Filter::inputText('payment_date')->operators(['contains']),
            Filter::inputText('period_start')->operators(['contains']),
            Filter::inputText('period_end')->operators(['contains']),
            Filter::inputText('notes')->operators(['contains']),
            Filter::inputText('status')->operators(['contains']),
            Filter::inputText('currency')->operators(['contains']),
            Filter::inputText('created_at')->operators(['contains']),
            Filter::inputText('updated_at')->operators(['contains']),
        ];
    }

    public function actions(TenantPayment $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-tenant-payment')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.tenant-payments.show', ['tenantPayment' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-tenant-payment')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.tenant-payments.edit', ['tenantPayment' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('delete-tenant-payment')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->route('admin.tenant-payments.destroy', ['tenantPayment' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        return $actions;
    }

    public function rowTemplates(): array
    {
        return [
            'template-image-path' => '
                <div class="flex items-center justify-center space-x-2">
                    <img src="{{ url }}" alt="" class="w-10 h-10 object-cover rounded-full">
                    <a href="{{ url }}" target="_blank" class="text-blue-600 hover:text-blue-800">Ver</a>
                </div>
            '
        ];
    }

}
