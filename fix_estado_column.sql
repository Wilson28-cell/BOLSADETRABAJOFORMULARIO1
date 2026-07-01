-- Expandir columna 'estado' en ambas tablas para permitir 'Desactivado'

ALTER TABLE `empresas_producto_aprobadas` 
MODIFY COLUMN `estado` VARCHAR(50) NOT NULL;

ALTER TABLE `productos_publicos` 
MODIFY COLUMN `estado` VARCHAR(50) NOT NULL;

-- Verificar cambios
SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME IN ('empresas_producto_aprobadas', 'productos_publicos') 
AND COLUMN_NAME = 'estado';
