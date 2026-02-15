@echo off
echo Instalando Inertia.js para Laravel...
echo.

REM Intentar con composer global
where composer >nul 2>&1
if %errorlevel% equ 0 (
    echo Usando composer global...
    composer require inertiajs/inertia-laravel
    goto :done
)

REM Intentar con composer.phar local
if exist composer.phar (
    echo Usando composer.phar local...
    php composer.phar require inertiajs/inertia-laravel
    goto :done
)

REM Intentar con php composer directamente
echo Intentando instalar con PHP...
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php composer.phar require inertiajs/inertia-laravel
php -r "unlink('composer-setup.php');"

:done
echo.
echo Instalacion completada!
echo.
echo Siguiente paso: Ejecuta 'npm run dev' para compilar los assets
pause
