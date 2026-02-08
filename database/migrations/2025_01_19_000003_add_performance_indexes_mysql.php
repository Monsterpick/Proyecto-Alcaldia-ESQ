<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Crea índices de forma segura en MySQL (verifica existencia antes de crear)
     */
    public function up(): void
    {
        // Solo ejecutar en MySQL (producción/local). En SQLite (tests) se omite.
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $indexExists = function ($table, $indexName) {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return count($indexes) > 0;
        };

        // BENEFICIARIES - Optimizar búsquedas del bot
        Schema::table('beneficiaries', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('beneficiaries', 'idx_beneficiaries_cedula')) {
                $table->index('cedula', 'idx_beneficiaries_cedula');
            }
            if (!$indexExists('beneficiaries', 'idx_beneficiaries_names')) {
                $table->index(['first_name', 'last_name'], 'idx_beneficiaries_names');
            }
            if (!$indexExists('beneficiaries', 'idx_beneficiaries_status')) {
                $table->index('status', 'idx_beneficiaries_status');
            }
            if (!$indexExists('beneficiaries', 'idx_beneficiaries_municipality')) {
                $table->index('municipality', 'idx_beneficiaries_municipality');
            }
            if (!$indexExists('beneficiaries', 'idx_beneficiaries_parroquia_id')) {
                $table->index('parroquia_id', 'idx_beneficiaries_parroquia_id');
            }
        });

        // REPORTS - Optimizar consultas de reportes
        Schema::table('reports', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('reports', 'idx_reports_beneficiary_cedula')) {
                $table->index('beneficiary_cedula', 'idx_reports_beneficiary_cedula');
            }
            if (!$indexExists('reports', 'idx_reports_delivery_date')) {
                $table->index('delivery_date', 'idx_reports_delivery_date');
            }
            if (!$indexExists('reports', 'idx_reports_status')) {
                $table->index('status', 'idx_reports_status');
            }
            if (!$indexExists('reports', 'idx_reports_created_by')) {
                $table->index('created_by', 'idx_reports_created_by');
            }
        });

        // REPORT_ITEMS - Optimizar relaciones
        Schema::table('report_items', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('report_items', 'idx_report_items_report_id')) {
                $table->index('report_id', 'idx_report_items_report_id');
            }
            if (!$indexExists('report_items', 'idx_report_items_product_id')) {
                $table->index('product_id', 'idx_report_items_product_id');
            }
            if (!$indexExists('report_items', 'idx_report_items_warehouse_id')) {
                $table->index('warehouse_id', 'idx_report_items_warehouse_id');
            }
        });

        // PRODUCTS - Optimizar consultas de productos
        Schema::table('products', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('products', 'idx_products_category_id')) {
                $table->index('category_id', 'idx_products_category_id');
            }
            if (!$indexExists('products', 'idx_products_name')) {
                $table->index('name', 'idx_products_name');
            }
        });

        // INVENTORIES - Optimizar consultas de inventario
        Schema::table('inventories', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('inventories', 'idx_inventories_product_id')) {
                $table->index('product_id', 'idx_inventories_product_id');
            }
            if (!$indexExists('inventories', 'idx_inventories_warehouse_id')) {
                $table->index('warehouse_id', 'idx_inventories_warehouse_id');
            }
        });

        // USERS - Optimizar autenticación
        Schema::table('users', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('users', 'idx_users_telegram_chat_id')) {
                $table->index('telegram_chat_id', 'idx_users_telegram_chat_id');
            }
        });

        // ACTIVITY_LOG - Optimizar consultas de auditoría
        Schema::table('activity_log', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('activity_log', 'idx_activity_log_causer_id')) {
                $table->index('causer_id', 'idx_activity_log_causer_id');
            }
            if (!$indexExists('activity_log', 'idx_activity_log_created_at')) {
                $table->index('created_at', 'idx_activity_log_created_at');
            }
            if (!$indexExists('activity_log', 'idx_activity_log_log_name')) {
                $table->index('log_name', 'idx_activity_log_log_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropIndex('idx_beneficiaries_cedula');
            $table->dropIndex('idx_beneficiaries_names');
            $table->dropIndex('idx_beneficiaries_status');
            $table->dropIndex('idx_beneficiaries_municipality');
            $table->dropIndex('idx_beneficiaries_parroquia_id');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('idx_reports_beneficiary_cedula');
            $table->dropIndex('idx_reports_delivery_date');
            $table->dropIndex('idx_reports_status');
            $table->dropIndex('idx_reports_created_by');
        });

        Schema::table('report_items', function (Blueprint $table) {
            $table->dropIndex('idx_report_items_report_id');
            $table->dropIndex('idx_report_items_product_id');
            $table->dropIndex('idx_report_items_warehouse_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_category_id');
            $table->dropIndex('idx_products_name');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropIndex('idx_inventories_product_id');
            $table->dropIndex('idx_inventories_warehouse_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_telegram_chat_id');
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropIndex('idx_activity_log_causer_id');
            $table->dropIndex('idx_activity_log_created_at');
            $table->dropIndex('idx_activity_log_log_name');
        });
    }
};
