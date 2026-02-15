# Credenciales para Pruebas - Sistema de Roles

## Super Admin (Angel y Gexander)
- **Email:** ag@gmail.com (y usuarios existentes con rol Super Admin)
- **Contraseña:** la que tengan configurada (ej. 1234)
- **Acceso:** Todo el sistema incluyendo Config General
- **Sesión:** Puede tener múltiples sesiones activas

---

## Alcalde (Administrador)
- **Email:** alcalde@alcaldia.escuque.com
- **Contraseña:** alcalde123
- **Acceso:** Todo excepto Config General (Datos empresa, Moneda, Logos, Colores, Roles, Permisos, Tipos de pago, Orígenes de pago)
- **Sesión:** Solo 1 sesión activa a la vez

---

## Analista
- **Email:** analista@alcaldia.escuque.com
- **Contraseña:** analista123
- **Acceso:** Solo Panel + Directores (Departamentos/Directores, Listado de Directores, Solicitudes de Alcaldía Digital)
- **Restricción:** No puede eliminar solicitudes
- **Sesión:** Solo 1 sesión activa a la vez

---

## Operador
- **Email:** operador@alcaldia.escuque.com
- **Contraseña:** operador123
- **Acceso:** Panel, Beneficiarios, Inventario completo, Movimientos (sin eliminar historial), Reportes, Mapa de Geolocalización
- **Restricción:** No puede eliminar historial de movimientos
- **Sesión:** Solo 1 sesión activa a la vez

---

## Notas
- El Super Admin puede modificar usuarios y sus datos desde el panel de Usuarios (Config General está en su sección exclusiva).
- Para cambiar contraseñas o datos de los usuarios ficticios, usar el panel de Usuarios como Super Admin.
- Si un usuario con sesión única intenta iniciar sesión desde otro dispositivo, verá un mensaje de error.
