-- Script para agregar el campo pdf_path a la tabla reports
-- Ejecuta esto en tu gestor de base de datos (phpMyAdmin, MySQL Workbench, etc.)

-- Verificar si el campo ya existe
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME = 'reports' 
  AND COLUMN_NAME = 'pdf_path';

-- Si NO aparece ningún resultado, ejecuta este comando:
ALTER TABLE reports 
ADD COLUMN pdf_path VARCHAR(255) NULL 
AFTER status;

-- Verificar que se agregó correctamente
DESCRIBE reports;
