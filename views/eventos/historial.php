<?php 
$titulo = "Historial de Eventos - Sistema RRHH";
include 'views/templates/header.php'; 
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-clock-history"></i> Historial de Eventos</h2>
            <a href="index.php?controller=empleado&action=index" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Empleados
            </a>
        </div>
        <p class="text-muted">Historial de eventos aplicados al empleado: <strong><?php echo htmlspecialchars($empleado->nombre . ' ' . $empleado->apellido); ?></strong></p>
    </div>
</div>

<!-- Información del empleado -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Información del Empleado</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Nombre:</strong><br>
                        <?php echo htmlspecialchars($empleado->nombre . ' ' . $empleado->apellido); ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Cédula:</strong><br>
                        <?php echo htmlspecialchars($empleado->cedula); ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Salario Base:</strong><br>
                        ₲ <?php echo number_format($empleado->salario_base, 0, ',', '.'); ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Fecha de Ingreso:</strong><br>
                        <?php echo date('d/m/Y', strtotime($empleado->fecha_ingreso)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros de fecha -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="evento">
                    <input type="hidden" name="action" value="historial">
                    <input type="hidden" name="empleado" value="<?php echo $empleado->id_empleado; ?>">
                    
                    <div class="col-md-4">
                        <label for="desde" class="form-label">Fecha Desde</label>
                        <input type="date" class="form-control" name="desde" id="desde" 
                               value="<?php echo $fecha_desde; ?>">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="hasta" class="form-label">Fecha Hasta</label>
                        <input type="date" class="form-control" name="hasta" id="hasta" 
                               value="<?php echo $fecha_hasta; ?>">
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="index.php?controller=evento&action=historial&empleado=<?php echo $empleado->id_empleado; ?>" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de eventos -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Total Bonificaciones</h6>
                        <h4 class="mb-0">
                            ₲ <?php 
                                $total_bonificaciones = 0;
                                foreach ($eventos_aplicados as $evento) {
                                    if ($evento['tipo'] == '+') {
                                        $total_bonificaciones += $evento['monto_aplicado'];
                                    }
                                }
                                echo number_format($total_bonificaciones, 0, ',', '.');
                            ?>
                        </h4>
                    </div>
                    <div>
                        <i class="bi bi-plus-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Total Descuentos</h6>
                        <h4 class="mb-0">
                            ₲ <?php 
                                $total_descuentos = 0;
                                foreach ($eventos_aplicados as $evento) {
                                    if ($evento['tipo'] == '-') {
                                        $total_descuentos += $evento['monto_aplicado'];
                                    }
                                }
                                echo number_format($total_descuentos, 0, ',', '.');
                            ?>
                        </h4>
                    </div>
                    <div>
                        <i class="bi bi-dash-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Diferencia</h6>
                        <h4 class="mb-0">
                            ₲ <?php 
                                $diferencia = $total_bonificaciones - $total_descuentos;
                                echo number_format($diferencia, 0, ',', '.');
                            ?>
                        </h4>
                    </div>
                    <div>
                        <i class="bi bi-calculator" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de eventos aplicados -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Eventos Aplicados</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Evento</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($eventos_aplicados) > 0): ?>
                                <?php foreach ($eventos_aplicados as $evento): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo date('d/m/Y', strtotime($evento['fecha_aplicacion'])); ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($evento['nombre_evento']); ?></strong>
                                        </td>
                                        <td>
                                            <?php if ($evento['tipo'] == '+'): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-plus-circle"></i> Bonificación
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-dash-circle"></i> Descuento
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong class="<?php echo $evento['tipo'] == '+' ? 'text-success' : 'text-danger'; ?>">
                                                <?php echo $evento['tipo'] . ' ₲ ' . number_format($evento['monto_aplicado'], 0, ',', '.'); ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($evento['observacion']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0">No se encontraron eventos en este período</p>
                                            <small>Intente cambiar el rango de fechas</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="index.php?controller=evento&action=aplicar&empleado=<?php echo $empleado->id_empleado; ?>" 
                           class="btn btn-outline-primary w-100">
                            <i class="bi bi-person-plus"></i><br>
                            <small>Aplicar Evento</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?controller=empleado&action=detalle&id=<?php echo $empleado->id_empleado; ?>" 
                           class="btn btn-outline-info w-100">
                            <i class="bi bi-person"></i><br>
                            <small>Ver Empleado</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?controller=liquidacion&action=calcular&empleado=<?php echo $empleado->id_empleado; ?>" 
                           class="btn btn-outline-success w-100">
                            <i class="bi bi-calculator"></i><br>
                            <small>Calcular Liquidación</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-house"></i><br>
                            <small>Dashboard</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>

