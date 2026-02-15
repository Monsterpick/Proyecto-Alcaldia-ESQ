Write-Host "Instalando Inertia.js para Laravel..." -ForegroundColor Cyan
Write-Host ""

# Intentar con composer global
$composerPath = Get-Command composer -ErrorAction SilentlyContinue
if ($composerPath) {
    Write-Host "Usando composer global..." -ForegroundColor Green
    composer require inertiajs/inertia-laravel
    exit
}

# Intentar con composer.phar local
if (Test-Path "composer.phar") {
    Write-Host "Usando composer.phar local..." -ForegroundColor Green
    php composer.phar require inertiajs/inertia-laravel
    exit
}

# Intentar descargar composer
Write-Host "Descargando composer..." -ForegroundColor Yellow
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
if (Test-Path "composer.phar") {
    php composer.phar require inertiajs/inertia-laravel
    Remove-Item composer-setup.php -ErrorAction SilentlyContinue
} else {
    Write-Host "Error: No se pudo instalar composer. Por favor instala composer manualmente." -ForegroundColor Red
    Write-Host "Visita: https://getcomposer.org/download/" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Siguiente paso: Ejecuta 'npm run dev' para compilar los assets" -ForegroundColor Green
