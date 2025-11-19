#!/bin/bash

echo "🚀 Iniciando aplicación..."

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Limpiar y cachear configuración
echo "🔧 Optimizando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico de storage
php artisan storage:link

# Configurar webhook de Telegram automáticamente
echo "🤖 Configurando webhook de Telegram..."
php artisan telegram:setup-webhook

# Iniciar servidor PHP con configuración correcta para archivos estáticos
echo "✅ Aplicación lista!"
php -S 0.0.0.0:${PORT:-8080} -t public public/index.php
