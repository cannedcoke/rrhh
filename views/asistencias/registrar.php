<?php 
$titulo = "Registrar Asistencia";
include 'views/templates/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="bi bi-calendar-check"></i> Registrar Asistencia</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=asistencia&action=guardar" method="POST" onsubmit="return validarFormularioAsistencia(this)">
                    <div class="row">
                        <!-- Selección de Empleado y Fecha -->
                        <div class="col-md-12 mb-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-person"></i> Información Básica</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_empleado" class="form-label">Empleado *</label>
                            <select class="form-select" id="id_empleado" name="id_empleado" required>
                                <option value="">Seleccione un empleado...</option>
                                <?php foreach ($empleados as $emp): ?>
                                    <option value="<?php echo $emp['id_empleado']; ?>" 
                                            <?php echo ($id_empleado_sel == $emp['id_empleado']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($emp['apellido'] . ', ' . $emp['nombre'] . ' - CI: ' . $emp['cedula']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <!-- Horarios -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-clock"></i> Horarios</h5>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="hora_entrada" class="form-label">Hora de Entrada *</label>
                            <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="hora_salida" class="form-label">Hora de Salida *</label>
                            <input type="time" class="form-control" id="hora_salida" name="hora_salida" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="horas_trabajadas" class="form-label">Horas Trabajadas</label>
                            <input type="number" class="form-control" id="horas_trabajadas" name="horas_trabajadas" 
                                   step="0.01" min="0" readonly>
                            <small class="text-muted">Se calcula automáticamente</small>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="presente" selected>Presente</option>
                                <option value="ausente">Ausente</option>
                                <option value="tardanza">Tardanza</option>
                                <option value="permiso">Permiso</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="observacion" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observacion" name="observacion" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?controller=asistencia&action=index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-save"></i> Registrar Asistencia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const entradaInput = document.getElementById('hora_entrada');
    const salidaInput = document.getElementById('hora_salida');
    const horasTrabajadasInput = document.getElementById('horas_trabajadas');
    const fechaInput = document.getElementById('fecha');

    function calcularHoras() {
        const fecha = fechaInput.value;
        const entrada = entradaInput.value;
        const salida = salidaInput.value;

        if (fecha && entrada && salida) {
            const fechaEntrada = new Date(`${fecha}T${entrada}`);
            let fechaSalida = new Date(`${fecha}T${salida}`);

            if (fechaSalida < fechaEntrada) { // Asumir que es un turno nocturno que cruza la medianoche
                fechaSalida.setDate(fechaSalida.getDate() + 1);
            }

            const diffMs = fechaSalida - fechaEntrada;
            const diffHoras = diffMs / (1000 * 60 * 60);
            horasTrabajadasInput.value = diffHoras.toFixed(2);
        }
    }

    entradaInput.addEventListener('change', calcularHoras);
    salidaInput.addEventListener('change', calcularHoras);
    fechaInput.addEventListener('change', calcularHoras);
});
</script>

<?php include 'views/templates/footer.php'; ?>
