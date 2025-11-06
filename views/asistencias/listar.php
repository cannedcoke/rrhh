<?php 
$titulo = "Registro de Asistencias";
include 'views/templates/header.php'; 
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-calendar-check"></i> Asistencias</h4>
                <div>
                    <a href="index.php?controller=asistencia&action=calendario" class="btn btn-light btn-sm me-2">
                        <i class="bi bi-calendar3"></i> Calendario
                    </a>
                    <a href="index.php?controller=asistencia&action=registrar" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle"></i> Registrar Asistencia
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filtro por fecha -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <form action="index.php" method="GET" class="d-flex">
                            <input type="hidden" name="controller" value="asistencia">
                            <input type="hidden" name="action" value="index">
                            <input type="date" name="fecha" class="form-control me-2" 
                                   value="<?php echo isset($fecha) ? $fecha : date('Y-m-d'); ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </form>
                    </div>
                </div>

                <h5 class="mb-3">Asistencias del: <?php echo date('d/m/Y', strtotime($fecha)); ?></h5>

                <!-- Tabla de asistencias -->
                <?php if (count($asistencias) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Empleado</th>
                                    <th>CI</th>
                                    <th>Hora Entrada</th>
                                    <th>Hora Salida</th>
                                    <th>Horas Trabajadas</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($asistencias as $asist): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($asist['apellido'] . ', ' . $asist['nombre']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($asist['cedula']); ?></td>
                                        <td><?php echo $asist['hora_entrada'] ? date('H:i', strtotime($asist['hora_entrada'])) : '-'; ?></td>
                                        <td><?php echo $asist['hora_salida'] ? date('H:i', strtotime($asist['hora_salida'])) : '-'; ?></td>
                                        <td><?php echo number_format($asist['horas_trabajadas'], 2); ?> hrs</td>
                                        <td>
                                            <?php
                                            $badge_class = 'secondary';
                                            $estado_text = $asist['estado'];
                                            
                                            if ($asist['estado'] == 'presente') {
                                                $badge_class = 'success';
                                            } elseif ($asist['estado'] == 'ausente') {
                                                $badge_class = 'danger';
                                            } elseif ($asist['estado'] == 'tardanza') {
                                                $badge_class = 'warning';
                                            } elseif ($asist['estado'] == 'permiso') {
                                                $badge_class = 'info';
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $badge_class; ?>">
                                                <?php echo ucfirst($estado_text); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="index.php?controller=asistencia&action=detalle&id=<?php echo $asist['id_asistencia']; ?>" 
                                                   class="btn btn-info" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="index.php?controller=asistencia&action=editar&id=<?php echo $asist['id_asistencia']; ?>" 
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="index.php?controller=asistencia&action=eliminar&id=<?php echo $asist['id_asistencia']; ?>" 
                                                   class="btn btn-danger" 
                                                   onclick="return confirm('¿Está seguro de eliminar esta asistencia?')"
                                                   title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted">Total de registros: <strong><?php echo count($asistencias); ?></strong></p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No hay asistencias registradas para esta fecha.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>
