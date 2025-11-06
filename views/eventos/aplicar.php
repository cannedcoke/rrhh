<?php 
$titulo = "Aplicar Evento - Sistema RRHH";
include 'views/templates/header.php'; 
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-person-plus"></i> Aplicar Evento a Empleado</h2>
            <a href="index.php?controller=evento&action=index" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        <p class="text-muted">Aplique un evento (bonificación o descuento) a un empleado específico</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-form"></i> Aplicación de Evento</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?controller=evento&action=guardarAplicacion" onsubmit="return validarFormularioAplicacion(this)">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_empleado" class="form-label">Empleado <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_empleado" name="id_empleado" required onchange="cargarInfoEmpleado()">
                                <option value="">Seleccionar empleado</option>
                                <?php foreach ($empleados as $emp): ?>
                                    <option value="<?php echo $emp['id_empleado']; ?>" 
                                            <?php echo ($id_empleado_sel == $emp['id_empleado']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']); ?>
                                        - <?php echo htmlspecialchars($emp['cedula']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Seleccione el empleado al que se aplicará el evento</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="id_evento" class="form-label">Evento <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_evento" name="id_evento" required onchange="cargarInfoEvento()">
                                <option value="">Seleccionar evento</option>
                                <?php foreach ($eventos as $evento): ?>
                                    <option value="<?php echo $evento['id_evento']; ?>" 
                                            data-tipo="<?php echo $evento['tipo']; ?>" 
                                            data-monto="<?php echo $evento['monto']; ?>">
                                        <?php echo htmlspecialchars($evento['nombre_evento']); ?>
                                        (<?php echo $evento['tipo'] == '+' ? 'Bonificación' : 'Descuento'; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Seleccione el evento a aplicar</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_aplicacion" class="form-label">Fecha de Aplicación <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_aplicacion" name="fecha_aplicacion" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                            <div class="form-text">Fecha en que se aplica el evento</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="monto_aplicado" class="form-label">Monto a Aplicar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₲</span>
                                <input type="number" class="form-control" id="monto_aplicado" name="monto_aplicado" 
                                       placeholder="0" min="0" step="0.01" required>
                            </div>
                            <div class="form-text">Monto específico para esta aplicación</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea class="form-control" id="observacion" name="observacion" rows="3" 
                                  placeholder="Observaciones sobre la aplicación del evento..."></textarea>
                        <div class="form-text">Información adicional sobre esta aplicación</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=evento&action=index" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Aplicar Evento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Empleado</h5>
            </div>
            <div class="card-body" id="info-empleado">
                <div class="text-center text-muted">
                    <i class="bi bi-person" style="font-size: 3rem;"></i>
                    <p class="mt-2 mb-0">Seleccione un empleado</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Información del Evento</h5>
            </div>
            <div class="card-body" id="info-evento">
                <div class="text-center text-muted">
                    <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
                    <p class="mt-2 mb-0">Seleccione un evento</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?controller=evento&action=index" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-list-ul"></i> Ver Eventos
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
function validarFormularioAplicacion(form) {
    const empleado = form.id_empleado.value;
    const evento = form.id_evento.value;
    const fecha = form.fecha_aplicacion.value;
    const monto = parseFloat(form.monto_aplicado.value);
    
    if (!empleado) {
        alert('Debe seleccionar un empleado');
        return false;
    }
    
    if (!evento) {
        alert('Debe seleccionar un evento');
        return false;
    }
    
    if (!fecha) {
        alert('Debe especificar una fecha de aplicación');
        return false;
    }
    
    if (monto < 0) {
        alert('El monto no puede ser negativo');
        return false;
    }
    
    return true;
}

function cargarInfoEmpleado() {
    const empleadoId = document.getElementById('id_empleado').value;
    const infoDiv = document.getElementById('info-empleado');
    
    if (!empleadoId) {
        infoDiv.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-person" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0">Seleccione un empleado</p>
            </div>
        `;
        return;
    }
    
    // Aquí se podría hacer una llamada AJAX para obtener la información del empleado
    // Por ahora mostramos un mensaje genérico
    infoDiv.innerHTML = `
        <div class="text-center">
            <i class="bi bi-person-check" style="font-size: 3rem; color: #198754;"></i>
            <p class="mt-2 mb-0 text-success">Empleado seleccionado</p>
        </div>
    `;
}

function cargarInfoEvento() {
    const eventoSelect = document.getElementById('id_evento');
    const montoInput = document.getElementById('monto_aplicado');
    const infoDiv = document.getElementById('info-evento');
    
    if (!eventoSelect.value) {
        infoDiv.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0">Seleccione un evento</p>
            </div>
        `;
        return;
    }
    
    const option = eventoSelect.selectedOptions[0];
    const tipo = option.dataset.tipo;
    const monto = option.dataset.monto;
    
    // Mostrar información del evento
    infoDiv.innerHTML = `
        <div class="mb-2">
            <strong>Tipo:</strong><br>
            <span class="badge bg-${tipo === '+' ? 'success' : 'danger'}">
                ${tipo === '+' ? 'Bonificación' : 'Descuento'}
            </span>
        </div>
        <div class="mb-2">
            <strong>Monto Base:</strong><br>
            <span class="h6 ${tipo === '+' ? 'text-success' : 'text-danger'}">
                ${tipo} ₲ ${parseFloat(monto).toLocaleString('es-PY')}
            </span>
        </div>
        <div class="mb-2">
            <strong>Estado:</strong><br>
            <span class="badge bg-success">Activo</span>
        </div>
    `;
    
    // Si el monto base es 0, permitir monto variable
    if (parseFloat(monto) === 0) {
        montoInput.placeholder = 'Monto variable';
        montoInput.required = true;
    } else {
        montoInput.value = monto;
        montoInput.placeholder = monto;
    }
}

// Cargar información inicial si hay empleado preseleccionado
document.addEventListener('DOMContentLoaded', function() {
    const empleadoSelect = document.getElementById('id_empleado');
    if (empleadoSelect.value) {
        cargarInfoEmpleado();
    }
});
</script>

<?php include 'views/templates/footer.php'; ?>

