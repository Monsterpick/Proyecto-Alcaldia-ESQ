<?php

namespace App\Telegram\Commands;

use App\Models\Report;
use App\Traits\LogsActivity;
use App\Telegram\Traits\RequiresAuth;
use Telegram\Bot\Commands\Command;

class ReportsCommand extends Command
{
    use LogsActivity, RequiresAuth;
    
    protected string $name = 'reports';
    protected string $description = 'Ver reportes de entregas';

    public function handle()
    {
        // Verificar autenticación
        $user = $this->requireAuth();
        if (!$user) {
            return;
        }
        
        // Obtener información del usuario
        $from = $this->getUpdate()->getMessage() 
            ? $this->getUpdate()->getMessage()->getFrom()
            : $this->getUpdate()->getCallbackQuery()->getFrom();
        
        $telegramUser = [
            'id' => $from->getId(),
            'username' => $from->getUsername(),
            'first_name' => $from->getFirstName(),
            'last_name' => $from->getLastName(),
        ];
        // Obtener el texto completo del mensaje
        $message = $this->getUpdate()->getMessage();
        $callbackQuery = $this->getUpdate()->getCallbackQuery();
        
        // Si viene desde un callback (botón), no hay argumentos
        if ($callbackQuery) {
            $arguments = '';
        } else {
            $fullText = $message ? $message->getText() : '';
            
            // Lista de textos de botones del teclado que deben ser ignorados
            $buttonTexts = ['📦 Reportes', '🧾 Reportes', 'Reportes'];
            
            // Si el mensaje completo es un botón del teclado, no hay argumentos
            if (in_array($fullText, $buttonTexts)) {
                $arguments = '';
            } else {
                // Extraer el argumento (lo que viene después del comando)
                $parts = explode(' ', $fullText, 2);
                $arguments = isset($parts[1]) ? trim($parts[1]) : '';
            }
        }
        
        if (empty($arguments)) {
            // Mostrar resumen de reportes
            $total = Report::count();
            $delivered = Report::where('status', 'delivered')->count();
            $inProcess = Report::where('status', 'in_process')->count();
            $notDelivered = Report::where('status', 'not_delivered')->count();
            
            $recent = Report::latest()->take(5)->get();
            
            $text = "📦 *Reportes de Entrega*\n\n";
            $text .= "📊 *Resumen:*\n";
            $text .= "• Total: {$total}\n";
            $text .= "• ✅ Entregados: {$delivered}\n";
            $text .= "• 🔄 En proceso: {$inProcess}\n";
            $text .= "• ❌ No entregados: {$notDelivered}\n\n";
            
            $text .= "📋 *Últimos 5 reportes:*\n\n";
            foreach ($recent as $report) {
                $status = match($report->status) {
                    'delivered' => '✅',
                    'in_process' => '🔄',
                    'not_delivered' => '❌',
                    default => '❓'
                };
                $beneficiaryName = trim($report->beneficiary_first_name . ' ' . $report->beneficiary_last_name);
                $text .= "{$status} *{$report->report_code}*\n";
                $text .= "   👤 {$beneficiaryName}\n";
                $text .= "   📅 " . $report->delivery_date->format('d/m/Y') . "\n";
                $text .= "   📍 {$report->municipality}\n\n";
            }
            
            $text .= "\n💡 *Ver reporte específico:*\n";
            $text .= "Usa: `/reports RPT-20251026-0001`";
            
        } else {
            // Buscar reporte específico
            $report = Report::where('report_code', $arguments)->first();
            
            if ($report) {
                $status = match($report->status) {
                    'delivered' => '✅ Entregado',
                    'in_process' => '🔄 En proceso',
                    'not_delivered' => '❌ No entregado',
                    default => '❓ Desconocido'
                };
                
                $beneficiaryName = trim($report->beneficiary_first_name . ' ' . $report->beneficiary_last_name);
                $beneficiaryCedula = $report->beneficiary_document_type . '-' . $report->beneficiary_cedula;
                
                $text = "📦 *Detalle del Reporte*\n\n";
                $text .= "🔖 *Código:* {$report->report_code}\n";
                $text .= "📊 *Estado:* {$status}\n\n";
                
                $text .= "👤 *Beneficiario:*\n";
                $text .= "• Nombre: {$beneficiaryName}\n";
                $text .= "• Cédula: {$beneficiaryCedula}\n";
                
                if ($report->beneficiary_phone) {
                    $text .= "• Teléfono: {$report->beneficiary_phone}\n";
                }
                
                $text .= "\n📅 *Fecha de entrega:* " . $report->delivery_date->format('d/m/Y') . "\n\n";
                
                $text .= "📍 *Ubicación:*\n";
                $text .= "• Municipio: {$report->municipality}\n";
                $text .= "• Estado: {$report->state}\n";
                $text .= "• Circuito Comunal: {$report->communal_circuit}\n";
                
                if ($report->parish) {
                    $text .= "• Parroquia: {$report->parish}\n";
                }
                
                if ($report->address) {
                    $text .= "• Dirección: {$report->address}\n";
                }
                
                // Obtener items del reporte
                $items = \App\Models\ReportItem::where('report_id', $report->id)->with('product')->get();
                
                if ($items->count() > 0) {
                    $text .= "\n📦 *Productos entregados:*\n";
                    foreach ($items as $item) {
                        if ($item->product) {
                            $text .= "• {$item->product->name}: {$item->quantity} unidades\n";
                        }
                    }
                }
                
                if ($report->delivery_detail) {
                    $text .= "\n📝 *Detalle de entrega:*\n{$report->delivery_detail}\n";
                }
                
                if ($report->notes) {
                    $text .= "\n💬 *Notas:*\n{$report->notes}\n";
                }
                
                $text .= "\n🕐 Creado: " . $report->created_at->format('d/m/Y H:i');
            } else {
                $text = "❌ No se encontró el reporte con código: {$arguments}";
            }
        }

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);
        
        // Registrar actividad
        if (empty($arguments)) {
            self::logTelegramActivity(
                'Consultó resumen de reportes',
                [
                    'command' => 'reports',
                    'total_reports' => $total ?? 0,
                    'delivered' => $delivered ?? 0,
                    'in_process' => $inProcess ?? 0,
                ],
                $telegramUser
            );
        } else {
            self::logTelegramActivity(
                'Consultó detalle de reporte',
                [
                    'command' => 'reports',
                    'report_code' => $arguments,
                    'found' => isset($report),
                ],
                $telegramUser
            );
        }
    }
}
