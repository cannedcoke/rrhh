# Eliminación del Tipo de Contrato "Jornalero"

## Resumen de Cambios

Se ha eliminado completamente la opción "jornalero" del sistema de gestión de contratos. Ahora solo existen dos tipos de contrato:
- **Mensualero**: Con salario base mensual
- **Catedrático**: Con pago por hora cátedra

---

## Archivos Modificados

### 1. **views/contratos/crear.php**
- ✅ Eliminada opción "jornalero" del select de tipo de contrato
- ✅ Actualizada función JavaScript `toggleCamposContrato()` para manejar solo "mensualero" y "catedratico"

### 2. **views/contratos/editar.php**
- ✅ Eliminada opción "jornalero" del select de tipo de contrato
- ✅ Actualizada función JavaScript `toggleCamposContrato()` para manejar solo "mensualero" y "catedratico"

### 3. **controllers/ContratoController.php**

#### Método `guardar()`:
- ✅ Eliminado case 'jornalero' del switch
- ✅ Solo permanecen los casos 'mensualero' y 'catedratico'

#### Método `actualizar()`:
- ✅ Eliminado case 'jornalero' del switch
- ✅ Solo permanecen los casos 'mensualero' y 'catedratico'

#### Método `generarPDF()`:
- ✅ Eliminado el bloque elseif para tipo 'jornalero'
- ✅ Ahora solo evalúa 'catedratico' o 'mensualero' (por defecto)

### 4. **database.sql**
- ✅ Modificado ENUM de `tipo_contrato` en tabla `contrato`
- ✅ Antes: `ENUM('jornalero', 'mensualero', 'catedratico')`
- ✅ Ahora: `ENUM('mensualero', 'catedratico')`

---

## Comportamiento del Sistema

### Tipo Mensualero:
- Muestra campo: **Salario Base (₲)**
- Oculta campo: Monto por Hora
- En PDF: Muestra monto base mensual
- IPS: Sí goza del beneficio

### Tipo Catedrático:
- Oculta campo: Salario Base
- Muestra campo: **Monto por Hora (₲)**
- En PDF: Muestra monto por hora cátedra
- IPS: No goza del beneficio

---

## Instrucciones para Base de Datos Existente

Si ya tienes el sistema instalado con contratos de tipo "jornalero", sigue estos pasos:

### 1. Verificar si hay contratos jornaleros:
```sql
USE rrhh_salarios;
SELECT * FROM contrato WHERE tipo_contrato = 'jornalero';
```

### 2. Si hay registros, debes decidir:
**Opción A - Convertirlos a mensualero:**
```sql
UPDATE contrato SET tipo_contrato = 'mensualero' WHERE tipo_contrato = 'jornalero';
```

**Opción B - Eliminarlos (¡cuidado! esto es irreversible):**
```sql
DELETE FROM contrato WHERE tipo_contrato = 'jornalero';
```

### 3. Actualizar la estructura de la tabla:
```sql
ALTER TABLE contrato 
MODIFY COLUMN tipo_contrato ENUM('mensualero', 'catedratico') NOT NULL;
```

### 4. Verificar el cambio:
```sql
SELECT COLUMN_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'rrhh_salarios' 
  AND TABLE_NAME = 'contrato' 
  AND COLUMN_NAME = 'tipo_contrato';
```

**Nota:** También puedes ejecutar el archivo `ACTUALIZAR_ELIMINAR_JORNALERO.sql` que contiene estos comandos.

---

## Instalación Nueva

Si estás instalando el sistema desde cero:
1. Simplemente ejecuta el archivo `database.sql` actualizado
2. La tabla `contrato` se creará sin la opción 'jornalero'
3. No necesitas ejecutar el script de actualización

---

## Notas Importantes

⚠️ **Antes de aplicar cambios en producción:**
1. Haz un backup completo de tu base de datos
2. Verifica que no haya contratos activos de tipo 'jornalero'
3. Prueba los cambios en un ambiente de desarrollo primero

✅ **Después de aplicar los cambios:**
1. Los formularios de crear/editar contratos solo mostrarán las opciones "Mensualero" y "Catedrático"
2. El sistema no permitirá crear nuevos contratos tipo "jornalero"
3. Los contratos existentes (si los convertiste) funcionarán normalmente con su nuevo tipo

---

## Fecha de Modificación
06 de Noviembre de 2025
