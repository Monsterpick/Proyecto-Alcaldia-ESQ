<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Estos índices optimizan las consultas más frecuentes del sistema:
     * - Búsquedas de beneficiarios (bot de Telegram)
     * - Consultas de reportes por beneficiario
     * - Filtros por fecha y estado
     * - Relaciones entre tablas
     */
    public function up(): void
    {
        // BENEFICIARIES - Optimizar búsquedas del bot
        Schema::table('beneficiaries', function (Blueprint $table) {
            // Búsqueda por cédula (muy frecuente en bot)
            $table->index('cedula', 'idx_beneficiaries_cedula');
            
            // Búsqueda por nombre completo (inline queries)
            $table->index(['first_name', 'last_name'], 'idx_beneficiaries_names');
            
            // Filtro por estado (activo/inactivo)
            $table->index('status', 'idx_beneficiaries_status');
            
            // Filtros por ubicación (reportes por parroquia)
            $table->index('municipality', 'idx_beneficiaries_municipality');
            $table->index('parish', 'idx_beneficiaries_parish');
        });

        // REPORTS - Optimizar consultas de reportes
        Schema::table('reports', function (Blueprint $table) {
            // Buscar reportes por beneficiario (MUY frecuente)
            $table->index('beneficiary_cedula', 'idx_reports_beneficiary_cedula');
            
            // Ordenar por fecha de entrega
            $table->index('delivery_date', 'idx_reports_delivery_date');
            
            // Filtrar por estado
            $table->index('status', 'idx_reports_status');
            
            // Reportes por usuario que creó el reporte
            $table->index('user_id', 'idx_reports_user_id');
            
            // Índice compuesto para consultas complejas
            // Ejemplo: reportes de un beneficiario por fecha
            $table->index(['beneficiary_cedula', 'delivery_date'], 'idx_reports_beneficiary_date');
        });

        // REPORT_ITEMS - Optimizar relaciones
        Schema::table('report_items', function (Blueprint $table) {
            // Items de un reporte específico
            $table->index('report_id', 'idx_report_items_report_id');
            
            // Items de un producto específico (inventario)
            $table->index('product_id', 'idx_report_items_product_id');
            
            // Items por warehouse (almacén)
            $table->index('warehouse_id', 'idx_report_items_warehouse_id');
        });

        // PRODUCTS - Optimizar consultas de productos
        Schema::table('products', function (Blueprint $table) {
            // Productos por categoría
            $table->index('category_id', 'idx_products_category_id');
            
            // Búsqueda por nombre
            $table->index('name', 'idx_products_name');
            
            // Filtro por estado activo
            $table->index('is_active', 'idx_products_is_active');
        });

        // INVENTORIES - Optimizar consultas de inventario
        Schema::table('inventories', function (Blueprint $table) {
            // Inventario por producto
            $table->index('product_id', 'idx_inventories_product_id');
            
            // Inventario por almacén
            $table->index('warehouse_id', 'idx_inventories_warehouse_id');
            
            // Índice compuesto para consultas de stock
            $table->index(['product_id', 'warehouse_id'], 'idx_inventories_product_warehouse');
        });

        // USERS - Optimizar autenticación y búsquedas
        Schema::table('users', function (Blueprint $table) {
            // Búsqueda por telegram_chat_id (autenticación del bot)
            $table->index('telegram_chat_id', 'idx_users_telegram_chat_id');
            
            // Usuarios activos
            $table->index('is_active', 'idx_users_is_active');
        });

        // ACTIVITY_LOG - Optimizar consultas de auditoría
        Schema::table('activity_log', function (Blueprint $table) {
            // Logs por usuario
            $table->index('causer_id', 'idx_activity_log_causer_id');
            
            // Logs por entidad (subject)
            $table->index(['subject_type', 'subject_id'], 'idx_activity_log_subject');
            
            // Logs por fecha
            $table->index('created_at', 'idx_activity_log_created_at');
            
            // Logs de Telegram
            $table->index('log_name', 'idx_activity_log_log_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropIndex('idx_beneficiaries_cedula');
            $table->dropIndex('idx_beneficiaries_names');
            $table->dropIndex('idx_beneficiaries_status');
            $table->dropIndex('idx_beneficiaries_municipality');
            $table->dropIndex('idx_beneficiaries_parish');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('idx_reports_beneficiary_cedula');
            $table->dropIndex('idx_reports_delivery_date');
            $table->dropIndex('idx_reports_status');
            $table->dropIndex('idx_reports_user_id');
            $table->dropIndex('idx_reports_beneficiary_date');
        });

        Schema::table('report_items', function (Blueprint $table) {
            $table->dropIndex('idx_report_items_report_id');
            $table->dropIndex('idx_report_items_product_id');
            $table->dropIndex('idx_report_items_warehouse_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_category_id');
            $table->dropIndex('idx_products_name');
            $table->dropIndex('idx_products_is_active');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropIndex('idx_inventories_product_id');
            $table->dropIndex('idx_inventories_warehouse_id');
            $table->dropIndex('idx_inventories_product_warehouse');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_telegram_chat_id');
            $table->dropIndex('idx_users_is_active');
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropIndex('idx_activity_log_causer_id');
            $table->dropIndex('idx_activity_log_subject');
            $table->dropIndex('idx_activity_log_created_at');
            $table->dropIndex('idx_activity_log_log_name');
        });
    }
};
