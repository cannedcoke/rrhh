<?php
/**
 * Controlador: Auth
 * Maneja autenticación y autorización
 */

class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Mostrar formulario de login
     */
    public function login() {
        require_once 'views/auth/login.php';
    }

    /**
     * Procesar login
     */
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];

            $query = "SELECT * FROM usuario WHERE correo = ? AND estado = 1 LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $correo);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si no se encontró usuario o la contraseña está vacía, fallo
            if (!$usuario) {
                $_SESSION['mensaje'] = "Correo o contraseña incorrectos";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=login");
                exit();
            }

            $stored = isset($usuario['contrasena']) ? $usuario['contrasena'] : '';

            $passwordMatch = false;

            // 1) Intentar verificación con password_verify (contraseña hasheada)
            if (!empty($stored) && password_verify($contrasena, $stored)) {
                $passwordMatch = true;

                // Si el hash es débil o se requiere rehash, actualizar
                if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                    $newHash = password_hash($contrasena, PASSWORD_DEFAULT);
                    $updateStmt = $this->db->prepare("UPDATE usuario SET contrasena = ? WHERE id_usuario = ?");
                    $updateStmt->execute([$newHash, $usuario['id_usuario']]);
                }
            }

            // 2) Comprobar formatos legacy: MD5, SHA1 o texto plano
            if (!$passwordMatch) {
                // MD5
                if (!empty($stored) && hash_equals($stored, md5($contrasena))) {
                    $passwordMatch = true;
                }

                // SHA1
                if (!$passwordMatch && !empty($stored) && hash_equals($stored, sha1($contrasena))) {
                    $passwordMatch = true;
                }

                // Texto plano
                if (!$passwordMatch && $contrasena === $stored) {
                    $passwordMatch = true;
                }

                // Si encontramos match en un formato legacy, migrar a password_hash
                if ($passwordMatch) {
                    $newHash = password_hash($contrasena, PASSWORD_DEFAULT);
                    $updateStmt = $this->db->prepare("UPDATE usuario SET contrasena = ? WHERE id_usuario = ?");
                    $updateStmt->execute([$newHash, $usuario['id_usuario']]);
                }
            }

            if ($passwordMatch) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
                $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
                $_SESSION['usuario_correo'] = $usuario['correo'];

                $_SESSION['mensaje'] = "Bienvenido " . $usuario['nombre'];
                $_SESSION['tipo_mensaje'] = "success";
                // Si el usuario tiene marcado forzar_cambio, redirigir al formulario de cambio
                if (isset($usuario['forzar_cambio']) && $usuario['forzar_cambio']) {
                    header("Location: index.php?controller=auth&action=cambiarContrasena");
                    exit();
                }

                // Redirigir según el tipo de usuario
                if ($usuario['tipo_usuario'] == 'empleado') {
                    header("Location: index.php?controller=empleado&action=asistencia");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $_SESSION['mensaje'] = "Correo o contraseña incorrectos";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=login");
                exit();
            }
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }

    /**
     * Mostrar formulario para cambiar contraseña obligatoria
     */
    public function cambiarContrasena() {
        // Asegurarse que el usuario esté logueado
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        require_once 'views/auth/cambiar_contrasena.php';
    }

    /**
     * Procesar cambio de contraseña
     */
    public function procesarCambioContrasena() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['usuario_id'])) {
                header("Location: index.php?controller=auth&action=login");
                exit();
            }

            $nueva = isset($_POST['nueva']) ? $_POST['nueva'] : '';
            $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';

            if (empty($nueva) || empty($confirm)) {
                $_SESSION['mensaje'] = "Ambos campos son requeridos";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=cambiarContrasena");
                exit();
            }

            if ($nueva !== $confirm) {
                $_SESSION['mensaje'] = "Las contraseñas no coinciden";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=cambiarContrasena");
                exit();
            }

            // Actualizar contraseña con hash seguro y limpiar forzar_cambio
            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE usuario SET contrasena = ?, forzar_cambio = 0 WHERE id_usuario = ?");
            $stmt->execute([$hash, $_SESSION['usuario_id']]);

            $_SESSION['mensaje'] = "Contraseña actualizada correctamente";
            $_SESSION['tipo_mensaje'] = "success";

            header("Location: index.php");
            exit();
        }
    }

    /**
     * Mostrar formulario para recuperar contraseña (olvide)
     */
    public function olvideContrasena() {
        require_once 'views/auth/olvide_contrasena.php';
    }

    /**
     * Procesar solicitud de recuperación: generar token, guardarlo y enviar email
     */
    public function procesarOlvideContrasena() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';

            if (empty($correo)) {
                $_SESSION['mensaje'] = "Ingrese su correo electrónico";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=olvideContrasena");
                exit();
            }

            // Verificar usuario
            $stmt = $this->db->prepare("SELECT id_usuario, nombre FROM usuario WHERE correo = ? AND estado = 1 LIMIT 1");
            $stmt->execute([$correo]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                // No revelar si no existe el correo
                $_SESSION['mensaje'] = "Si el correo existe recibirá un email con instrucciones";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=auth&action=login");
                exit();
            }

            // Generar token y expiración (1 hora)
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', time() + 3600);

            // Crear tabla password_resets si no existe (intento seguro)
            try {
                $this->db->exec("CREATE TABLE IF NOT EXISTS password_resets (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    correo VARCHAR(150) NOT NULL,
                    token VARCHAR(128) NOT NULL,
                    expires_at DATETIME NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX(correo), INDEX(token)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            } catch (Exception $e) {
                // no fatal; seguiremos intentando insertar
            }

            // Insertar o actualizar token
            $stmt = $this->db->prepare("REPLACE INTO password_resets (id, correo, token, expires_at) VALUES ((SELECT id FROM (SELECT id FROM password_resets WHERE correo = ? LIMIT 1) AS tmp), ?, ?, ?)");
            // La consulta REPLACE anterior puede fallar en algunos motd; usar INSERT ... ON DUPLICATE KEY sería más correcto con clave única.
            try {
                // Intentar borrado anterior e inserción limpia
                $del = $this->db->prepare("DELETE FROM password_resets WHERE correo = ?");
                $del->execute([$correo]);
                $ins = $this->db->prepare("INSERT INTO password_resets (correo, token, expires_at) VALUES (?, ?, ?)");
                $ins->execute([$correo, $token, $expires_at]);
            } catch (Exception $e) {
                // Si falla la inserción, reportar error
                $_SESSION['mensaje'] = "Error al procesar la solicitud. Intente nuevamente más tarde.";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=login");
                exit();
            }

            // Enviar email con link de recuperación
            require_once 'config/email.php';

            $host = $_SERVER['HTTP_HOST'];
            $path = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");
            $resetLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . $host . $path . "/index.php?controller=auth&action=resetContrasena&token=" . $token;

            $subject = 'Recuperar contraseña - Sistema RRHH';
            $message = "Hola " . $user['nombre'] . ",\n\nHemos recibido una solicitud para restablecer la contraseña de su cuenta. Para cambiar su contraseña haga clic en el siguiente enlace (válido 1 hora):\n\n" . $resetLink . "\n\nSi usted no solicitó este cambio, puede ignorar este mensaje.\n\nSaludos,\nSistema RRHH\n";

            $headers = 'From: ' . EMAIL_FROM_NAME . ' <' . EMAIL_FROM . ">\r\n" .
                       'Reply-To: ' . EMAIL_FROM . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            $mailSent = false;
            // Intentar enviar con mail(); el servidor debe tenerlo configurado
            try {
                $mailSent = mail($correo, $subject, $message, $headers);
            } catch (Exception $e) {
                $mailSent = false;
            }

            if (!$mailSent) {
                // Para entornos de desarrollo, mostrar el enlace en pantalla en lugar de fallar
                $_SESSION['mensaje'] = "Se generó el enlace de recuperación. En entorno local podría no haberse enviado el email. Copie este enlace: " . $resetLink;
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Si el correo existe recibirá un email con instrucciones";
                $_SESSION['tipo_mensaje'] = "success";
            }

            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    /**
     * Mostrar formulario para resetear contraseña (vía token)
     */
    public function resetContrasena() {
        $token = isset($_GET['token']) ? $_GET['token'] : '';
        if (empty($token)) {
            $_SESSION['mensaje'] = "Token inválido";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        // Buscar token
        $stmt = $this->db->prepare("SELECT correo, expires_at FROM password_resets WHERE token = ? LIMIT 1");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $_SESSION['mensaje'] = "Token inválido o caducado";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        if (strtotime($row['expires_at']) < time()) {
            // Borrar token expirado
            $del = $this->db->prepare("DELETE FROM password_resets WHERE token = ?");
            $del->execute([$token]);

            $_SESSION['mensaje'] = "El enlace ha expirado";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        // Mostrar formulario con token oculto
        $correo = $row['correo'];
        require_once 'views/auth/reset_contrasena.php';
    }

    /**
     * Procesar el reset de contraseña vía token
     */
    public function procesarResetContrasena() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = isset($_POST['token']) ? $_POST['token'] : '';
            $nueva = isset($_POST['nueva']) ? $_POST['nueva'] : '';
            $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';

            if (empty($token) || empty($nueva) || empty($confirm)) {
                $_SESSION['mensaje'] = "Todos los campos son requeridos";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=login");
                exit();
            }

            if ($nueva !== $confirm) {
                $_SESSION['mensaje'] = "Las contraseñas no coinciden";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=resetContrasena&token=" . urlencode($token));
                exit();
            }

            // Buscar token
            $stmt = $this->db->prepare("SELECT correo, expires_at FROM password_resets WHERE token = ? LIMIT 1");
            $stmt->execute([$token]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || strtotime($row['expires_at']) < time()) {
                $_SESSION['mensaje'] = "Token inválido o expirado";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=auth&action=login");
                exit();
            }

            $correo = $row['correo'];

            // Actualizar contraseña del usuario
            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            $upd = $this->db->prepare("UPDATE usuario SET contrasena = ?, forzar_cambio = 0 WHERE correo = ?");
            $upd->execute([$hash, $correo]);

            // Borrar token usado
            $del = $this->db->prepare("DELETE FROM password_resets WHERE token = ?");
            $del->execute([$token]);

            $_SESSION['mensaje'] = "Contraseña actualizada correctamente. Ya puede iniciar sesión.";
            $_SESSION['tipo_mensaje'] = "success";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public static function verificarSesion() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }
}
?>
