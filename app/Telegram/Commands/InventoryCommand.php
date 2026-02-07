<?php

namespace App\Telegram\Commands;

use App\Models\Product;
use App\Models\Inventory;
use App\Traits\LogsActivity;
use App\Telegram\Traits\RequiresAuth;
use Telegram\Bot\Commands\Command;

class InventoryCommand extends Command
{
    use LogsActivity, RequiresAuth;
    
    protected string $name = 'inventory';
    protected string $description = 'Ver estado del inventario';

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
        
        try {
            $totalProducts = Product::count();
            
            // Calcular stock de cada producto y detectar stock bajo
            $lowStock = 0;
            $productsWithStock = [];
            
            $products = Product::all();
            foreach ($products as $product) {
                // Obtener el stock del último registro de inventory para este producto
                // (mismo método que usa el sistema web)
                $inventory = Inventory::where('product_id', $product->id)
                    ->where('warehouse_id', 1) // Almacén por defecto
                    ->latest()
                    ->first();
                
                $totalStock = $inventory ? $inventory->quantity_balance : 0;
                
                $productsWithStock[] = [
                    'name' => $product->name,
                    'stock' => $totalStock,
                    'low' => $totalStock < 10
                ];
                
                if ($totalStock < 10) {
                    $lowStock++;
                }
            }
            
            // Ordenar productos por stock (menor a mayor)
            usort($productsWithStock, function($a, $b) {
                return $a['stock'] <=> $b['stock'];
            });
            
            // Obtener últimos 5 movimientos
            $recentMovements = Inventory::with(['product', 'warehouse'])
                ->latest()
                ->take(5)
                ->get();
            
            // Construir mensaje
            $text = "📦 *Estado del Inventario*\n\n";
            $text .= "📊 *Resumen:*\n";
            $text .= "• Total de productos: {$totalProducts}\n";
            $text .= "• ⚠️ Productos con stock bajo: {$lowStock}\n\n";
            
            // Mostrar productos con stock
            $text .= "📋 *Productos y Stock:*\n";
            foreach ($productsWithStock as $item) {
                $icon = $item['low'] ? '⚠️' : '✅';
                $text .= "{$icon} {$item['name']}: *{$item['stock']}* unidades\n";
            }
            
            // Mostrar últimos movimientos
            if ($recentMovements->count() > 0) {
                $text .= "\n🔄 *Últimos movimientos:*\n";
                foreach ($recentMovements as $movement) {
                    $product = $movement->product;
                    $warehouse = $movement->warehouse;
                    
                    if ($product && $warehouse) {
                        // Determinar tipo de movimiento con iconos claros
                        if ($movement->quantity_in > 0) {
                            $icon = '🟢'; // Verde para entradas
                            $action = 'ENTRADA';
                            $qty = $movement->quantity_in;
                        } else {
                            $icon = '🔴'; // Rojo para salidas
                            $action = 'SALIDA';
                            $qty = $movement->quantity_out;
                        }
                        
                        $text .= "{$icon} *{$action}*: {$product->name}\n";
                        $text .= "   └ Cantidad: {$qty} | {$warehouse->name}\n";
                    }
                }
            }
            
            $text .= "\n🕐 Actualizado: " . now()->format('d/m/Y H:i');

            $this->replyWithMessage([
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);
            
            // Registrar actividad
            self::logTelegramActivity(
                'Consultó estado del inventario',
                [
                    'command' => 'inventory',
                    'total_products' => $totalProducts,
                    'low_stock' => $lowStock,
                ],
                $telegramUser
            );
            
        } catch (\Exception $e) {
            // Registrar error
            self::logError('Error en comando de inventario de Telegram', $e, [
                'command' => 'inventory',
                'telegram_user' => $telegramUser,
            ]);
            $this->replyWithMessage([
                'text' => "❌ Error al obtener inventario: " . $e->getMessage(),
            ]);
        }
    }
}
