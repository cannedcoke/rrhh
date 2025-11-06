<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema RRHH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card">
                    <div class="card-header login-header text-center py-4">
                        <h3 class="mb-0">
                            <i class="bi bi-people-fill"></i> Sistema RRHH
                        </h3>
                        <p class="mb-0 mt-2">Gestión de Recursos Humanos</p>
                    </div>
                    <div class="card-body p-4">
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
                        
                        <form method="POST" action="index.php?controller=auth&action=procesarLogin">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="correo" name="correo" 
                                           placeholder="usuario@ejemplo.com" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="contrasena" name="contrasena" 
                                           placeholder="Ingrese su contraseña" required>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="recordar">
                                <label class="form-check-label" for="recordar">
                                    Recordar sesión
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-light">
                        <small class="text-muted">
                            Sistema desarrollado por: Miqueas Zarate, Rodney Fariña, Cinthia González, Abel Molinas
                        </small>
                    </div>
                </div>
                
                <!-- Información de usuarios de prueba -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Usuarios de Prueba</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <strong>Administrador:</strong><br>
                                <small class="text-muted">
                                    admin@rrhh.com<br>
                                    Contraseña: 123456
                                </small>
                            </div>
                            <div class="col-6">
                                <strong>RRHH:</strong><br>
                                <small class="text-muted">
                                    rrhh@rrhh.com<br>
                                    Contraseña: 123456
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>

