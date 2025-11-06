<?php 
$titulo = "Detalle del Empleado";
include 'views/templates/header.php'; 
?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-person-circle"></i> Detalle del Empleado</h4>
                <div>
                    <a href="index.php?controller=empleado&action=editar&id=<?php echo $this->empleado->id_empleado; ?>" 
                       class="btn btn-light btn-sm">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="index.php?controller=empleado&action=index" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Información Personal -->
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-person"></i> Información Personal</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <strong>Nombre Completo:</strong>
                        <p class="text-muted"><?php echo htmlspecialchars($this->empleado->nombre . ' ' . $this->empleado->apellido); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Cédula:</strong>
                        <p class="text-muted"><?php echo htmlspecialchars($this->empleado->cedula); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Fecha de Nacimiento:</strong>
                        <p class="text-muted"><?php echo date('d/m/Y', strtotime($this->empleado->fecha_nacimiento)); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Edad:</strong>
                        <p class="text-muted">
                            <?php 
                            $nacimiento = new DateTime($this->empleado->fecha_nacimiento);
                            $hoy = new DateTime();
                            $edad = $hoy->diff($nacimiento);
                            echo $edad->y . ' años';
                            ?>
                        </p>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-telephone"></i> Información de Contacto</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <strong>Teléfono:</strong>
                        <p class="text-muted"><?php echo htmlspecialchars($this->empleado->telefono); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Correo Electrónico:</strong>
                        <p class="text-muted"><?php echo htmlspecialchars($this->empleado->correo); ?></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <strong>Dirección:</strong>
                        <p class="text-muted"><?php echo htmlspecialchars($this->empleado->direccion); ?></p>
                    </div>
                </div>

                <!-- Información Laboral -->
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-briefcase"></i> Información Laboral</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Salario Base:</strong>
                        <p class="text-muted">₲ <?php echo number_format($this->empleado->salario_base, 0, ',', '.'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Fecha de Ingreso:</strong>
                        <p class="text-muted"><?php echo date('d/m/Y', strtotime($this->empleado->fecha_ingreso)); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Antigüedad:</strong>
                        <p class="text-muted">
                            <?php 
                            $ingreso = new DateTime($this->empleado->fecha_ingreso);
                            $hoy = new DateTime();
                            $antiguedad = $hoy->diff($ingreso);
                            echo $antiguedad->y . ' años, ' . $antiguedad->m . ' meses';
                            ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Estado:</strong>
                        <p>
                            <span class="badge bg-<?php echo $this->empleado->estado == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $this->empleado->estado == 1 ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar con acciones rápidas -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Acciones Rápidas</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php?controller=contrato&action=crear&empleado=<?php echo $this->empleado->id_empleado; ?>" 
                   class="list-group-item list-group-item-action">
                    <i class="bi bi-file-text"></i> Crear Contrato
                </a>
                <a href="index.php?controller=asistencia&action=registrar&empleado=<?php echo $this->empleado->id_empleado; ?>" 
                   class="list-group-item list-group-item-action">
                    <i class="bi bi-calendar-check"></i> Registrar Asistencia
                </a>
                <a href="index.php?controller=liquidacion&action=calcular&empleado=<?php echo $this->empleado->id_empleado; ?>" 
                   class="list-group-item list-group-item-action">
                    <i class="bi bi-calculator"></i> Calcular Liquidación
                </a>
                <a href="index.php?controller=empleado&action=informe&empleado=<?php echo $this->empleado->id_empleado; ?>" 
                   class="list-group-item list-group-item-action">
                    <i class="bi bi-file-earmark-pdf"></i> Generar Informe
                </a>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Resumen</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-file-text text-primary"></i> 
                        <strong>Contratos:</strong> <?php echo count($contratos); ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-calendar-check text-success"></i> 
                        <strong>Asistencias:</strong> 0
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-cash-stack text-warning"></i> 
                        <strong>Liquidaciones:</strong> 0
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Contratos -->
<?php if (!empty($contratos)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-file-text"></i> Contratos del Empleado</h5>
                <a href="index.php?controller=contrato&action=crear&empleado=<?php echo $this->empleado->id_empleado; ?>" 
                   class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle"></i> Nuevo Contrato
                </a>
            </div>
            <div class="card-body">
                <?php if ($contrato_activo): ?>
                <div class="alert alert-success mb-3">
                    <strong><i class="bi bi-check-circle"></i> Contrato Activo:</strong> 
                    <?php echo htmlspecialchars($contrato_activo['tipo_contrato']); ?> - 
                    Desde <?php echo date('d/m/Y', strtotime($contrato_activo['fecha_inicio'])); ?>
                    <?php if ($contrato_activo['fecha_fin']): ?>
                        hasta <?php echo date('d/m/Y', strtotime($contrato_activo['fecha_fin'])); ?>
                    <?php else: ?>
                        (Indefinido)
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tipo de Contrato</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Salario</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contratos as $contrato): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contrato['tipo_contrato']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($contrato['fecha_inicio'])); ?></td>
                                <td>
                                    <?php 
                                    echo $contrato['fecha_fin'] 
                                        ? date('d/m/Y', strtotime($contrato['fecha_fin'])) 
                                        : '<span class="text-muted">Indefinido</span>'; 
                                    ?>
                                </td>
                                <td>₲ <?php echo number_format($contrato['monto_base'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    $badge_class = '';
                                    $estado_texto = '';
                                    switch($contrato['estado']) {
                                        case 'activo':
                                            $badge_class = 'bg-success';
                                            $estado_texto = 'Activo';
                                            break;
                                        case 'finalizado':
                                            $badge_class = 'bg-secondary';
                                            $estado_texto = 'Finalizado';
                                            break;
                                        case 'suspendido':
                                            $badge_class = 'bg-warning';
                                            $estado_texto = 'Suspendido';
                                            break;
                                        default:
                                            $badge_class = 'bg-dark';
                                            $estado_texto = ucfirst($contrato['estado']);
                                    }
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo $estado_texto; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?controller=contrato&action=detalle&id=<?php echo $contrato['id_contrato']; ?>" 
                                       class="btn btn-sm btn-info" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if ($contrato['estado'] == 'activo'): ?>
                                    <a href="index.php?controller=contrato&action=editar&id=<?php echo $contrato['id_contrato']; ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-file-text"></i> Contratos del Empleado</h5>
            </div>
            <div class="card-body text-center py-5">
                <i class="bi bi-file-text" style="font-size: 4rem; color: #ddd;"></i>
                <p class="text-muted mt-3">Este empleado aún no tiene contratos registrados.</p>
                <a href="index.php?controller=contrato&action=crear&empleado=<?php echo $this->empleado->id_empleado; ?>" 
                   class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear Primer Contrato
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/templates/footer.php'; ?>
