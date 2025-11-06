<?php 
$titulo = "Crear Evento - Sistema RRHH";
include 'views/templates/header.php'; 
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-plus-circle"></i> Crear Nuevo Evento</h2>
            <a href="index.php?controller=evento&action=index" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        <p class="text-muted">Registre un nuevo evento (bonificación o descuento) en el sistema</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-form"></i> Información del Evento</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?controller=evento&action=guardar" onsubmit="return validarFormularioEvento(this)">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_evento" class="form-label">Nombre del Evento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_evento" name="nombre_evento" 
                                   placeholder="Ej: Bono por desempeño" required>
                            <div class="form-text">Nombre descriptivo del evento</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo de Evento <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo" name="tipo" required onchange="toggleCamposEvento()">
                                <option value="">Seleccionar tipo</option>
                                <option value="+">Bonificación (+)</option>
                                <option value="-">Descuento (-)</option>
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
                                       placeholder="0" min="0" step="0.01" required>
                            </div>
                            <div class="form-text">Monto fijo del evento (0 para monto variable)</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="activo" class="form-label">Estado</label>
                            <select class="form-select" id="activo" name="activo">
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            <div class="form-text">Los eventos inactivos no se pueden aplicar</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                                  placeholder="Descripción detallada del evento..."></textarea>
                        <div class="form-text">Información adicional sobre el evento</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=evento&action=index" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Crear Evento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información</h5>
            </div>
            <div class="card-body">
                <h6>Tipos de Eventos:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <span class="badge bg-success me-2">+</span>
                        <strong>Bonificaciones:</strong> Suman al salario
                        <small class="d-block text-muted">Ej: Bono por desempeño, horas extras</small>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-danger me-2">-</span>
                        <strong>Descuentos:</strong> Restan del salario
                        <small class="d-block text-muted">Ej: Ausencias, anticipos, IPS</small>
                    </li>
                </ul>
                
                <hr>
                
                <h6>Monto Variable:</h6>
                <p class="small text-muted">
                    Si el monto es 0, se puede especificar un monto diferente al aplicar el evento a cada empleado.
                </p>
                
                <hr>
                
                <h6>Eventos Comunes:</h6>
                <ul class="small">
                    <li><strong>Bonificaciones:</strong> Bono por desempeño, horas extras, comisiones</li>
                    <li><strong>Descuentos:</strong> IPS (9%), ausencias, anticipos, tardanzas</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?controller=evento&action=aplicar" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-person-plus"></i> Aplicar Evento
                    </a>
                    <a href="index.php?controller=empleado&action=index" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-people"></i> Ver Empleados
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

