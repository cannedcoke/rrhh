<?php
// confirmar.php

// Suponiendo que ya tenés los datos de la liquidación en $liquidacion
// $liquidacion podría venir de tu controlador (LiquidacionController)

$titulo = "Confirmar Liquidación - Sistema RRHH";
include 'views/templates/header.php';
?>

<div class="container mt-4">
    <h2><i class="bi bi-check-circle"></i> Confirmar Liquidación</h2>
    
    <?php if (!isset($liquidacion) || empty($liquidacion)): ?>
        <div class="alert alert-danger mt-3">
            No se encontraron datos de la liquidación a confirmar.
            <a href="index.php?controller=liquidacion&action=index" class="alert-link">Volver al listado</a>.
        </div>
    <?php else: ?>
        <div class="card mt-3">
            <div class="card-header">
                <strong>Resumen de la Liquidación</strong>
            </div>
            <div class="card-body">
                <p><strong>Empleado:</strong> <?php echo htmlspecialchars($liquidacion['nombre_empleado']); ?></p>
                <p><strong>Periodo:</strong> <?php echo htmlspecialchars($liquidacion['fecha_desde']) . " - " . htmlspecialchars($liquidacion['fecha_hasta']); ?></p>
                <p><strong>Total a pagar:</strong> ₲ <?php echo number_format($liquidacion['total'], 0, ',', '.'); ?></p>
                
                <hr>
                
                <form method="POST" action="index.php?controller=liquidacion&action=procesarConfirmacion">
                    <input type="hidden" name="id_liquidacion" value="<?php echo $liquidacion['id_liquidacion']; ?>">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=liquidacion&action=index" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Confirmar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/templates/footer.php'; ?>
