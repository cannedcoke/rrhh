<?php 
$titulo = "Liquidaciones de Salarios";
include 'views/templates/header.php'; 
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-cash-stack"></i> Liquidaciones</h4>
                <a href="index.php?controller=liquidacion&action=calcular" class="btn btn-dark btn-sm">
                    <i class="bi bi-calculator"></i> Nueva Liquidación
                </a>
            </div>
            <div class="card-body">
                <!-- Tabla de liquidaciones -->
                <?php if (count($liquidaciones) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Empleado</th>
                                    <th>Tipo Contrato</th>
                                    <th>Período</th>
                                    <th>Total Bruto</th>
                                    <th>Descuentos</th>
                                    <th>Neto a Cobrar</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liquidaciones as $liq): ?>
                                    <tr>
                                        <td><?php echo $liq['id_liquidacion']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($liq['apellido'] . ', ' . $liq['nombre']); ?></strong><br>
                                            <small class="text-muted">CI: <?php echo htmlspecialchars($liq['cedula']); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo ucfirst($liq['tipo_contrato']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            echo date('d/m/Y', strtotime($liq['periodo_desde'])) . ' - ' . 
                                                 date('d/m/Y', strtotime($liq['periodo_hasta'])); 
                                            ?>
                                        </td>
                                        <td>₲ <?php echo number_format($liq['total_bruto'], 0, ',', '.'); ?></td>
                                        <td class="text-danger">₲ <?php echo number_format($liq['total_descuentos'], 0, ',', '.'); ?></td>
                                        <td class="text-success fw-bold">₲ <?php echo number_format($liq['neto_cobrar'], 0, ',', '.'); ?></td>
                                        <td>
                                            <?php
                                            $badge_class = 'secondary';
                                            if ($liq['estado'] == 'pendiente') $badge_class = 'warning';
                                            elseif ($liq['estado'] == 'pagado') $badge_class = 'success';
                                            elseif ($liq['estado'] == 'anulado') $badge_class = 'danger';
                                            ?>
                                            <span class="badge bg-<?php echo $badge_class; ?>">
                                                <?php echo ucfirst($liq['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="index.php?controller=liquidacion&action=detalle&id=<?php echo $liq['id_liquidacion']; ?>" 
                                                   class="btn btn-info" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="index.php?controller=liquidacion&action=recibo&id=<?php echo $liq['id_liquidacion']; ?>" 
                                                   class="btn btn-primary" title="Recibo" target="_blank">
                                                    <i class="bi bi-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted">Total de liquidaciones: <strong><?php echo count($liquidaciones); ?></strong></p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No hay liquidaciones registradas.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>
