<?php

namespace App\Services;

use App\Models\Departamento;
use App\Models\Director;
use Illuminate\Validation\ValidationException;

class DirectorDepartamentoService
{
    /**
     * Valida si un director puede asignarse a un departamento.
     * @throws ValidationException si el departamento ya tiene director activo
     */
    public function puedeAsignarDirectorADepartamento(Departamento $departamento): bool
    {
        if ($departamento->director && $departamento->director->activo) {
            throw ValidationException::withMessages([
                'departamento_input' => ['Ese departamento ya posee director asignado.'],
            ]);
        }
        return true;
    }

    /**
     * Indica si el director debe asignarse como temporal (principal inactivo).
     */
    public function debeSerTemporal(Departamento $departamento): bool
    {
        return $departamento->director && !$departamento->director->activo;
    }

    /**
     * Asigna un director a un departamento (como principal o temporal).
     */
    public function asignarDirector(Departamento $departamento, Director $director): void
    {
        $this->puedeAsignarDirectorADepartamento($departamento);

        if ($this->debeSerTemporal($departamento)) {
            $antiguoTemporal = $departamento->directorTemporal;
            $departamento->update(['director_temporal_id' => $director->id]);
            if ($antiguoTemporal) {
                $antiguoTemporal->update(['departamento_id' => null]);
            }
            $director->update(['departamento_id' => $departamento->id]);
        } else {
            $departamento->update(['director_id' => $director->id]);
            $director->update(['departamento_id' => $departamento->id, 'departamento_nombre_pendiente' => null]);
        }
    }

    /**
     * Quita un director del departamento (principal o temporal).
     */
    public function quitarDirectorDelDepartamento(Director $director): void
    {
        $dept = $director->departamento;
        if (!$dept) {
            return;
        }
        if ($dept->director_id == $director->id) {
            $dept->update(['director_id' => null]);
        }
        if ($dept->director_temporal_id == $director->id) {
            $dept->update(['director_temporal_id' => null]);
        }
        $director->update(['departamento_id' => null, 'departamento_nombre_pendiente' => null]);
    }

    /**
     * Promueve al director temporal como director principal.
     * Desvincula automáticamente al director inactivo si existía.
     */
    public function promoverTemporalAPrincipal(Director $director): bool
    {
        $dept = Departamento::with('director')->find($director->departamento_id);
        if (!$dept || $dept->director_temporal_id != $director->id) {
            return false;
        }
        if ($dept->director) {
            $dept->director->update(['departamento_id' => null]);
        }
        $dept->update([
            'director_id' => $director->id,
            'director_temporal_id' => null,
        ]);
        return true;
    }

    /**
     * Busca departamento por nombre (case-insensitive).
     */
    public function buscarDepartamentoPorNombre(string $nombre): ?Departamento
    {
        return Departamento::whereRaw('LOWER(TRIM(nombre)) = ?', [strtolower(trim($nombre))])->first();
    }

    /**
     * Auto-asigna director pendiente cuando se crea un departamento con ese nombre.
     */
    public function autoAsignarDirectorPendiente(Departamento $departamento): ?Director
    {
        $nombreBusqueda = trim($departamento->nombre);
        $directorPendiente = Director::whereRaw('LOWER(TRIM(departamento_nombre_pendiente)) = ?', [strtolower($nombreBusqueda)])
            ->first();
        if ($directorPendiente) {
            $departamento->update(['director_id' => $directorPendiente->id]);
            $directorPendiente->update([
                'departamento_id' => $departamento->id,
                'departamento_nombre_pendiente' => null,
            ]);
            return $directorPendiente;
        }
        return null;
    }
}
