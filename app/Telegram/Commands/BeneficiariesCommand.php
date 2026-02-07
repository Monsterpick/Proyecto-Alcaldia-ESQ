<?php

namespace App\Telegram\Commands;

use App\Models\Beneficiary;
use App\Models\Report;
use App\Traits\LogsActivity;
use App\Telegram\Traits\RequiresAuth;
use Telegram\Bot\Commands\Command;

class BeneficiariesCommand extends Command
{
    use LogsActivity, RequiresAuth;
    
    protected string $name = 'beneficiaries';
    protected string $description = 'Consultar información de beneficiarios';

    public function handle()
    {
        // Verificar autenticación
        $user = $this->requireAuth();
        if (!$user) {
            return;
        }
        
        // Obtener información del usuario
        $from = $this->getUpdate()->getMessage()->getFrom();
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        // Siempre mostrar el resumen cuando viene desde un botón
        $total = Beneficiary::count();
        $active = Beneficiary::where('status', 'active')->count();
        $inactive = Beneficiary::where('status', 'inactive')->count();
        
        // Mostrar todos los beneficiarios (limitado a 20 para no saturar)
        $beneficiaries = Beneficiary::orderBy('first_name')->orderBy('last_name')->take(20)->get();
        
        $text = "👥 *Lista de Beneficiarios*\n\n";
        $text .= "📊 *Estadísticas:*\n";
        $text .= "• Total: {$total}\n";
        $text .= "• ✅ Activos: {$active}\n";
        $text .= "• ❌ Inactivos: {$inactive}\n\n";
        $text .= "📋 *Beneficiarios registrados:*\n\n";
        
        foreach ($beneficiaries as $beneficiary) {
            $status = $beneficiary->status === 'active' ? '✅' : '❌';
            $text .= "{$status} *{$beneficiary->full_name}*\n";
            $text .= "   📝 {$beneficiary->full_cedula}\n";
            $text .= "   📍 {$beneficiary->municipality}, {$beneficiary->state}\n\n";
        }
        
        if ($total > 20) {
            $remaining = $total - 20;
            $text .= "_(Mostrando 20 de {$total}. Hay {$remaining} más...)_\n\n";
        }
        
        $text .= "💡 *Para buscar un beneficiario específico:*\n";
        $text .= "Usa el botón *🔍 Buscar Beneficiario*";

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);
        
        // Registrar actividad
        self::logTelegramActivity(
            'Consultó lista de beneficiarios',
            [
                'command' => 'beneficiaries',
                'total_beneficiaries' => $total,
                'active' => $active,
                'inactive' => $inactive,
            ],
            $telegramUser
        );
    }
}
