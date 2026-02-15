# Comandos de Instalación - Inertia.js + React

## 📋 Pasos para completar la instalación

### 1. Instalar dependencias de Composer

```bash
composer require inertiajs/inertia-laravel
```

### 2. Instalar dependencias de NPM

```bash
npm install
```

Esto instalará automáticamente:
- `@inertiajs/react` - Cliente React de Inertia
- `react` y `react-dom` - React 18
- `@vitejs/plugin-react` - Plugin de Vite para React

### 3. Ejecutar migraciones y seeders (si es necesario)

```bash
php artisan migrate
php artisan db:seed --class=ThemeSettingsSeeder
```

### 4. Compilar assets

**Desarrollo:**
```bash
npm run dev
```

**Producción:**
```bash
npm run build
```

### 5. Iniciar servidor Laravel

```bash
php artisan serve
```

O usar el comando combinado (si está configurado):
```bash
composer dev
```

## ✅ Verificación

Una vez completados los pasos, deberías poder:

1. Visitar `http://localhost:8000` y ver la página Welcome renderizada con React
2. Los colores y nombre del municipio se leen automáticamente desde `settings`
3. Cambiar valores en `settings` (ej: `primary_color`) y ver los cambios reflejados

## 🔧 Solución de problemas

### Error: "Cannot find module '@inertiajs/react'"
- Ejecuta `npm install` nuevamente
- Verifica que `package.json` tenga las dependencias correctas

### Error: "Inertia middleware not found"
- Verifica que `bootstrap/app.php` tenga el middleware configurado
- Ejecuta `php artisan config:clear` y `php artisan cache:clear`

### Los estilos no se cargan
- Ejecuta `npm run dev` o `npm run build`
- Verifica que `vite.config.js` tenga el plugin de React configurado

### Los colores no cambian
- Verifica que existan los registros en la tabla `settings`
- Ejecuta el seeder: `php artisan db:seed --class=ThemeSettingsSeeder`
- Limpia caché: `php artisan config:clear`

## 📝 Notas

- El sistema está diseñado para coexistir con Livewire/Blade
- Puedes migrar páginas gradualmente de Blade a React
- Las configuraciones de tema se leen automáticamente en todas las páginas React
- El middleware `HandleInertiaRequests` comparte `settings` globalmente
