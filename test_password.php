<?php
require_once 'config/database.php';

header('Content-Type: text/plain; charset=utf-8');

$email = 'admin@rrhh.com';
$password = '123456';

$db = (new Database())->getConnection();
if (!$db) {
    echo "No se pudo conectar a la base de datos. Comprueba la configuración en config/database.php\n";
    exit;
}

$stmt = $db->prepare('SELECT id_usuario, nombre, apellido, correo, contrasena FROM usuario WHERE correo = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Usuario con correo $email no encontrado en la base de datos.\n";
    exit;
}

echo "Usuario encontrado: " . $user['nombre'] . " " . $user['apellido'] . " (id=" . $user['id_usuario'] . ")\n";
echo "Hash almacenado (inicio): " . substr($user['contrasena'], 0, 60) . "...\n";

if (password_verify($password, $user['contrasena'])) {
    echo "password_verify -> OK: la contraseña '" . $password . "' coincide con el hash en la BD.\n";
} else {
    echo "password_verify -> FAIL: la contraseña '" . $password . "' NO coincide con el hash en la BD.\n";

    // Comprobar si la contraseña está guardada en texto plano
    if ($password === $user['contrasena']) {
        echo "La contraseña en la BD parece estar en texto plano y coincide exactamente.\n";
    } else {
        echo "La contraseña en la BD no coincide ni como hash ni como texto plano.\n";
    }
}

// Mostrar instrucciones
echo "\nPara probar: abre en tu navegador: http://localhost/rrhh_salarios/test_password.php\n";
echo "Si estás usando otro alias o carpeta en XAMPP, ajusta la URL.\n";

?>
