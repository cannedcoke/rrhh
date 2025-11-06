<?php
// Configuración básica de correo. Ajusta según tu servidor SMTP o proveedor.

// Dirección "from" que aparecerá en los correos
if (!defined('EMAIL_FROM')) {
    define('EMAIL_FROM', 'no-reply@localhost');
}
if (!defined('EMAIL_FROM_NAME')) {
    define('EMAIL_FROM_NAME', 'Sistema RRHH');
}

// Si prefieres usar PHPMailer u otra librería, puedes cargarla aquí y usarla en lugar de mail().
?>