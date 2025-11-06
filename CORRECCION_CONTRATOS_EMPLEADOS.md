# 🔧 Corrección: Visualización de Contratos en Empleados

## 📋 Problema Identificado

Los contratos no se mostraban en las vistas de **Detalle** y **Editar** del empleado porque:

1. ❌ El modelo `Empleado` no tenía métodos para obtener contratos
2. ❌ El controlador `EmpleadoController` no cargaba los contratos del empleado
3. ❌ Las vistas no tenían código HTML para mostrar los contratos

## ✅ Solución Implementada

### 1. **Modelo Empleado** (`models/Empleado.php`)

Se agregaron dos nuevos métodos:

```php
/**
 * Obtener contratos de un empleado
 */
public function obtenerContratos() {
    $query = "SELECT *
              FROM contrato
              WHERE id_empleado = :id_empleado
              ORDER BY fecha_inicio DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_empleado', $this->id_empleado);
    $stmt->execute();
    
    return $stmt;
}

/**
 * Obtener contrato activo de un empleado
 */
public function obtenerContratoActivo() {
    $query = "SELECT *
              FROM contrato
              WHERE id_empleado = :id_empleado
              AND estado = 'activo'
              AND (fecha_fin IS NULL OR fecha_fin >= CURDATE())
              ORDER BY fecha_inicio DESC
              LIMIT 1";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_empleado', $this->id_empleado);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
```

### 2. **Controlador EmpleadoController** (`controllers/EmpleadoController.php`)

Se actualizaron los métodos `detalle()` y `editar()`:

#### Método `detalle()`:
```php
public function detalle() {
    if (isset($_GET['id'])) {
        $this->empleado->id_empleado = $_GET['id'];
        
        if ($this->empleado->obtenerPorId()) {
            // ✅ AGREGADO: Obtener contratos del empleado
            $stmt_contratos = $this->empleado->obtenerContratos();
            $contratos = $stmt_contratos->fetchAll(PDO::FETCH_ASSOC);
            
            // ✅ AGREGADO: Obtener contrato activo
            $contrato_activo = $this->empleado->obtenerContratoActivo();
            
            require_once 'views/empleados/detalle.php';
        } else {
            // ... código de error
        }
    }
}
```

#### Método `editar()`:
```php
public function editar() {
    if (isset($_GET['id'])) {
        $this->empleado->id_empleado = $_GET['id'];
        
        if ($this->empleado->obtenerPorId()) {
            // ✅ AGREGADO: Obtener contratos del empleado
            $stmt_contratos = $this->empleado->obtenerContratos();
            $contratos = $stmt_contratos->fetchAll(PDO::FETCH_ASSOC);
            
            // ✅ AGREGADO: Obtener contrato activo
            $contrato_activo = $this->empleado->obtenerContratoActivo();
            
            require_once 'views/empleados/editar.php';
        }
    }
}
```

### 3. **Vista Detalle** (`views/empleados/detalle.php`)

Se agregó una sección completa para mostrar los contratos:

#### Características implementadas:
- ✅ Muestra el contador correcto de contratos en el resumen
- ✅ Alerta destacada con el contrato activo (si existe)
- ✅ Tabla con todos los contratos del empleado mostrando:
  - **Tipo de contrato** (campo `tipo_contrato`)
  - **Fechas de inicio y fin**
  - **Salario** (campo `monto_base`)
  - **Estado** (con badge de colores)
  - **Botones de acción** (Ver/Editar)
- ✅ Botón para crear nuevo contrato
- ✅ Mensaje cuando no hay contratos con botón de acción

```php
<!-- Sección de Contratos -->
<?php if (!empty($contratos)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-file-text"></i> Contratos del Empleado
                </h5>
            </div>
            <div class="card-body">
                <!-- Alerta de contrato activo -->
                <?php if ($contrato_activo): ?>
                <div class="alert alert-success mb-3">
                    <strong>Contrato Activo:</strong> 
                    <?php echo htmlspecialchars($contrato_activo['tipo_contrato']); ?>
                    ...
                </div>
                <?php endif; ?>
                
                <!-- Tabla de contratos -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <!-- Contenido de la tabla -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<!-- Mensaje cuando no hay contratos -->
<?php endif; ?>
```

### 4. **Vista Editar** (`views/empleados/editar.php`)

Se agregó una sección similar pero más compacta:

