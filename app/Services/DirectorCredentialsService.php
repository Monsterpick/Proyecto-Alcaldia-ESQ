<?php

namespace App\Services;

use App\Models\Director;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DirectorCredentialsService
{
    public function __construct(
        protected EvolutionService $evolution
    ) {}

    /**
     * Crear o actualizar usuario para el director y enviar credenciales por WhatsApp
     */
    public function crearUsuarioYEnviarCredenciales(Director $director): User
    {
        $password = Str::random(10);
        $user = User::where('email', $director->email)->first();

        if ($user) {
            // El email ya existe: actualizar contraseña, rol y enlace
            $user->update([
                'name' => $director->nombre,
                'last_name' => trim(($director->apellido ?? '') . ' ' . ($director->segundo_apellido ?? '')),
                'password' => $password,
            ]);
            if ($user->director && $user->director->id !== $director->id) {
                $user->director->update(['user_id' => null]);
            }
        } else {
            $user = User::create([
                'name' => $director->nombre,
                'last_name' => trim(($director->apellido ?? '') . ' ' . ($director->segundo_apellido ?? '')),
                'email' => $director->email,
                'password' => $password,
                'email_verified_at' => now(),
            ]);
        }

        $user->syncRoles(['Director']);
        $director->update(['user_id' => $user->id]);

        $this->enviarCredencialesWhatsApp($director, $user->email, $password, false);
        return $user;
    }

    /**
     * Enviar credenciales por WhatsApp (creación o actualización)
     */
    public function enviarCredencialesWhatsApp(Director $director, string $email, string $password, bool $esActualizacion = false): void
    {
        $numero = $director->getWhatsappNormalizado();
        if (empty($numero)) {
            Log::warning('Director sin WhatsApp - no se enviaron credenciales', [
                'director_id' => $director->id,
                'email' => $email,
            ]);
            return;
        }

        $titulo = $esActualizacion ? '🔐 *Credenciales actualizadas*' : '🔐 *Credenciales de acceso al sistema*';
        $mensaje = $titulo . "\n\n";
        $mensaje .= "Estimado/a *{$director->nombre_completo}*,\n\n";
        if ($esActualizacion) {
            $mensaje .= "Sus credenciales han sido actualizadas correctamente.\n\n";
        } else {
            $mensaje .= "Se le han asignado credenciales para acceder al sistema de solicitudes de su departamento.\n\n";
        }
        $mensaje .= "📧 *Usuario (email):* {$email}\n";
        $mensaje .= "🔑 *Contraseña:* {$password}\n\n";
        $mensaje .= "Acceda en: " . config('app.url') . "/login\n\n";
        $mensaje .= "Por seguridad, le recomendamos cambiar su contraseña al primer acceso.";

        try {
            $this->evolution->sendMessage($numero, $mensaje);
            Log::info('Credenciales enviadas por WhatsApp al director', [
                'director_id' => $director->id,
                'es_actualizacion' => $esActualizacion,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar credenciales por WhatsApp', [
                'director_id' => $director->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
