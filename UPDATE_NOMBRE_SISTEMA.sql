-- Actualizar el nombre del sistema en la base de datos
-- Ejecuta este SQL en tu base de datos

UPDATE settings 
SET value = 'Sistema Web de Gestion de la Alcaldia del Municipio Escuque' 
WHERE key = 'name';

UPDATE settings 
SET value = 'Sistema Web de Gestion de la Alcaldia del Municipio Escuque' 
WHERE key = 'razon_social';

UPDATE settings 
SET value = 'Sistema Web de Gestion de la Alcaldia del Municipio Escuque es un sistema integral para la gestión y control de beneficios sociales. Facilitamos la administración eficiente de programas de ayuda social, garantizando transparencia y acceso equitativo a los beneficiarios.' 
WHERE key = 'long_description';

-- Verificar los cambios
SELECT key, value FROM settings WHERE key IN ('name', 'razon_social', 'long_description');
