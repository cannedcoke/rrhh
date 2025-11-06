<?php 
$titulo = "Lista de Empleados";
include 'views/templates/header.php'; 
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-person-badge"></i> Empleados</h4>
                <a href="index.php?controller=empleado&action=crear" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle"></i> Nuevo Empleado
                </a>
            </div>
            <div class="card-body">
                <!-- Buscador -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="index.php" method="GET" class="d-flex">
                            <input type="hidden" name="controller" value="empleado">
                            <input type="hidden" name="action" value="buscar">
                            <input type="text" name="q" class="form-control me-2" placeholder="Buscar por nombre o cédula...">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tabla de empleados -->
                <?php if (count($empleados) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Cédula</th>
                                    <th>Nombre Completo</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Salario Base</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($empleados as $emp): ?>
                                    <tr>
                                        <td><?php echo $emp['id_empleado']; ?></td>
                                        <td><?php echo htmlspecialchars($emp['cedula']); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($emp['apellido'] . ', ' . $emp['nombre']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($emp['telefono']); ?></td>
                                        <td><?php echo htmlspecialchars($emp['correo']); ?></td>
                                        <td>₲ <?php echo number_format($emp['salario_base'], 0, ',', '.'); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($emp['fecha_ingreso'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="index.php?controller=empleado&action=detalle&id=<?php echo $emp['id_empleado']; ?>" 
                                                   class="btn btn-info" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="index.php?controller=empleado&action=editar&id=<?php echo $emp['id_empleado']; ?>" 
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="index.php?controller=empleado&action=eliminar&id=<?php echo $emp['id_empleado']; ?>" 
                                                   class="btn btn-danger" 
                                                   onclick="return confirm('¿Está seguro de eliminar este empleado?')"
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
                        <p class="text-muted">Total de empleados: <strong><?php echo count($empleados); ?></strong></p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No hay empleados registrados.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>
