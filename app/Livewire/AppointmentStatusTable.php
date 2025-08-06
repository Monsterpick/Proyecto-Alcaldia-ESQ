<?php

namespace App\Livewire;

use App\Models\AppointmentStatus;
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

final class AppointmentStatusTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public string $tableName = 'appointment-status-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::exportable(fileName: 'appointment-statuses') 
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100, 500, 1000, 0])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return AppointmentStatus::query();
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
            Column::make('Id', 'id'),
            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'description')
                ->sortable()
                ->searchable(),

            Column::make('Creado el',  'created_at')
                ->sortable(),

            Column::make('Actualizado el',  'updated_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('description')->operators(['contains']),
            Filter::inputText('created_at')->operators(['contains']),
            Filter::inputText('updated_at')->operators(['contains']),
        ];
    }

    public function actions(AppointmentStatus $row): array
    {
        $actions = [];

        if(Auth::check() && Auth::user()->can('view-appointment-status')){
            $actions[] = Button::add('show')
                ->slot('<i class="fas fa-eye btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-first')
                ->route('admin.appointment-statuses.show', ['appointmentStatus' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('edit-appointment-status')){
            $actions[] = Button::add('edit')
                ->slot('<i class="fas fa-edit btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-middle')
                ->route('admin.appointment-statuses.edit', ['appointmentStatus' => $row->id])
                ->attributes(['wire:navigate' => true]);
        }

        if(Auth::check() && Auth::user()->can('delete-appointment-status')){
            $actions[] = Button::add('delete')
                ->slot('<i class="fas fa-trash btn-group-icon"></i> ')
                ->id()
                ->class('btn-group-item btn-group-item-last cursor-pointer')
                ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
        }

        return $actions;
    }

}