#### Características implementadas:
- ✅ Tabla compacta con información resumida de contratos
- ✅ Alerta con contrato activo
- ✅ Botón para crear nuevo contrato
- ✅ Botón para ver detalle de cada contrato
- ✅ Mensaje cuando no hay contratos

## 📊 Estructura de la Tabla Contrato

Campos utilizados de la tabla `contrato`:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id_contrato` | INT | ID del contrato |
| `id_empleado` | INT | ID del empleado |
| `tipo_contrato` | VARCHAR | Tipo de contrato (texto directo) |
| `fecha_inicio` | DATE | Fecha de inicio del contrato |
| `fecha_fin` | DATE | Fecha de fin (puede ser NULL) |
| `monto_base` | DECIMAL | Salario base del contrato |
| `monto_hora` | DECIMAL | Monto por hora |
| `estado` | VARCHAR | Estado: activo, finalizado, suspendido |
| `observaciones` | TEXT | Observaciones del contrato |

## 🎨 Estados de Contratos con Colores

Los contratos se muestran con badges de colores según su estado:

| Estado | Color | Clase CSS |
|--------|-------|-----------|
| **Activo** | 🟢 Verde | `bg-success` |
| **Finalizado** | ⚫ Gris | `bg-secondary` |
| **Suspendido** | 🟡 Amarillo | `bg-warning` |
| **Otros** | ⚫ Negro | `bg-dark` |

## 📊 Información Mostrada

### En la Vista de Detalle:
- Tipo de contrato (campo `tipo_contrato`)
- Fecha de inicio (formato: dd/mm/YYYY)
- Fecha de fin (formato: dd/mm/YYYY o "Indefinido")
- Salario (campo `monto_base`, formato: ₲ 1.000.000)
- Estado con color
- Botones: Ver detalle y Editar (solo si está activo)

### En la Vista de Editar:
- Misma información pero en tabla más compacta
- Solo botón de "Ver detalle"

## 🔗 Navegación Agregada

Se agregaron varios enlaces de navegación:

1. **Desde el resumen lateral**: 
   - Contador actualizado de contratos

2. **En la sección de contratos**:
   - Botón "Nuevo Contrato" (lleva a crear contrato pre-cargado con el empleado)
   - Botón "Ver Detalle" en cada contrato
   - Botón "Editar" en contratos activos

3. **Cuando no hay contratos**:
   - Botón "Crear Primer Contrato"

## ✅ Resultado Final

Ahora cuando entres a:
- **Ver Detalle del Empleado**: Verás todos sus contratos listados
- **Editar Empleado**: Podrás ver los contratos mientras editas la información básica

## 🧪 Cómo Probar

1. Navega a la lista de empleados
2. Haz clic en "Ver detalle" de cualquier empleado
3. Deberías ver la sección de contratos al final de la página
4. Si el empleado no tiene contratos, verás un mensaje y un botón para crear uno
5. Si tiene contratos, verás una tabla con todos sus contratos
6. Lo mismo aplica en la vista de "Editar empleado"

## 📝 Notas Importantes

- Los contratos se ordenan por fecha de inicio (más recientes primero)
- Solo se muestra un contrato como "activo" destacado
- El contador en el resumen ahora es dinámico
- Las fechas se formatean automáticamente al estilo paraguayo (dd/mm/YYYY)
- Los salarios (`monto_base`) se formatean con separadores de miles
- El campo `tipo_contrato` se muestra directamente sin necesidad de JOIN con otra tabla

## 🎯 Variables Disponibles en las Vistas

Después de estas modificaciones, las vistas tienen acceso a:

```php
$this->empleado       // Objeto con datos del empleado
$contratos            // Array con todos los contratos del empleado
$contrato_activo      // Array con el contrato activo (null si no hay)
```

Estructura de cada contrato en el array `$contratos`:
```php
[
    'id_contrato' => 1,
    'id_empleado' => 5,
    'tipo_contrato' => 'Indefinido',
    'fecha_inicio' => '2024-01-15',
    'fecha_fin' => null,
    'monto_base' => 3500000,
    'monto_hora' => 0,
    'estado' => 'activo',
    'observaciones' => '...'
]
```

## 🚀 Próximas Mejoras Sugeridas

1. Agregar filtros por estado de contrato
2. Paginación si hay muchos contratos
3. Exportar lista de contratos a PDF/Excel
4. Historial de cambios en contratos
5. Notificaciones de contratos próximos a vencer
6. Gráfica de evolución salarial
