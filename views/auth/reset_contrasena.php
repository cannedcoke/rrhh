<?php
if (!isset($_SESSION)) session_start();
if (!isset($correo) || !isset($token)) {
    echo 'Acceso no válido';
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Sistema RRHH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Restablecer Contraseña</div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['mensaje'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['tipo_mensaje'] == 'success' ? 'success' : 'danger'; ?>">
                                <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?controller=auth&action=procesarResetContrasena">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($correo); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="nueva" class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control" id="nueva" name="nueva" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm" class="form-label">Confirmar contraseña</label>
                                <input type="password" class="form-control" id="confirm" name="confirm" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Restablecer contraseña</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>