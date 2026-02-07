#!/bin/bash

echo "🚀 Iniciando Sistema de Gestión..."

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté disponible..."
max_tries=30
count=0
until php artisan db:monitor 2>/dev/null || [ $count -eq $max_tries ]; do
    count=$((count + 1))
    echo "   Intento $count/$max_tries..."
    sleep 2
done

if [ $count -eq $max_tries ]; then
    echo "⚠️ MySQL no está disponible después de 60 segundos"
    exit 1
fi

echo "✅ MySQL está listo!"

# Verificar si las variables de entorno están configuradas
echo "🔍 Verificando configuración..."

# Verificar si se debe resetear la base de datos
if [ "$RESET_DB" = "true" ]; then
    echo "🔄 Reseteando base de datos..."
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
    
    # Datos de dashboard (IMPORTANTE PARA GRÁFICOS)
    php artisan db:seed --class=DashboardDataSeeder --force
    
    echo "✅ Base de datos reseteada y datos cargados correctamente"
else
    echo "📊 Ejecutando migraciones..."
    php artisan migrate --force
    echo "⏭️ Omitiendo reset - Base de datos mantenida"
fi

# Limpiar cachés (SIN optimize:clear para evitar errores de BD)
echo "🔧 Limpiando cachés..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true

# Limpiar archivos de caché manualmente
rm -rf bootstrap/cache/*.php 2>/dev/null || true
rm -rf storage/framework/cache/data/* 2>/dev/null || true
rm -rf storage/framework/views/*.php 2>/dev/null || true

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
