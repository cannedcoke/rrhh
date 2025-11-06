<?php
if (!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Sistema RRHH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Recuperar Contraseña</div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['mensaje'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['tipo_mensaje'] == 'success' ? 'success' : 'danger'; ?>">
                                <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?controller=auth&action=procesarOlvideContrasena">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Enviar instrucciones</button>
                            </div>
                        </form>

                        <div class="mt-3">
                            <a href="index.php?controller=auth&action=login">Volver al login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>