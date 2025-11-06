<?php 
$titulo = "Editar Evento - Sistema RRHH";
include 'views/templates/header.php'; 
    if (!isset($evento)) : ?>
    <div class="alert alert-danger mt-3">
        <strong>Error:</strong> No se encontró información del evento. 
        <a href="index.php?controller=evento&action=index" class="alert-link">Volver al listado</a>.
    </div>
    <?php include 'views/templates/footer.php'; ?>
<?php endif;

?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-pencil-square"></i> Editar Evento</h2>
            <a href="index.php?controller=evento&action=index" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        <p class="text-muted">Modifique la información del evento seleccionado</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-form"></i> Información del Evento</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?controller=evento&action=actualizar" onsubmit="return validarFormularioEvento(this)">
                    <input type="hidden" name="id_evento" value="<?php echo $evento->id_evento; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_evento" class="form-label">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_evento" name="nombre_evento" 
                                   value="<?php echo htmlspecialchars($evento->nombre_evento); ?>" required>
                            <div class="form-text">Nombre descriptivo del evento</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo de Evento <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo" name="tipo" required onchange="toggleCamposEvento()">
                                <option value="">Seleccionar tipo</option>
                                <option value="+" <?php echo ($evento->tipo == '+') ? 'selected' : ''; ?>>Bonificación (+)</option>
                                <option value="-" <?php echo ($evento->tipo == '-') ? 'selected' : ''; ?>>Descuento (-)</option>
                            </select>
                            <div class="form-text">Bonificación suma, descuento resta del salario</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="monto" class="form-label">Monto <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₲</span>
                                <input type="number" class="form-control" id="monto" name="monto" 
                                       value="<?php echo $evento->monto; ?>" min="0" step="0.01" required>
                            </div>
                            <div class="form-text">Monto fijo del evento (0 para monto variable)</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="activo" class="form-label">Estado</label>
                            <select class="form-select" id="activo" name="activo">
                                <option value="1" <?php echo ($evento->activo) ? 'selected' : ''; ?>>Activo</option>
                                <option value="0" <?php echo (!$evento->activo) ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                            <div class="form-text">Los eventos inactivos no se pueden aplicar</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                                  placeholder="Descripción detallada del evento..."><?php echo htmlspecialchars($evento->descripcion); ?></textarea>
                        <div class="form-text">Información adicional sobre el evento</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=evento&action=index" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Actualizar Evento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información Actual</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID del Evento:</strong><br>
                    <span class="badge bg-secondary">#<?php echo $evento->id_evento; ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>Tipo Actual:</strong><br>
                    <?php if ($evento->tipo == '+'): ?>
                        <span class="badge bg-success">
                            <i class="bi bi-plus-circle"></i> Bonificación
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger">
                            <i class="bi bi-dash-circle"></i> Descuento
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <strong>Monto Actual:</strong><br>
                    <span class="h5 <?php echo $evento->tipo == '+' ? 'text-success' : 'text-danger'; ?>">
                        <?php echo $evento->tipo . ' ₲ ' . number_format($evento->monto, 0, ',', '.'); ?>
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Estado:</strong><br>
                    <?php if ($evento->activo): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                    <?php endif; ?>
                </div>
                
                <hr>
                
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Advertencia:</strong> Los cambios en este evento afectarán las liquidaciones futuras.
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?controller=evento&action=aplicar&evento=<?php echo $evento->id_evento; ?>" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-person-plus"></i> Aplicar a Empleado
                    </a>
                    <a href="index.php?controller=evento&action=index" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-list-ul"></i> Ver Todos
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validarFormularioEvento(form) {
    const nombre = form.nombre_evento.value.trim();
    const tipo = form.tipo.value;
    const monto = parseFloat(form.monto.value);
    
    if (nombre.length < 3) {
        alert('El nombre del evento debe tener al menos 3 caracteres');
        return false;
    }
    
    if (!tipo) {
        alert('Debe seleccionar un tipo de evento');
        return false;
    }
    
    if (monto < 0) {
        alert('El monto no puede ser negativo');
        return false;
    }
    
    return true;
}

function toggleCamposEvento() {
    const tipo = document.getElementById('tipo').value;
    const montoField = document.getElementById('monto');
    
    if (tipo === '+') {
        montoField.placeholder = 'Monto de bonificación';
    } else if (tipo === '-') {
        montoField.placeholder = 'Monto de descuento';
    } else {
        montoField.placeholder = '0';
    }
}
</script>

<?php include 'views/templates/footer.php'; ?>

