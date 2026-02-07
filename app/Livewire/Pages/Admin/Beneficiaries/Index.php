<?php

namespace App\Livewire\Pages\Admin\Beneficiaries;

use Livewire\Component;
use App\Models\Beneficiary;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('livewire.layout.admin.admin')]
#[Title('Beneficiarios')]
class Index extends Component
{
    use WithPagination;

    // Búsqueda y filtros
    public $search = '';
    public $statusFilter = '';

    public function deleteBeneficiary($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);
        $beneficiaryName = $beneficiary->full_name;
        
        // Eliminar (soft delete)
        $beneficiary->delete();
        
        // Mostrar mensaje
        session()->flash('message', "Beneficiario {$beneficiaryName} eliminado exitosamente");
        
        // Redirigir para recargar
        return redirect()->route('admin.beneficiaries.index');
    }

    public function toggleStatus($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);
        $newStatus = $beneficiary->status === 'active' ? 'inactive' : 'active';
        $beneficiary->status = $newStatus;
        $beneficiary->updated_by = auth()->id();
        $beneficiary->save();
        
        $statusText = $newStatus === 'active' ? 'activo' : 'inactivo';
        
        session()->flash('message', "Estado cambiado a {$statusText} exitosamente");
        
        return redirect()->route('admin.beneficiaries.index');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query con búsqueda y filtros
        $query = Beneficiary::with(['parroquia', 'circuitoComunal']);
        
        // Filtro por búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('second_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('second_last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('cedula', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }
        
        // Filtro por estado
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        // Paginación
        $beneficiaries = $query->latest()->paginate(10);

        $stats = [
            'total' => Beneficiary::count(),
            'active' => Beneficiary::active()->count(),
            'inactive' => Beneficiary::inactive()->count(),
        ];

        return view('livewire.pages.admin.beneficiaries.index', [
            'beneficiaries' => $beneficiaries,
            'stats' => $stats,
        ]);
    }
}
