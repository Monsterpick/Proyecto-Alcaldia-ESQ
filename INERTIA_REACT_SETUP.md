# Configuración Inertia.js + React

Este proyecto ahora usa **Inertia.js + React** para facilitar la personalización por alcaldía.

## 🚀 Instalación

### 1. Instalar dependencias de Composer (Laravel)

```bash
composer require inertiajs/inertia-laravel
```

### 2. Instalar dependencias de NPM (React + Inertia)

```bash
npm install
```

Esto instalará automáticamente:
- `@inertiajs/react`
- `react` y `react-dom`
- `@vitejs/plugin-react`

### 3. Compilar assets

```bash
npm run dev
# o para producción:
npm run build
```

## 📁 Estructura de Archivos

```
resources/
├── js/
│   ├── app.js                    # Punto de entrada de Inertia
│   ├── bootstrap.js              # Configuración de Axios
│   ├── Pages/                    # Páginas React (equivalente a vistas Blade)
│   │   └── Welcome.jsx
│   ├── Layouts/                  # Layouts reutilizables
│   │   └── Layout.jsx
│   └── Components/               # Componentes React reutilizables
│       ├── Theme/
│       │   └── ThemeProvider.jsx  # Proveedor de tema (colores, settings)
│       ├── Layout/
│       │   └── Navbar.jsx
│       └── Welcome/
│           ├── Hero.jsx
│           ├── StatsBar.jsx
│           ├── QuickAccess.jsx
│           ├── Services.jsx
│           ├── SolicitudForm.jsx
│           ├── Contact.jsx
│           └── Footer.jsx
└── views/
    └── app.blade.php             # Root template de Inertia

app/
└── Http/
    ├── Controllers/
    │   └── WelcomeController.php # Controlador que usa Inertia::render()
    └── Middleware/
        └── HandleInertiaRequests.php # Middleware que comparte datos con React
```

## 🎨 Sistema de Personalización por Alcaldía

### Configuración desde Settings

El sistema lee automáticamente las siguientes configuraciones desde la tabla `settings`:

- `primary_color`: Color principal (por defecto: `#b91c1c` - rojo Escuque)
- `secondary_color`: Color secundario (por defecto: `#d97706` - dorado Escuque)
- `accent_color`: Color de acento (por defecto: `#059669` - verde)
- `municipality_name`: Nombre del municipio
- `logo_url`: URL del logo
- `favicon_url`: URL del favicon
- `phone`: Teléfono de contacto
- `email`: Email de contacto
- `address`: Dirección
- `whatsapp`: Número de WhatsApp
- `horario_atencion`: Horario de atención

### Cómo personalizar para otra alcaldía

1. **Desde el panel admin** (si existe):
   - Ir a Configuración → General
   - Actualizar los valores de colores, nombre, logo, etc.

2. **Desde la base de datos**:
   ```php
   Setting::set('primary_color', '#1e40af'); // Azul para otra alcaldía
   Setting::set('municipality_name', 'Municipio Ejemplo');
   Setting::set('logo_url', '/storage/logos/ejemplo.png');
   ```

3. **Desde un seeder** (para configuraciones iniciales):
   ```php
   Setting::create([
       'key' => 'primary_color',
       'value' => '#1e40af',
       'type' => 'string',
       'group' => 'theme',
       'name' => 'Color Principal',
   ]);
   ```

## 🔧 Uso de Componentes

### En una página React:

```jsx
import { useTheme } from '@/Components/Theme/ThemeProvider';

export default function MiPagina() {
    const theme = useTheme();
    
    return (
        <div style={{ color: theme.colors.primary }}>
            {theme.municipality.name}
        </div>
    );
}
```

### Crear una nueva página:

1. Crear componente en `resources/js/Pages/MiPagina.jsx`:
```jsx
import Layout from '@/Layouts/Layout';

export default function MiPagina({ settings }) {
    return (
        <Layout settings={settings}>
            <h1>Mi Página</h1>
        </Layout>
    );
}
```

2. Crear controlador en `app/Http/Controllers/MiPaginaController.php`:
```php
use Inertia\Inertia;

public function index() {
    return Inertia::render('MiPagina');
}
```

3. Agregar ruta en `routes/web.php`:
```php
Route::get('/mi-pagina', [MiPaginaController::class, 'index']);
```

## 🎯 Ventajas de este enfoque

1. **Fácil personalización**: Solo cambiar Settings en BD y los colores/nombre se actualizan automáticamente
2. **Componentes reutilizables**: Los componentes React pueden usarse en múltiples páginas
3. **Coexistencia**: Puedes seguir usando Livewire/Blade en otras partes mientras migras gradualmente
4. **Sin API pública**: Inertia maneja todo internamente, no necesitas crear endpoints REST
5. **SEO friendly**: El HTML inicial se renderiza en el servidor

## 📝 Próximos pasos

1. Migrar más páginas de Blade/Livewire a React según necesidad
2. Crear más componentes reutilizables (botones, cards, formularios)
3. Agregar más configuraciones personalizables en Settings
4. Crear un panel admin para editar configuraciones visualmente

## ⚠️ Notas importantes

- Los componentes React usan Tailwind CSS (ya configurado)
- Los estilos dinámicos (colores) se aplican con `style={{ color: theme.colors.primary }}`
- El middleware `HandleInertiaRequests` comparte automáticamente `settings` con todas las páginas
- Puedes seguir usando Livewire/Blade en otras rutas sin problema
