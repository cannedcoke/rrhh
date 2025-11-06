<?php 
$titulo = "Gestión de Eventos - Sistema RRHH";
include 'views/templates/header.php'; 
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-calendar-event"></i> Gestión de Eventos</h2>
            <a href="index.php?controller=evento&action=crear" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Evento
            </a>
        </div>
        <p class="text-muted">Administre bonificaciones y descuentos del sistema</p>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="evento">
                    <input type="hidden" name="action" value="index">
                    
                    <div class="col-md-3">
                        <label for="tipo" class="form-label">Tipo de Evento</label>
                        <select class="form-select" name="tipo" id="tipo">
                            <option value="">Todos</option>
                            <option value="+" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == '+') ? 'selected' : ''; ?>>Bonificaciones</option>
                            <option value="-" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == '-') ? 'selected' : ''; ?>>Descuentos</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="buscar" class="form-label">Buscar</label>
                        <input type="text" class="form-control" name="buscar" id="buscar" 
                               placeholder="Nombre del evento..." 
                               value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        <a href="index.php?controller=evento&action=index" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de eventos -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Eventos</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Evento</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($eventos) > 0): ?>
                                <?php foreach ($eventos as $evento): ?>
                                    <tr>
                                        <td><?php echo $evento['id_evento']; ?></td>
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
                                                <?php echo $evento['tipo'] . ' ₲ ' . number_format($evento['monto'], 0, ',', '.'); ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($evento['descripcion']); ?>
                                        </td>
                                        <td>
                                            <?php if ($evento['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="index.php?controller=evento&action=editar&id_evento=<?php echo $evento['id_evento']; ?>" 
                                                class="btn btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="index.php?controller=evento&action=aplicar&evento=<?php echo $evento['id_evento']; ?>" 
                                                   class="btn btn-outline-success" title="Aplicar a Empleado">
                                                    <i class="bi bi-person-plus"></i>
                                                </a>
                                                <?php if ($evento['activo']): ?>
                                                    <a href="index.php?controller=evento&action=desactivar&id=<?php echo $evento['id_evento']; ?>" 
                                                       class="btn btn-outline-warning" 
                                                       onclick="return confirmarEliminacion('¿Desactivar este evento?')" 
                                                       title="Desactivar">
                                                        <i class="bi bi-pause-circle"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0">No se encontraron eventos</p>
                                            <small>Haga clic en "Nuevo Evento" para crear el primero</small>
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
                        <a href="index.php?controller=evento&action=aplicar" class="btn btn-outline-primary w-100">
                            <i class="bi bi-person-plus"></i><br>
                            <small>Aplicar Evento</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?controller=empleado&action=index" class="btn btn-outline-info w-100">
                            <i class="bi bi-people"></i><br>
                            <small>Ver Empleados</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?controller=liquidacion&action=index" class="btn btn-outline-success w-100">
                            <i class="bi bi-cash-stack"></i><br>
                            <small>Liquidaciones</small>
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

