# 📋 Módulo: Proyectos Comunitarios

## 📝 Descripción

Este módulo gestiona proyectos comunitarios con tres fases:
- 🔄 **En Proceso**: Proyectos actualmente en ejecución
- ✅ **Ejecutados**: Proyectos completados
- 💡 **Propuestos**: Proyectos en fase de propuesta

---

## 🗂️ Archivos del Módulo

### **1. Permisos** (`database/seeders/PermissionSeeder.php`)
Líneas 194-217:
```php
// Proyectos Comunitarios
'view-community-project',
'create-community-project',
'edit-community-project',
'delete-community-project',
'download-community-project',

// Proyectos en Proceso
'view-project-in-progress',
'create-project-in-progress',
'edit-project-in-progress',
'delete-project-in-progress',

// Proyectos Ejecutados
'view-project-executed',
'create-project-executed',
'edit-project-executed',
'delete-project-executed',

// Proyectos Propuestos
'view-project-proposed',
'create-project-proposed',
'edit-project-proposed',
'delete-project-proposed',
```

### **2. Roles** (`database/seeders/RoleSeeder.php`)
Líneas 80-96:
```php
'view-community-project',
'create-community-project',
'edit-community-project',
'delete-community-project',
'download-community-project',
'view-project-in-progress',
'create-project-in-progress',
'edit-project-in-progress',
'delete-project-in-progress',
'view-project-executed',
'create-project-executed',
'edit-project-executed',
'delete-project-executed',
'view-project-proposed',
'create-project-proposed',
'edit-project-proposed',
'delete-project-proposed',
```

### **3. Menú Sidebar** (`resources/views/livewire/layout/admin/includes/sidebar.blade.php`)
Líneas 108-147:
```php
[
    'header' => 'Proyectos Comunitarios',
    'permission' => 'view-community-project',
],
[
    'name' => 'Proyectos',
    'icon' => 'fa-solid fa-diagram-project',
    'href' => '#',
    'active' => request()->routeIs([...]),
    'id_submenu' => 'submenu-projects',
    'permission' => 'view-community-project',
    'submenu' => [
        // En Proceso, Ejecutados, Propuestos
    ],
],
```

---

## ❌ Cómo Remover el Módulo (si no se usa)

Si decides no usar este módulo, sigue estos pasos:

### **Paso 1: Eliminar Permisos**
Edita `database/seeders/PermissionSeeder.php` y **elimina las líneas 194-217**

### **Paso 2: Eliminar del Rol Admin**
Edita `database/seeders/RoleSeeder.php` y **elimina las líneas 80-96**

### **Paso 3: Eliminar del Menú**
Edita `resources/views/livewire/layout/admin/includes/sidebar.blade.php` y **elimina las líneas 108-147**

### **Paso 4: Aplicar Cambios**
```bash
php artisan migrate:fresh --seed
php artisan view:clear
```

### **Paso 5: Eliminar este archivo**
```bash
rm MODULO_PROYECTOS_COMUNITARIOS.md
```

---

## ✅ Cómo Expandir el Módulo (si se usa)

Si decides usar y expandir este módulo:

### **1. Crear Modelo**
```bash
php artisan make:model CommunityProject -m
```

### **2. Crear Migración**
Edita la migración creada y agrega campos:
```php
Schema::create('community_projects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->enum('status', ['proposed', 'in_progress', 'executed'])->default('proposed');
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();
    $table->decimal('budget', 12, 2)->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
    $table->softDeletes();
});
```

### **3. Crear Tabla PowerGrid**
```bash
php artisan powergrid:create CommunityProjectTable --model=CommunityProject
```

### **4. Crear Rutas**
Agrega en `routes/admin.php`:
```php
// Proyectos en Proceso
Volt::route('/projects/in-progress', 'pages.admin.projects.in-progress.index')
    ->middleware('permission:view-project-in-progress')
    ->name('projects-in-progress.index');

// Proyectos Ejecutados
Volt::route('/projects/executed', 'pages.admin.projects.executed.index')
    ->middleware('permission:view-project-executed')
    ->name('projects-executed.index');

// Proyectos Propuestos
Volt::route('/projects/proposed', 'pages.admin.projects.proposed.index')
    ->middleware('permission:view-project-proposed')
    ->name('projects-proposed.index');
```

### **5. Crear Vistas Volt**
```bash
php artisan make:volt pages/admin/projects/in-progress/index
php artisan make:volt pages/admin/projects/executed/index
php artisan make:volt pages/admin/projects/proposed/index
```

### **6. Actualizar URLs en Sidebar**
Reemplaza los `'url' => '#'` por las rutas reales:
```php
'url' => route('admin.projects-in-progress.index'),
'url' => route('admin.projects-executed.index'),
'url' => route('admin.projects-proposed.index'),
```

---

## 📊 Estado Actual

- ✅ Permisos creados
- ✅ Roles asignados
- ✅ Menú agregado al sidebar
- ⏳ Modelo pendiente (crear si se usa)
- ⏳ Migraciones pendientes (crear si se usa)
- ⏳ Rutas pendientes (crear si se usa)
- ⏳ Vistas pendientes (crear si se usa)

---

## 📞 Notas

- Este módulo está **listo para usar** pero **fácil de remover**
- Los enlaces actuales apuntan a `#` (placeholder)
- Si decides usarlo, sigue la sección "Cómo Expandir el Módulo"
- Si decides no usarlo, sigue la sección "Cómo Remover el Módulo"

---

**Control de beneficios 1X10 Escuque** - Sistema de gestión de beneficios sociales
Desarrollado por AG 1.0
