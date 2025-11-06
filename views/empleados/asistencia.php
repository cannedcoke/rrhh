<?php 
$titulo = "Mi Asistencia";
include 'views/templates/headeremple.php'; 
?>

<div class="row">
    <!-- Panel de Control de Asistencia -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock"></i> Control de Asistencia</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <h6>Hoy: <?php echo date('d/m/Y'); ?></h6>
                    <h4 id="hora-actual" class="text-primary"></h4>
                </div>

                <?php if (!$asistencia_hoy): ?>
                    <!-- Botón de Entrada -->
                    <form action="index.php?controller=empleado&action=registrarEntrada" method="POST" class="mb-3">
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-play-circle"></i> Registrar Entrada
                        </button>
                    </form>
                <?php elseif ($asistencia_hoy['hora_entrada'] && !$asistencia_hoy['hora_salida']): ?>
                    <!-- Botón de Salida -->
                    <div class="alert alert-info mb-3">
                        <strong>Entrada:</strong> <?php echo date('H:i', strtotime($asistencia_hoy['hora_entrada'])); ?>
                    </div>
                    <form action="index.php?controller=empleado&action=registrarSalida" method="POST">
                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <i class="bi bi-stop-circle"></i> Registrar Salida
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Día completado -->
                    <div class="alert alert-success">
                        <strong>Entrada:</strong> <?php echo date('H:i', strtotime($asistencia_hoy['hora_entrada'])); ?><br>
                        <strong>Salida:</strong> <?php echo date('H:i', strtotime($asistencia_hoy['hora_salida'])); ?><br>
                        <strong>Horas:</strong> <?php echo number_format($asistencia_hoy['horas_trabajadas'], 2); ?>h
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Información del Empleado -->
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-person"></i> Mi Información</h6>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> <?php echo $empleado['nombre'] . ' ' . $empleado['apellido']; ?></p>
                <p><strong>Cédula:</strong> <?php echo $empleado['cedula']; ?></p>
                <p><strong>Salario Base:</strong> ₲<?php echo number_format($empleado['salario_base'], 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>

    <!-- Historial de Asistencias -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Mis Asistencias - <?php echo date('F Y'); ?></h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="generarReporte('semanal')">
                        <i class="bi bi-file-earmark-text"></i> Semanal
                    </button>
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="generarReporte('mensual')">
                        <i class="bi bi-file-earmark-text"></i> Mensual
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($asistencias)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No hay registros de asistencia este mes</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Horas</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($asistencias as $asist): ?>
                                <tr class="<?php echo $asist['fecha'] == date('Y-m-d') ? 'table-primary' : ''; ?>">
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($asist['fecha'])); ?>
                                        <?php if ($asist['fecha'] == date('Y-m-d')): ?>
                                            <span class="badge bg-primary">Hoy</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo $asist['hora_entrada'] ? date('H:i', strtotime($asist['hora_entrada'])) : '-'; ?>
                                    </td>
                                    <td>
                                        <?php echo $asist['hora_salida'] ? date('H:i', strtotime($asist['hora_salida'])) : '-'; ?>
                                    </td>
                                    <td>
                                        <?php echo $asist['horas_trabajadas'] ? number_format($asist['horas_trabajadas'], 2) . 'h' : '-'; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $estado_class = '';
                                        switch($asist['estado']) {
                                            case 'presente':
                                                $estado_class = 'bg-success';
                                                break;
                                            case 'ausente':
                                                $estado_class = 'bg-danger';
                                                break;
                                            case 'tardanza':
                                                $estado_class = 'bg-warning';
                                                break;
                                            case 'permiso':
                                                $estado_class = 'bg-info';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $estado_class; ?>">
                                            <?php echo ucfirst($asist['estado']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Resumen del mes -->
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5><?php echo count(array_filter($asistencias, function($a) { return $a['estado'] == 'presente'; })); ?></h5>
                                    <small>Días Presentes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5><?php echo count(array_filter($asistencias, function($a) { return $a['estado'] == 'ausente'; })); ?></h5>
                                    <small>Ausencias</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5><?php echo count(array_filter($asistencias, function($a) { return $a['estado'] == 'tardanza'; })); ?></h5>
                                    <small>Tardanzas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5><?php echo number_format(array_sum(array_column($asistencias, 'horas_trabajadas')), 1); ?>h</h5>
                                    <small>Total Horas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Reportes -->
<div class="modal fade" id="reporteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generar Reporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reporteForm">
                    <div class="mb-3">
                        <label for="tipo_reporte" class="form-label">Tipo de Reporte</label>
                        <select class="form-select" id="tipo_reporte" name="tipo">
                            <option value="semanal">Semanal</option>
                            <option value="mensual">Mensual</option>
                            <option value="personalizado">Personalizado</option>
                        </select>
                    </div>
                    <div class="mb-3" id="fechas_personalizadas" style="display: none;">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                    </div>
                    <div class="mb-3">
                        <label for="formato" class="form-label">Formato</label>
                        <select class="form-select" id="formato" name="formato">
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="generarReporteFinal()">Generar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Actualizar hora en tiempo real
function actualizarHora() {
    const ahora = new Date();
    const hora = ahora.toLocaleTimeString('es-ES', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    document.getElementById('hora-actual').textContent = hora;
}

// Actualizar cada segundo
setInterval(actualizarHora, 1000);
actualizarHora();

// Mostrar modal de reportes
function generarReporte(tipo) {
    const modal = new bootstrap.Modal(document.getElementById('reporteModal'));
    document.getElementById('tipo_reporte').value = tipo;
    
    if (tipo === 'personalizado') {
        document.getElementById('fechas_personalizadas').style.display = 'block';
    } else {
        document.getElementById('fechas_personalizadas').style.display = 'none';
    }
    
    modal.show();
}

// Generar reporte final
function generarReporteFinal() {
    const form = document.getElementById('reporteForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    window.location.href = `index.php?controller=empleado&action=generarReporte&${params.toString()}`;
}

// Mostrar/ocultar fechas personalizadas
document.getElementById('tipo_reporte').addEventListener('change', function() {
    const fechasDiv = document.getElementById('fechas_personalizadas');
    if (this.value === 'personalizado') {
        fechasDiv.style.display = 'block';
    } else {
        fechasDiv.style.display = 'none';
    }
});
</script>

<?php include 'views/templates/footer.php'; ?>

