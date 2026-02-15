# Análisis: Estructura actual de roles y permisos

## 1. Paquete y base de datos

- **Paquete:** [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission).
- **Migración:** `database/migrations/2025_07_11_140213_create_permission_tables.php`.
- **Tablas:** `permissions`, `roles`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`.
- **Config:** `config/permission.php` (modelos y nombres de tablas por defecto).

---

## 2. Modelo User

- **Trait:** `Spatie\Permission\Traits\HasRoles` en `app/Models/User.php`.
- Métodos usados en el proyecto: `hasRole()`, `roles`, `assignRole()`, `syncPermissions()` (en seeders).

---

## 3. Roles definidos (RoleSeeder)

| Rol            | Permisos asignados en seed |
|----------------|----------------------------|
| **Super Admin**| Ninguno en seeder (SuperAdminSeeder asigna todos después) |
| **admin**      | Sí: users, roles, activitylog, inventory, products, warehouses, categories, movements, stock, inventory-entry/exit, community-project, project-in-progress/executed/proposed |
| **user**       | Solo `view-user` |
| **Beneficiario** | Ninguno |
| **Coordinador**  | Ninguno |
| **Operador**     | Ninguno |
| **Administrador**| Ninguno |

Inconsistencia: se crean 7 roles pero solo **admin** y **user** tienen permisos en el seeder. Super Admin se completa en `SuperAdminSeeder` (no se llama en `DatabaseSeeder` por defecto).

---

## 4. Permisos (PermissionSeeder)

Se crean permisos para:

- **Roles y usuarios:** create/edit/delete/view/download para role y user.
- **Activity log, permissions, tenants, dashboard, profile-setting.**
- **Catálogos:** actividad, estado, estatus, municipio, parroquia, plan, pagos, payment-type, payment-origin, settings, direccion, general.
- **Inventario:** inventory, product, warehouse, category, movement, stock-adjustment, inventory-entry, inventory-exit.
- **Proyectos comunitarios:** community-project, project-in-progress, project-executed, project-proposed.

No hay permisos específicos tipo `view-beneficiary`, `view-report`, `view-solicitud`; se usa `view-dashboard` para Beneficiarios, Reportes y Solicitudes en el sidebar y en rutas de solicitudes.

---

## 5. Uso en login (Livewire auth)

**Archivo:** `resources/views/livewire/auth/login.blade.php` (y lógica de login).

- Si `hasRole(['admin','Super Admin','Coordinador','Operador','Administrador'])` → redirección a `route('admin.dashboard')`.
- Si `hasRole(['Beneficiario','user'])` → redirección a `route('dashboard')`.
- Cualquier otro rol → logout + flash warning “Tu usuario no tiene permisos asignados” + redirect home.

En `routes/web.php`, `route('dashboard')` es un redirect a `route('admin.dashboard')`. Por tanto, **Beneficiario** y **user** también acaban intentando entrar a `/admin/dashboard`.

---

## 6. Middleware de rutas admin (bootstrap/app.php)

Las rutas de `routes/admin.php` se registran con:

```php
Route::middleware('web', 'auth', 'verified', 'role:admin|Super Admin|Doctor|Recepcionista|Administrador')
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));
```

Consecuencias:

- **Coordinador** y **Operador** no están en el middleware: el login los manda a `admin.dashboard`, pero al cargar `/admin/*` el middleware de rol los bloqueará (403).
- **Beneficiario** y **user** tampoco están: igualmente serán bloqueados en cualquier ruta `/admin/*`, incluido el dashboard.
- **Doctor** y **Recepcionista** sí están en el middleware pero **no existen en RoleSeeder** (solo en el middleware), por lo que son roles “fantasma” a menos que se creen a mano.

Resumen: solo tienen acceso real a `/admin/*` quienes tengan uno de: **admin, Super Admin, Doctor, Recepcionista, Administrador**. El resto (Coordinador, Operador, Beneficiario, user) quedan en un estado incoherente (redirección a admin pero sin acceso).

---

## 7. Rutas admin y permisos por ruta

- Casi todas las rutas en `admin.php` usan `->middleware('permission:...')` (ej. `permission:view-user`, `permission:view-role`, etc.).
- **Sin middleware de permiso** (solo auth + rol de grupo):  
  `/admin/dashboard`, `/admin/beneficiaries/*`, `/admin/reports/*`, `/admin/map`, `/admin/activity-logs`.
- **Con permiso:**  
  `/admin/solicitudes` y PDF: `permission:view-dashboard`.

El sidebar (`resources/views/livewire/layout/admin/includes/sidebar.blade.php`) usa `@can($link['permission'])` para mostrar/ocultar ítems; muchos ítems usan `view-dashboard` (Panel, Beneficiarios, Solicitudes ciudadanas, etc.).

---

## 8. Otros usos de roles/permisos

- **AppServiceProvider:** `Gate::before` → si el usuario `hasRole('Super Admin')` devuelve `true` (acceso total).
- **Vista welcome (Blade):** `Auth::user()->hasRole(['Beneficiario'])` para mostrar un menú u otro (si se usa esa vista para usuarios autenticados).
- **UserTable (Livewire):** lista usuarios con `roles` y evita eliminar Super Admin; al eliminar usuario se hace `$user->roles()->detach()`.
- **CRUD de roles/permisos:** Livewire (RoleTable, roles index/create/edit/show, permissions index/create/edit/show) usando modelos Spatie.

---

## 9. Resumen de problemas e incoherencias

1. **Roles sin permisos:** Beneficiario, Coordinador, Operador, Administrador no reciben permisos en `RoleSeeder`.
2. **Middleware de admin vs login:** El login redirige Coordinador, Operador, Beneficiario y user a dashboard/admin, pero el middleware `role:admin|Super Admin|Doctor|Recepcionista|Administrador` no incluye Coordinador ni Operador, y tampoco Beneficiario ni user → riesgo de 403 tras el login.
3. **Dashboard único:** No hay un dashboard distinto para “Beneficiario” o “user”; ambos son redirigidos al mismo `admin.dashboard`, que además no pueden abrir por el middleware de rol.
4. **Roles en middleware que no están en seeders:** Doctor, Recepcionista aparecen en el middleware pero no en `RoleSeeder`.
5. **Super Admin:** Se completa con `SuperAdminSeeder` (todos los permisos), pero `DatabaseSeeder` no llama a `SuperAdminSeeder`, solo a `DefaultUserSeeder` (que asigna rol “Super Admin” a usuarios).
6. **Permisos 1x10:** Beneficiarios, reportes y solicitudes se apoyan en `view-dashboard` o en ninguna protección por permiso; no hay permisos granulares tipo `view-beneficiary`, `view-report`, `view-solicitud`.

---

## 10. Recomendaciones (para siguiente iteración)

1. **Unificar roles y middleware:**  
   Decidir qué roles pueden entrar a `/admin/*` y poner exactamente esos en el middleware (incluir Coordinador y Operador si deben usar el panel; o dejar solo admin/Super Admin/Administrador y dar a Coordinador/Operador otro flujo).
2. **Dashboard por tipo de usuario:**  
   Para Beneficiario (y si aplica “user”), usar una ruta tipo `/dashboard` que no redirija a `admin.dashboard`, sino a una vista/Volt/Livewire específica de “mi cuenta” o “beneficiario”, sin pasar por el middleware de rol admin.
3. **Asignar permisos en RoleSeeder** a Coordinador, Operador y Administrador según la matriz de permisos que se defina (y opcionalmente un permiso mínimo para Beneficiario si en el futuro tiene pantallas propias).
4. **Añadir permisos 1x10** (opcional): por ejemplo `view-beneficiary`, `edit-beneficiary`, `view-report`, `view-solicitud`, etc., y usarlos en rutas y sidebar en lugar de depender solo de `view-dashboard`.
5. **Sincronizar seeders y middleware:**  
   O bien crear roles Doctor y Recepcionista en `RoleSeeder` (si se usan), o bien quitarlos del middleware y dejar solo los roles que realmente existen y se usan (Super Admin, admin, Administrador, Coordinador, Operador, Beneficiario, user).
6. **Documentar matriz rol–permiso** en un archivo (ej. `ROLES_Y_PERMISOS.md`) y mantener `RoleSeeder` alineado con esa matriz.

---

*Documento generado a partir del análisis del código (rutas, middleware, seeders, modelo User y vistas).*
