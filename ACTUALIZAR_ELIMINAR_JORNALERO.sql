-- Script para eliminar el tipo de contrato 'jornalero' del sistema
-- Ejecutar este script si ya tienes la base de datos instalada

USE rrhh_salarios;

-- Importante: Antes de ejecutar, asegúrate de que NO hay contratos con tipo 'jornalero' en uso
-- Puedes verificar con: SELECT * FROM contrato WHERE tipo_contrato = 'jornalero';

-- Si hay contratos con tipo 'jornalero', primero debes cambiarlos a otro tipo:
-- UPDATE contrato SET tipo_contrato = 'mensualero' WHERE tipo_contrato = 'jornalero';
-- O eliminarlos si es necesario

-- Modificar el ENUM de la tabla contrato para eliminar 'jornalero'
ALTER TABLE contrato 
MODIFY COLUMN tipo_contrato ENUM('mensualero', 'catedratico') NOT NULL;

-- Verificación
SELECT COLUMN_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'rrhh_salarios' 
  AND TABLE_NAME = 'contrato' 
  AND COLUMN_NAME = 'tipo_contrato';

-- El resultado debe mostrar: enum('mensualero','catedratico')
