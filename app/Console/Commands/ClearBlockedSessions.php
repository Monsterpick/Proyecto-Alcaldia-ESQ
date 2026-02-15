<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearBlockedSessions extends Command
{
    protected $signature = 'sessions:clear-blocked {email?}';
    protected $description = 'Limpiar active_session_id de usuarios bloqueados (ej: tras cerrar navegador)';

    public function handle(): int
    {
        $email = $this->argument('email');
        $query = User::query();

        if ($email) {
            $query->where('email', $email);
        } else {
            $query->whereHas('roles', fn ($q) => $q->whereIn('name', ['Alcalde', 'Analista', 'Operador']));
        }

        $count = $query->whereNotNull('active_session_id')->update([
            'active_session_id' => null,
            'session_last_activity' => null,
        ]);

        $this->info("Sesiones limpiadas: {$count} usuario(s).");
        return 0;
    }
}
