<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión RRHH - <?php echo isset($titulo) ? $titulo : 'Inicio'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-people-fill"></i> Sistema RRHH
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 'empleado'): ?>
                    <!-- Menú para empleados -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=empleado&action=asistencia">
                                <i class="bi bi-clock"></i> Mi Asistencia
                            </a>
                        </li>
                    </ul>
                <?php else: ?>
                    <!-- Menú para administradores -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=empleado&action=index">
                                <i class="bi bi-person-badge"></i> Empleados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=contrato&action=index">
                                <i class="bi bi-file-text"></i> Contratos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=asistencia&action=index">
                                <i class="bi bi-calendar-check"></i> Asistencias
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=liquidacion&action=index">
                                <i class="bi bi-cash-stack"></i> Liquidaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=evento&action=index">
                                <i class="bi bi-calendar-event"></i> Eventos
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?php echo $_SESSION['usuario_correo'] ?? ''; ?></h6></li>
                            <li><span class="dropdown-item-text text-muted"><?php echo ucfirst($_SESSION['usuario_tipo'] ?? 'usuario'); ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?controller=auth&action=logout">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container-fluid mt-4">
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?php echo $_SESSION['tipo_mensaje'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['mensaje']; 
                    unset($_SESSION['mensaje']);
                    unset($_SESSION['tipo_mensaje']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
