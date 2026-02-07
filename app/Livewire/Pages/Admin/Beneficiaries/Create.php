<?php

namespace App\Livewire\Pages\Admin\Beneficiaries;

use Livewire\Component;
use App\Models\Beneficiary;
use App\Models\Parroquia;
use App\Models\CircuitoComunal;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('livewire.layout.admin.admin')]
#[Title('Añadir Beneficiario')]
class Create extends Component
{
    // Datos del formulario
    public $first_name = '';
    public $second_name = '';
    public $last_name = '';
    public $second_last_name = '';
    public $document_type = 'V';
    public $circuito_search = ''; // Para búsqueda de circuitos
    public $cedula = '';
    public $birth_date = '';
    public $email = '';
    public $phone = '';
    public $parroquia_id = '';
    public $circuito_comunal_id = '';
    public $sector = '';
    public $sector_search = ''; // Para búsqueda de sectores
    public $reference_point = '';
    public $address = '';
    public $status = 'active';

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'second_name' => 'nullable|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'second_last_name' => 'nullable|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'document_type' => 'required|in:V,E,J,G,P',
            'cedula' => 'required|string|max:20|regex:/^[0-9]+$/|unique:beneficiaries,cedula',
            'birth_date' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'email' => 'nullable|email|max:255|unique:beneficiaries,email',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\-\+\(\)\s]+$/',
            'parroquia_id' => 'required|exists:parroquias,id',
            'circuito_comunal_id' => 'required|exists:circuito_comunals,id',
            'sector' => 'nullable|string|max:255',
            'reference_point' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:1000',
        ];
    }

    protected $messages = [
        'first_name.required' => 'El primer nombre es obligatorio',
        'first_name.regex' => 'El primer nombre solo debe contener letras',
        'second_name.regex' => 'El segundo nombre solo debe contener letras',
        'last_name.required' => 'El apellido es obligatorio',
        'last_name.regex' => 'El apellido solo debe contener letras',
        'second_last_name.regex' => 'El segundo apellido solo debe contener letras',
        'document_type.required' => 'El tipo de documento es obligatorio',
        'cedula.required' => 'La cédula es obligatoria',
        'cedula.regex' => 'La cédula solo debe contener números',
        'cedula.unique' => 'Esta cédula ya está registrada en el sistema',
        'birth_date.required' => 'La fecha de nacimiento es obligatoria',
        'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida',
        'birth_date.before_or_equal' => 'El beneficiario debe ser mayor de 18 años',
        'email.email' => 'El formato del correo electrónico no es válido',
        'email.unique' => 'Este correo electrónico ya está registrado',
        'phone.regex' => 'El formato del teléfono no es válido',
        'parroquia_id.required' => 'La parroquia es obligatoria',
        'parroquia_id.exists' => 'La parroquia seleccionada no existe',
        'circuito_comunal_id.required' => 'El circuito comunal es obligatorio',
        'circuito_comunal_id.exists' => 'El circuito comunal seleccionado no existe',
    ];

    public function updatedParroquiaId()
    {
        $this->circuito_comunal_id = '';
    }

    // Validación en tiempo real para cada campo
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // Verificar si el formulario está completo
    public function isFormValid()
    {
        return !empty($this->first_name) &&
               !empty($this->last_name) &&
               !empty($this->document_type) &&
               !empty($this->cedula) &&
               !empty($this->birth_date) &&
               !empty($this->parroquia_id) &&
               !empty($this->circuito_comunal_id);
    }

    // Método para seleccionar circuito comunal
    public function selectCircuito($id, $search)
    {
        $this->circuito_comunal_id = $id;
        $this->circuito_search = $search;
        $this->dispatch('circuito-selected');
    }

    // Método para limpiar circuito comunal
    public function clearCircuito()
    {
        $this->circuito_comunal_id = '';
        $this->circuito_search = '';
    }

    // Método para seleccionar sector
    public function selectSector($nombre)
    {
        $this->sector = $nombre;
        $this->sector_search = $nombre;
        $this->dispatch('sector-selected');
    }

    // Método para limpiar sector
    public function clearSector()
    {
        $this->sector = '';
        $this->sector_search = '';
    }

    public function save()
    {
        try {
            $this->validate();

            $beneficiary = Beneficiary::create([
                'first_name' => trim($this->first_name),
                'second_name' => trim($this->second_name),
                'last_name' => trim($this->last_name),
                'second_last_name' => trim($this->second_last_name),
                'document_type' => $this->document_type,
                'cedula' => trim($this->cedula),
                'birth_date' => $this->birth_date,
                'email' => trim($this->email),
                'phone' => trim($this->phone),
                'parroquia_id' => $this->parroquia_id,
                'circuito_comunal_id' => $this->circuito_comunal_id,
                'sector' => trim($this->sector),
                'reference_point' => trim($this->reference_point),
                'address' => trim($this->address),
                'status' => $this->status,
                'created_by' => auth()->id(),
                'state' => 'Trujillo',
                'municipality' => 'Escuque',
            ]);

            // Emitir evento para SweetAlert
            $this->dispatch('beneficiaryCreated', [
                'name' => $beneficiary->full_name,
                'cedula' => $beneficiary->full_cedula
            ]);

            // Redirigir después de un breve delay para que se vea el SweetAlert
            return redirect()->route('admin.beneficiaries.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Las validaciones ya muestran los mensajes automáticamente
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el beneficiario: ' . $e->getMessage());
            $this->dispatch('showError', [
                'message' => 'Error al crear el beneficiario'
            ]);
        }
    }

    public function render()
    {
        $parroquias = Parroquia::where('municipio_id', 1)->orderBy('parroquia')->get();
        
        $circuitos = collect();
        $sectores = collect();
        
        if ($this->parroquia_id) {
            $query = CircuitoComunal::where('parroquia_id', $this->parroquia_id);
            
            // Filtrar por búsqueda SOLO si hay texto en el input
            if ($this->circuito_search && trim($this->circuito_search) !== '') {
                $query->where(function($q) {
                    $q->where('codigo', 'like', '%' . $this->circuito_search . '%')
                      ->orWhere('nombre', 'like', '%' . $this->circuito_search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->circuito_search . '%');
                });
            }
            
            // Siempre devolver resultados si hay parroquia seleccionada
            $circuitos = $query->orderBy('codigo')->get();
            
            // Extraer sectores únicos de los circuitos comunales
            $allCircuitos = CircuitoComunal::where('parroquia_id', $this->parroquia_id)->get();
            $sectoresArray = [];
            
            foreach ($allCircuitos as $circuito) {
                if ($circuito->descripcion && str_contains($circuito->descripcion, 'Sector:')) {
                    preg_match('/Sector:\s*([^|]+)/', $circuito->descripcion, $matches);
                    if (isset($matches[1])) {
                        $sector = trim($matches[1]);
                        if ($sector && !in_array($sector, $sectoresArray)) {
                            $sectoresArray[] = $sector;
                        }
                    }
                }
            }
            
            // Filtrar sectores si hay búsqueda
            if ($this->sector_search && trim($this->sector_search) !== '') {
                $sectoresArray = array_filter($sectoresArray, function($sector) {
                    return stripos($sector, $this->sector_search) !== false;
                });
            }
            
            sort($sectoresArray);
            $sectores = collect($sectoresArray);
        }

        return view('livewire.pages.admin.beneficiaries.create', [
            'parroquias' => $parroquias,
            'circuitos' => $circuitos,
            'sectores' => $sectores,
        ]);
    }
}
