<?php
/**
 * Controlador: Home
 * Maneja la página principal del sistema
 */

require_once 'models/Empleado.php';
require_once 'models/Contrato.php';
require_once 'models/Liquidacion.php';

class HomeController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Página principal con dashboard
     */
    public function index() {
        // Obtener estadísticas
        $empleado = new Empleado($this->db);
        $contrato = new Contrato($this->db);
        $liquidacion = new Liquidacion($this->db);

        $total_empleados = $empleado->contarActivos();
        $contratos_activos = $contrato->contarActivos();
        $liquidaciones_pendientes = $liquidacion->contarPendientes();

        require_once 'views/home/index.php';
    }
}
?>
