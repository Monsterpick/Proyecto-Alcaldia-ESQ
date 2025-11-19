#!/bin/bash

echo "🚀 Iniciando aplicación Laravel..."

# Esperar a que la base de datos esté lista
echo "⏳ Esperando base de datos..."
sleep 5

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders básicos si es necesario
# php artisan db:seed --class=SuperAdminSeeder --force

# Limpiar cachés
echo "🧹 Limpiando cachés..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cachear para producción
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

# Iniciar supervisor
echo "✅ Iniciando servicios..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
