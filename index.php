<?php
/**
 * Enrutador principal del sistema
 * Sistema de Gestión de RRHH
 */

session_start();

// Incluir configuración
require_once 'config/database.php';

// Verificar autenticación (excepto para login)
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
if (!isset($_SESSION['usuario_id']) && $controller != 'auth') {
    header("Location: index.php?controller=auth&action=login");
    exit();
}

// Obtener parámetros de la URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Definir rutas de controladores
$controllers = [
    'home' => 'controllers/HomeController.php',
    'empleado' => 'controllers/EmpleadoController.php',
    'contrato' => 'controllers/ContratoController.php',
    'asistencia' => 'controllers/AsistenciaController.php',
    'liquidacion' => 'controllers/LiquidacionController.php',
    'evento' => 'controllers/EventoController.php',
    'auth' => 'controllers/AuthController.php'
];

// Verificar si el controlador existe
if (array_key_exists($controller, $controllers)) {
    $controllerFile = $controllers[$controller];
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        // Crear instancia del controlador
        $controllerClass = ucfirst($controller) . 'Controller';
        
        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();
            
            // Verificar si el método existe
            if (method_exists($controllerInstance, $action)) {
                $controllerInstance->$action();
            } else {
                die("Error: Acción '$action' no encontrada en el controlador '$controller'");
            }
        } else {
            die("Error: Clase del controlador '$controllerClass' no encontrada");
        }
    } else {
        die("Error: Archivo del controlador no encontrado");
    }
} else {
    die("Error: Controlador '$controller' no existe");
}
?>
