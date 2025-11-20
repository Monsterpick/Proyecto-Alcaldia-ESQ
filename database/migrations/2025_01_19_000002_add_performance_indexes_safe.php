<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migration crea índices de forma segura (no falla si ya existen)
     */
    public function up(): void
    {
        // Helper para crear índice solo si no existe
        $createIndexIfNotExists = function ($table, $indexName, $columns) {
            $columnList = is_array($columns) ? implode(', ', $columns) : $columns;
            DB::statement("
                CREATE INDEX IF NOT EXISTS {$indexName} 
                ON {$table} ({$columnList})
            ");
        };

        // BENEFICIARIES
        $createIndexIfNotExists('beneficiaries', 'idx_beneficiaries_cedula', 'cedula');
        $createIndexIfNotExists('beneficiaries', 'idx_beneficiaries_names', ['first_name', 'last_name']);
        $createIndexIfNotExists('beneficiaries', 'idx_beneficiaries_status', 'status');
        $createIndexIfNotExists('beneficiaries', 'idx_beneficiaries_municipality', 'municipality');
        $createIndexIfNotExists('beneficiaries', 'idx_beneficiaries_parish', 'parish');

        // REPORTS
        $createIndexIfNotExists('reports', 'idx_reports_beneficiary_cedula', 'beneficiary_cedula');
        $createIndexIfNotExists('reports', 'idx_reports_delivery_date', 'delivery_date');
        $createIndexIfNotExists('reports', 'idx_reports_status', 'status');
        $createIndexIfNotExists('reports', 'idx_reports_user_id', 'user_id');
        $createIndexIfNotExists('reports', 'idx_reports_beneficiary_date', ['beneficiary_cedula', 'delivery_date']);

        // REPORT_ITEMS
        $createIndexIfNotExists('report_items', 'idx_report_items_report_id', 'report_id');
        $createIndexIfNotExists('report_items', 'idx_report_items_product_id', 'product_id');
        $createIndexIfNotExists('report_items', 'idx_report_items_warehouse_id', 'warehouse_id');

        // PRODUCTS
        $createIndexIfNotExists('products', 'idx_products_category_id', 'category_id');
        $createIndexIfNotExists('products', 'idx_products_name', 'name');

        // INVENTORIES
        $createIndexIfNotExists('inventories', 'idx_inventories_product_id', 'product_id');
        $createIndexIfNotExists('inventories', 'idx_inventories_warehouse_id', 'warehouse_id');
        $createIndexIfNotExists('inventories', 'idx_inventories_product_warehouse', ['product_id', 'warehouse_id']);

        // USERS
        $createIndexIfNotExists('users', 'idx_users_telegram_chat_id', 'telegram_chat_id');

        // ACTIVITY_LOG
        $createIndexIfNotExists('activity_log', 'idx_activity_log_causer_id', 'causer_id');
        $createIndexIfNotExists('activity_log', 'idx_activity_log_subject', ['subject_type', 'subject_id']);
        $createIndexIfNotExists('activity_log', 'idx_activity_log_created_at', 'created_at');
        $createIndexIfNotExists('activity_log', 'idx_activity_log_log_name', 'log_name');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacemos nada en down - los índices pueden quedarse
        // Si fuera necesario eliminarlos, se haría manualmente
    }
};
