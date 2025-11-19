#!/bin/bash

echo "🚀 Iniciando aplicación Laravel..."

# Variable para forzar reset completo (cambiar a false después del reset)
FORCE_RESET=false

if [ "$FORCE_RESET" = true ]; then
    echo "🔄 RESETEANDO BASE DE DATOS COMPLETAMENTE..."
    php artisan migrate:fresh --force
    
    echo "🌱 Cargando datos limpios del sistema..."
    
    # Seeders base
    php artisan db:seed --class=PermissionSeeder --force
    php artisan db:seed --class=RoleSeeder --force
    php artisan db:seed --class=SettingsSeeder --force
    
    # Datos geográficos
    php artisan db:seed --class=EstadoSeeder --force
    php artisan db:seed --class=MunicipioSeeder --force
    php artisan db:seed --class=ParroquiaSeeder --force
    php artisan db:seed --class=CircuitoComunalSeeder --force
    
    # Catálogos
    php artisan db:seed --class=EstatusSeeder --force
    php artisan db:seed --class=PaymentTypeSeeder --force
    php artisan db:seed --class=PaymentOriginSeeder --force
    php artisan db:seed --class=CategorySeeder --force
    php artisan db:seed --class=WarehouseSeeder --force
    
    # Usuario Super Admin
    php artisan db:seed --class=SuperAdminSeeder --force
    
    echo "✅ Base de datos reseteada y datos cargados correctamente"
else
    echo "📊 Ejecutando migraciones..."
    php artisan migrate --force
    echo "⏭️ Omitiendo reset - Base de datos mantenida"
fi

# Limpiar y optimizar
echo "🔧 Limpiando cachés..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "🔧 Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico de storage
echo "📁 Configurando storage..."
php artisan storage:link

# Configurar webhook de Telegram
echo "🤖 Configurando webhook de Telegram..."
php artisan telegram:setup-webhook

# Configurar Nginx para el puerto de Railway
echo "🌐 Configurando Nginx..."
sed -i "s/listen 8080;/listen ${PORT:-8080};/" /etc/nginx/sites-available/default

# Iniciar PHP-FPM en background
echo "⚙️ Iniciando PHP-FPM..."
php-fpm -D

# Iniciar Nginx en foreground
echo "✅ Iniciando Nginx..."
nginx -g "daemon off;"
