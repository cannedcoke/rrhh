<?php 
$titulo = "Dashboard - Sistema RRHH";
include 'views/templates/header.php'; 
?>

<div class="row">
    <!-- Título principal -->
    <div class="col-12 mb-4">
        <h2><i class="bi bi-speedometer2"></i> Panel de Control</h2>
        <p class="text-muted">Bienvenido al Sistema de Gestión de Recursos Humanos</p>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Empleados Activos</h6>
                        <h2 class="mb-0"><?php echo $total_empleados; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-people-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="index.php?controller=empleado&action=index" class="text-white text-decoration-none">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Contratos Activos</h6>
                        <h2 class="mb-0"><?php echo $contratos_activos; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-file-text-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="index.php?controller=contrato&action=index" class="text-white text-decoration-none">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-white bg-warning shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Liquidaciones Pendientes</h6>
                        <h2 class="mb-0"><?php echo $liquidaciones_pendientes; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-cash-stack" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="index.php?controller=liquidacion&action=index" class="text-white text-decoration-none">
                    Ver todas <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Sección de accesos rápidos -->
<div class="row mt-4">
    <div class="col-12 mb-3">
        <h4><i class="bi bi-lightning-fill"></i> Accesos Rápidos</h4>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100 text-center">
            <div class="card-body">
                <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Nuevo Empleado</h5>
                <p class="card-text">Registrar un nuevo empleado en el sistema</p>
                <a href="index.php?controller=empleado&action=crear" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Agregar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100 text-center">
            <div class="card-body">
                <i class="bi bi-file-earmark-text-fill text-success" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Nuevo Contrato</h5>
                <p class="card-text">Generar un contrato laboral</p>
                <a href="index.php?controller=contrato&action=crear" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Crear
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100 text-center">
            <div class="card-body">
                <i class="bi bi-calendar-check-fill text-info" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Registrar Asistencia</h5>
                <p class="card-text">Registrar entrada/salida de empleados</p>
                <a href="index.php?controller=asistencia&action=registrar" class="btn btn-info btn-sm">
                    <i class="bi bi-check-circle"></i> Registrar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100 text-center">
            <div class="card-body">
                <i class="bi bi-calculator-fill text-warning" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Calcular Liquidación</h5>
                <p class="card-text">Calcular salarios del período</p>
                <a href="index.php?controller=liquidacion&action=calcular" class="btn btn-warning btn-sm">
                    <i class="bi bi-calculator"></i> Calcular
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Información del sistema -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Módulos Disponibles:</h6>
                        <ul>
                            <li><strong>Empleados:</strong> Gestión completa de información de empleados</li>
                            <li><strong>Contratos:</strong> Creación y administración de contratos laborales</li>
                            <li><strong>Asistencias:</strong> Control de horarios y horas trabajadas</li>
                            <li><strong>Liquidaciones:</strong> Cálculo automático de salarios</li>
                            <li><strong>Eventos:</strong> Gestión de bonificaciones y descuentos</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Características:</h6>
                        <ul>
                            <li>✓ Generación automática de contratos</li>
                            <li>✓ Registro visual de asistencias con calendario</li>
                            <li>✓ Informes en PDF y CSV</li>
                            <li>✓ Cálculo automático de liquidaciones</li>
                            <li>✓ Control de bonificaciones y descuentos</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>
