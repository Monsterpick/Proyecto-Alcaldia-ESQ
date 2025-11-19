<?php

namespace App\Telegram\Commands;

use App\Models\Beneficiary;
use App\Models\Report;
use App\Models\Product;
use App\Traits\LogsActivity;
use App\Telegram\Traits\RequiresAuth;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;

class StatsCommand extends Command
{
    use LogsActivity, RequiresAuth;
    
    protected string $name = 'stats';
    protected string $description = 'Ver estadísticas del sistema';

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
        // Obtener estadísticas globales
        $totalBeneficiaries = Beneficiary::count();
        $activeBeneficiaries = Beneficiary::where('status', 'active')->count();
        $inactiveBeneficiaries = Beneficiary::where('status', 'inactive')->count();
        
        $totalReports = Report::count();
        $deliveredReports = Report::where('status', 'delivered')->count();
        $inProcessReports = Report::where('status', 'in_process')->count();
        $notDeliveredReports = Report::where('status', 'not_delivered')->count();
        
        $totalProducts = Product::count();
        
        // Obtener estadísticas por parroquia
        $parishes = ['Sabana Libre', 'La Unión', 'Santa Rita', 'Escuque'];
        $parishStats = [];
        
        foreach ($parishes as $parish) {
            $parishBeneficiaries = Beneficiary::whereHas('parroquia', function($q) use ($parish) {
                $q->where('parroquia', $parish);
            })->count();
            
            $parishReports = Report::where('parish', $parish)->count();
            
            $parishStats[$parish] = [
                'beneficiaries' => $parishBeneficiaries,
                'reports' => $parishReports,
            ];
        }

        // Generar URL del gráfico de Beneficiarios
        $beneficiariesChart = $this->generatePieChart(
            'Beneficiarios',
            ['Activos', 'Inactivos'],
            [$activeBeneficiaries, $inactiveBeneficiaries],
            ['#10b981', '#ef4444']
        );
        
        // Generar URL del gráfico de Reportes
        $reportsChart = $this->generatePieChart(
            'Reportes de Entrega',
            ['Entregados', 'En proceso', 'No entregados'],
            [$deliveredReports, $inProcessReports, $notDeliveredReports],
            ['#10b981', '#f59e0b', '#ef4444']
        );

        // Enviar texto con estadísticas globales
        $text = "📊 *Estadísticas Globales del Sistema*\n\n";
        $text .= "🌎 *RESUMEN GENERAL*\n\n";
        
        $text .= "👥 *Beneficiarios:*\n";
        $text .= "   • Total: {$totalBeneficiaries}\n";
        $text .= "   • ✅ Activos: {$activeBeneficiaries}\n";
        $text .= "   • ❌ Inactivos: {$inactiveBeneficiaries}\n\n";
        
        $text .= "📦 *Reportes de Entrega:*\n";
        $text .= "   • Total: {$totalReports}\n";
        $text .= "   • ✅ Entregados: {$deliveredReports}\n";
        $text .= "   • 🔄 En proceso: {$inProcessReports}\n";
        $text .= "   • ❌ No entregados: {$notDeliveredReports}\n\n";
        
        $text .= "📋 *Productos:*\n";
        $text .= "   • Total: {$totalProducts}\n\n";
        
        $text .= "📍 *ESTADÍSTICAS POR PARROQUIA*\n\n";
        
        foreach ($parishStats as $parish => $stats) {
            $text .= "• *{$parish}:*\n";
            $text .= "   👥 Beneficiarios: {$stats['beneficiaries']}\n";
            $text .= "   📦 Reportes: {$stats['reports']}\n\n";
        }
        
        $text .= "📈 *Gráficos a continuación...*\n";
        $text .= "🕐 Actualizado: " . now()->format('d/m/Y H:i');

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);
        
        // Registrar actividad
        self::logTelegramActivity(
            'Consultó estadísticas del sistema',
            [
                'command' => 'stats',
                'stats' => [
                    'beneficiaries' => $totalBeneficiaries,
                    'reports' => $totalReports,
                    'products' => $totalProducts,
                ]
            ],
            $telegramUser
        );
        
        // Obtener chat_id
        $chatId = $this->getUpdate()->getMessage() 
            ? $this->getUpdate()->getMessage()->getChat()->getId() 
            : $this->getUpdate()->getCallbackQuery()->getMessage()->getChat()->getId();
        
        // Enviar gráfico de Beneficiarios
        if ($totalBeneficiaries > 0) {
            Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create($beneficiariesChart),
                'caption' => '📊 Gráfico de Beneficiarios (Global)',
            ]);
        }
        
        // Enviar gráfico de Reportes
        if ($totalReports > 0) {
            Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create($reportsChart),
                'caption' => '📦 Gráfico de Reportes de Entrega (Global)',
            ]);
        }
        
        // Generar y enviar gráfico de comparación por parroquias
        if ($totalReports > 0) {
            $parishNames = array_keys($parishStats);
            $parishReportCounts = array_column($parishStats, 'reports');
            
            $parishComparisonChart = $this->generateBarChart(
                'Reportes por Parroquia',
                $parishNames,
                $parishReportCounts,
                '#3b82f6'
            );
            
            Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create($parishComparisonChart),
                'caption' => '📍 Comparación de Reportes por Parroquia',
            ]);
        }
    }
    
    /**
     * Generar URL de gráfico tipo pastel usando QuickChart
     */
    private function generatePieChart($title, $labels, $data, $colors)
    {
        $chart = [
            'type' => 'pie',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => $data,
                        'backgroundColor' => $colors,
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => $title,
                    'fontSize' => 18,
                    'fontColor' => '#333',
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'fontSize' => 14,
                        'fontColor' => '#333',
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => '#fff',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14,
                        ],
                        'formatter' => null // Se mostrará el valor
                    ]
                ]
            ]
        ];
        
        $chartJson = json_encode($chart);
        $chartEncoded = urlencode($chartJson);
        
        return "https://quickchart.io/chart?c={$chartEncoded}&width=500&height=300&backgroundColor=white";
    }
    
    /**
     * Generar URL de gráfico de barras usando QuickChart
     */
    private function generateBarChart($title, $labels, $data, $color)
    {
        $chart = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => $title,
                        'data' => $data,
                        'backgroundColor' => $color,
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => $title,
                    'fontSize' => 18,
                    'fontColor' => '#333',
                ],
                'legend' => [
                    'display' => false,
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                                'fontColor' => '#333',
                            ]
                        ]
                    ],
                    'xAxes' => [
                        [
                            'ticks' => [
                                'fontColor' => '#333',
                            ]
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => '#fff',
                        'anchor' => 'end',
                        'align' => 'top',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14,
                        ],
                    ]
                ]
            ]
        ];
        
        $chartJson = json_encode($chart);
        $chartEncoded = urlencode($chartJson);
        
        return "https://quickchart.io/chart?c={$chartEncoded}&width=600&height=400&backgroundColor=white";
    }
}
