<?php 
$titulo = "Editar Empleado";
include 'views/templates/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Empleado</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=empleado&action=actualizar" method="POST">
                    <input type="hidden" name="id_empleado" value="<?php echo $this->empleado->id_empleado; ?>">
                    
                    <div class="row">
                        <!-- Información Personal -->
                        <div class="col-md-12 mb-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-person"></i> Información Personal</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($this->empleado->nombre); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido *</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" 
                                   value="<?php echo htmlspecialchars($this->empleado->apellido); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula *</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" 
                                   value="<?php echo htmlspecialchars($this->empleado->cedula); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento *</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                                   value="<?php echo $this->empleado->fecha_nacimiento; ?>" required>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-telephone"></i> Información de Contacto</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="<?php echo htmlspecialchars($this->empleado->telefono); ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" 
                                   value="<?php echo htmlspecialchars($this->empleado->correo); ?>">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2"><?php echo htmlspecialchars($this->empleado->direccion); ?></textarea>
                        </div>

                        <!-- Información Laboral -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-briefcase"></i> Información Laboral</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="salario_base" class="form-label">Salario Base (₲) *</label>
                            <input type="number" class="form-control" id="salario_base" name="salario_base" 
                                   step="0.01" min="0" value="<?php echo $this->empleado->salario_base; ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso *</label>
                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" 
                                   value="<?php echo $this->empleado->fecha_ingreso; ?>" required>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?controller=empleado&action=detalle&id=<?php echo $this->empleado->id_empleado; ?>" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sección de Contratos -->
        <?php if (!empty($contratos)): ?>
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
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
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tipo</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Salario</th>
                                <th>Estado</th>
                                <th>Acción</th>
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
                                        : '<span class="text-muted">-</span>'; 
                                    ?>
                                </td>
                                <td>₲ <?php echo number_format($contrato['monto_base'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    $badge = '';
                                    switch($contrato['estado']) {
                                        case 'activo': $badge = 'bg-success'; break;
                                        case 'finalizado': $badge = 'bg-secondary'; break;
                                        case 'suspendido': $badge = 'bg-warning'; break;
                                        default: $badge = 'bg-dark';
                                    }
                                    ?>
                                    <span class="badge <?php echo $badge; ?>">
                                        <?php echo ucfirst($contrato['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?controller=contrato&action=detalle&id=<?php echo $contrato['id_contrato']; ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-file-text"></i> Contratos del Empleado</h5>
            </div>
            <div class="card-body text-center py-4">
                <i class="bi bi-file-text" style="font-size: 3rem; color: #ddd;"></i>
                <p class="text-muted mt-2 mb-3">Este empleado aún no tiene contratos.</p>
                <a href="index.php?controller=contrato&action=crear&empleado=<?php echo $this->empleado->id_empleado; ?>" 
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Crear Contrato
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>
