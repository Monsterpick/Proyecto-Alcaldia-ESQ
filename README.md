# Sistema Web de Gestion de la Alcaldia del Municipio Escuque

Sistema integral de gestión y control de beneficios sociales desarrollado con Laravel y Livewire.

## 🎯 Descripción

Sistema Web de Gestion de la Alcaldia del Municipio Escuque es un sistema especializado para la administración eficiente de programas de ayuda social, garantizando transparencia y acceso equitativo a los beneficiarios.

## ✨ Características principales

- **Gestión de Beneficiarios**: Control completo de beneficiarios y sus datos
- **Administración de Coordinadores**: Gestión de personal encargado de programas sociales
- **Control de Pagos**: Sistema de registro y seguimiento de pagos y beneficios
- **Reportes y Estadísticas**: Dashboards con métricas en tiempo real
- **Sistema de Roles y Permisos**: Control granular de acceso con Spatie Permission
- **Auditoría**: Registro completo de actividades con Spatie Activity Log
- **Multi-tenancy**: Soporte para múltiples organizaciones
- **Geolocalización**: Gestión de estados, municipios y parroquias de Venezuela

## 🛠️ Stack Tecnológico

### Backend
- **Laravel 12**: Framework PHP moderno
- **Livewire Volt**: Componentes reactivos full-stack
- **Livewire Flux**: Biblioteca de componentes UI
- **Spatie Permission**: Sistema de roles y permisos
- **Spatie Activity Log**: Auditoría de actividades
- **Laravel Sanctum**: Autenticación API
- **Laravel Reverb**: WebSockets en tiempo real
- **DomPDF**: Generación de PDFs
- **OpenSpout**: Exportación Excel/CSV

### Frontend
- **Vite 6**: Build tool moderno
- **Tailwind CSS 4**: Framework CSS utility-first
- **Alpine.js**: Framework JavaScript reactivo
- **WireUI**: Componentes Livewire pre-construidos
- **PowerGrid**: Tablas de datos avanzadas
- **Flowbite**: Componentes UI
- **FullCalendar**: Calendario interactivo
- **SweetAlert2**: Alertas elegantes
- **Dropzone**: Carga de archivos drag & drop
- **Lightweight Charts**: Gráficos de rendimiento
- **FontAwesome**: Iconos
- **AOS**: Animaciones on scroll

## 📋 Requisitos

- PHP >= 8.2
- Composer
- Node.js >= 18
- NPM o Yarn
- Base de datos (MySQL, PostgreSQL o SQLite)

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone [URL_DEL_REPOSITORIO]
cd nevora_base
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias JavaScript

```bash
npm install
```

### 4. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurar la base de datos

Edita el archivo `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=sqlite
# O para MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=escuque_db
# DB_USERNAME=root
# DB_PASSWORD=
```

### 6. Ejecutar migraciones y seeders

```bash
php artisan migrate:fresh --seed
```

Este comando creará todas las tablas necesarias y poblará la base de datos con:
- Roles predefinidos (Super Admin, Administrador, Coordinador, Operador, Beneficiario)
- Permisos del sistema
- Usuarios de prueba
- Configuración inicial
- Datos geográficos de Venezuela (estados, municipios, parroquias)
- Tipos y orígenes de pago

### 7. Compilar assets

Para desarrollo:
```bash
npm run dev
```

Para producción:
```bash
npm run build
```

### 8. Iniciar el servidor

```bash
php artisan serve
```

O usar el comando de desarrollo completo (servidor + queue + vite):
```bash
composer dev
```

La aplicación estará disponible en `http://localhost:8000`

## 👤 Usuarios por defecto

Después de ejecutar los seeders, tendrás acceso con:

**Super Admin:**
- Email: `ag@gmail.com`
- Password: `1234`

**Admin Secundario:**
- Email: `alejandro@admin.com`
- Password: `123456789`

## 📁 Estructura del proyecto

```
nevora_base/
├── app/
│   ├── Http/Controllers/     # Controladores HTTP
│   ├── Livewire/            # Componentes Livewire
│   ├── Models/              # Modelos Eloquent
│   ├── Services/            # Lógica de negocio
│   └── Traits/              # Traits reutilizables
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/             # Seeders de datos
├── resources/
│   ├── css/                 # Estilos CSS/Tailwind
│   ├── js/                  # JavaScript
│   └── views/               # Vistas Blade/Livewire
├── routes/
│   ├── web.php             # Rutas web públicas
│   ├── admin.php           # Rutas del panel admin
│   ├── api.php             # Rutas API
│   └── auth.php            # Rutas de autenticación
└── public/                  # Assets públicos
```

## 🔧 Comandos útiles

```bash
# Limpiar caché
php artisan optimize:clear

# Ejecutar tests
php artisan test

# Formatear código (Laravel Pint)
./vendor/bin/pint

# Ver logs en tiempo real
php artisan pail

# Ejecutar queue workers
php artisan queue:work

# Iniciar servidor de WebSockets
php artisan reverb:start
```

## 📝 Configuración adicional

### Configuración de la empresa

Después de instalar, puedes configurar los datos de tu organización en:
- Panel Admin → Configuración → General
- Panel Admin → Configuración → Logos

**Datos de la empresa configurados:**
- Nombre: Sistema Web de Gestion de la Alcaldia del Municipio Escuque
- Dirección: Avenida principal, Municipio Escuque, Estado Trujillo.
- Teléfono: 04163762183
- Email Principal: ag@gmail.com
- Email Secundario: alejandro@admin.com

### Roles y permisos

El sistema incluye roles predefinidos:
- **Super Admin**: Acceso total al sistema
- **Administrador**: Gestión completa excepto configuración crítica
- **Coordinador**: Gestión de beneficiarios y programas
- **Operador**: Registro y consulta de información
- **Beneficiario**: Acceso limitado a su información personal

Puedes personalizar roles y permisos en:
- Panel Admin → Configuración → Roles
- Panel Admin → Configuración → Permisos

## 🌍 Localización

El sistema está configurado en español por defecto. Los archivos de idioma se encuentran en:
- `lang/es.json`: Traducciones generales
- `lang/es/`: Traducciones por módulo

## 🔒 Seguridad

- Autenticación con Laravel Sanctum
- Protección CSRF
- Validación de datos en servidor
- Sanitización de inputs
- Control de acceso basado en roles y permisos
- Auditoría completa de actividades

## 📊 Base de datos

El sistema utiliza SQLite por defecto para facilitar el desarrollo. Para producción, se recomienda usar MySQL o PostgreSQL.

### Backup de base de datos

```bash
# SQLite
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u usuario -p nombre_bd > backup-$(date +%Y%m%d).sql
```

## 🤝 Contribución

Este es un proyecto privado. Para contribuir, contacta al equipo de desarrollo.

## 📄 Licencia

MIT License

## 👥 Equipo

Desarrollado por **AG 1.0**

## 📞 Soporte

Para soporte técnico, contacta a:
- Email: ag@gmail.com
- Email: alejandro@admin.com
- Teléfono: 04163762183

---

**Sistema Web de Gestion de la Alcaldia del Municipio Escuque** - Sistema de gestión de beneficios sociales
