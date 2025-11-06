<?php 
$titulo = "Calcular Liquidación";
include 'views/templates/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="bi bi-calculator"></i> Calcular Liquidación de Salario</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=liquidacion&action=procesar" method="POST">
                    <div class="row">
                        <!-- Selección de Empleado -->
                        <div class="col-md-12 mb-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-person"></i> Empleado</h5>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="id_empleado" class="form-label">Seleccionar Empleado *</label>
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

                        <!-- Período de Liquidación -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-calendar-range"></i> Período de Liquidación</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="periodo_desde" class="form-label">Desde *</label>
                            <input type="date" class="form-control" id="periodo_desde" name="periodo_desde" 
                                   value="<?php echo $periodo_desde; ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="periodo_hasta" class="form-label">Hasta *</label>
                            <input type="date" class="form-control" id="periodo_hasta" name="periodo_hasta" 
                                   value="<?php echo $periodo_hasta; ?>" required>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Información:</strong> El sistema calculará automáticamente:
                                <ul class="mb-0 mt-2">
                                    <li>Salario base según tipo de contrato</li>
                                    <li>Horas trabajadas (para jornaleros)</li>
                                    <li>Bonificaciones aplicadas</li>
                                    <li>Descuentos aplicados</li>
                                    <li>IPS (9% del salario base para mensualeros)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?controller=liquidacion&action=index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-calculator"></i> Calcular Liquidación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>
