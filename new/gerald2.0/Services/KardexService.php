<?php

namespace App\Services;

use App\Models\Inventory;

class KardexService
{
    public function getLastRecord($productId, $warehouseId)
    {

        $lastRecord = Inventory::where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->latest('id')
                ->first();

            return [
                'quantity' => $lastRecord?->quantity ?? 0,
                'cost' => $lastRecord?->cost ?? 0,
                'total' => $lastRecord?->total ?? 0,
                'date' => $lastRecord?->created_at ?? now(),
            ];
    }

    public function registerEntry($model, array $product, $warehouseId, $detail)
    {
        $lastRecord = $this->getLastRecord($product['id'], $warehouseId);

        $newQuantityBalance = $lastRecord['quantity'] + $product['quantity'];
        $newTotalBalance = $lastRecord['total'] + ($product['price'] * $product['quantity']);
        $newCostBalance = $newTotalBalance / ($newQuantityBalance ?: 1);

        $model->inventories()->create([
            'detail' => $detail,
            'quantity_in' => $product['quantity'],
            'cost_in' => $product['price'],
            'total_in' => $product['price'] * $product['quantity'],
            'quantity_out' => 0,
            'cost_out' => 0,
            'total_out' => 0,
            'quantity_balance' => $newQuantityBalance,
            'cost_balance' => $newCostBalance,
            'total_balance' => $newTotalBalance,
            'product_id' => $product['id'],
            'warehouse_id' => $warehouseId,
            /* 'inventoryable_id' => $model->id,
            'inventoryable_type' => get_class($model), */
        ]);
    }
}