<?php
/**
 * Controlador: Asistencia
 * Maneja las acciones relacionadas con registro de asistencias
 */

require_once 'models/Asistencia.php';
require_once 'models/Empleado.php';

class AsistenciaController {
    private $db;
    private $asistencia;
    private $empleado;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->asistencia = new Asistencia($this->db);
        $this->empleado = new Empleado($this->db);
    }

    /**
     * Mostrar lista de asistencias
     */
    public function index() {
        // Por defecto mostrar asistencias del mes actual
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
        
        $stmt = $this->asistencia->listarPorFecha($fecha);
        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/asistencias/listar.php';
    }

    /**
     * Mostrar calendario de asistencias
     */
    public function calendario() {
        $mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
        $anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
        
        require_once 'views/asistencias/calendario.php';
    }

    /**
     * Mostrar formulario para registrar asistencia
     */
    public function registrar() {
        // Obtener lista de empleados activos
        $stmt = $this->empleado->listar();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Si viene el ID del empleado por parámetro
        $id_empleado_sel = isset($_GET['empleado']) ? $_GET['empleado'] : null;
        
        require_once 'views/asistencias/registrar.php';
    }

    /**
     * Guardar nueva asistencia
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_empleado = $_POST['id_empleado'];
            $fecha = $_POST['fecha'];
            
            // Verificar si ya existe asistencia para ese día
            if ($this->asistencia->existeAsistencia($id_empleado, $fecha)) {
                $_SESSION['mensaje'] = "Ya existe un registro de asistencia para este empleado en esta fecha";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=asistencia&action=registrar");
                exit();
            }
            
            $this->asistencia->id_empleado = $id_empleado;
            $this->asistencia->fecha = $fecha;
            $this->asistencia->hora_entrada = !empty($_POST['hora_entrada']) ? $_POST['hora_entrada'] : null;
            $this->asistencia->hora_salida = !empty($_POST['hora_salida']) ? $_POST['hora_salida'] : null;
            $this->asistencia->observacion = isset($_POST['observacion']) ? $_POST['observacion'] : '';
            $this->asistencia->estado = $_POST['estado'];

            // Calcular horas trabajadas en el servidor para mayor seguridad y consistencia
            $horas_trabajadas = 0;
            if ($this->asistencia->hora_entrada && $this->asistencia->hora_salida) {
                $entrada = new DateTime($this->asistencia->fecha . ' ' . $this->asistencia->hora_entrada);
                $salida = new DateTime($this->asistencia->fecha . ' ' . $this->asistencia->hora_salida);
                if ($salida < $entrada) { // Turno nocturno que cruza la medianoche
                    $salida->modify('+1 day');
                }
                $horas_trabajadas = ($salida->getTimestamp() - $entrada->getTimestamp()) / 3600;
            }
            $this->asistencia->horas_trabajadas = round($horas_trabajadas, 2);

            if ($this->asistencia->registrar()) {
                $_SESSION['mensaje'] = "Asistencia registrada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=asistencia&action=index&fecha=" . $fecha);
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al registrar asistencia";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=asistencia&action=registrar");
                exit();
            }
        }
    }

    /**
     * Mostrar detalle de una asistencia
     */
    public function detalle() {
        if (isset($_GET['id'])) {
            $this->asistencia->id_asistencia = $_GET['id'];
            $asistencia = $this->asistencia->obtenerPorId();
            
            if ($asistencia) {
                require_once 'views/asistencias/detalle.php';
            } else {
                $_SESSION['mensaje'] = "Asistencia no encontrada";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=asistencia&action=index");
                exit();
            }
        } else {
            header("Location: index.php?controller=asistencia&action=index");
            exit();
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function editar() {
        if (isset($_GET['id'])) {
            $this->asistencia->id_asistencia = $_GET['id'];
            $asistencia = $this->asistencia->obtenerPorId();
            
            if ($asistencia) {
                require_once 'views/asistencias/editar.php';
            } else {
                $_SESSION['mensaje'] = "Asistencia no encontrada";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=asistencia&action=index");
                exit();
            }
        } else {
            header("Location: index.php?controller=asistencia&action=index");
            exit();
        }
    }

    /**
     * Actualizar asistencia
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->asistencia->id_asistencia = $_POST['id_asistencia'];
            $this->asistencia->hora_entrada = !empty($_POST['hora_entrada']) ? $_POST['hora_entrada'] : null;
            $this->asistencia->hora_salida = !empty($_POST['hora_salida']) ? $_POST['hora_salida'] : null;
            $this->asistencia->observacion = isset($_POST['observacion']) ? $_POST['observacion'] : '';
            $this->asistencia->estado = $_POST['estado'];

            // Recalcular horas trabajadas en el servidor
            $horas_trabajadas = 0;
            if ($this->asistencia->hora_entrada && $this->asistencia->hora_salida) {
                $asistencia_actual = $this->asistencia->obtenerPorId();
                $entrada = new DateTime($asistencia_actual['fecha'] . ' ' . $this->asistencia->hora_entrada);
                $salida = new DateTime($asistencia_actual['fecha'] . ' ' . $this->asistencia->hora_salida);
                if ($salida < $entrada) { $salida->modify('+1 day'); }
                $horas_trabajadas = ($salida->getTimestamp() - $entrada->getTimestamp()) / 3600;
            }
            $this->asistencia->horas_trabajadas = round($horas_trabajadas, 2);

            if ($this->asistencia->actualizar()) {
                $_SESSION['mensaje'] = "Asistencia actualizada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=asistencia&action=detalle&id=" . $this->asistencia->id_asistencia);
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al actualizar asistencia";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=asistencia&action=editar&id=" . $this->asistencia->id_asistencia);
                exit();
            }
        }
    }

    /**
     * Eliminar asistencia
     */
    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->asistencia->id_asistencia = $_GET['id'];
            
            if ($this->asistencia->eliminar()) {
                $_SESSION['mensaje'] = "Asistencia eliminada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar asistencia";
                $_SESSION['tipo_mensaje'] = "error";
            }
        }
        
        header("Location: index.php?controller=asistencia&action=index");
        exit();
    }

    /**
     * Ver asistencias por empleado
     */
    public function porEmpleado() {
        if (isset($_GET['id'])) {
            $id_empleado = $_GET['id'];
            $fecha_inicio = isset($_GET['desde']) ? $_GET['desde'] : date('Y-m-01');
            $fecha_fin = isset($_GET['hasta']) ? $_GET['hasta'] : date('Y-m-d');
            
            // Obtener datos del empleado
            $this->empleado->id_empleado = $id_empleado;
            $this->empleado->obtenerPorId();
            
            // Obtener asistencias
            $stmt = $this->asistencia->listarPorEmpleado($id_empleado, $fecha_inicio, $fecha_fin);
            $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Obtener estadísticas
            $mes = date('m', strtotime($fecha_inicio));
            $anio = date('Y', strtotime($fecha_inicio));
            $estadisticas = $this->asistencia->estadisticasPorEmpleado($id_empleado, $mes, $anio);
            
            require_once 'views/asistencias/por_empleado.php';
        } else {
            header("Location: index.php?controller=asistencia&action=index");
            exit();
        }
    }

    /**
     * Registrar asistencia masiva (todos los empleados de un día)
     */
    public function registrarMasivo() {
        // Obtener lista de empleados activos
        $stmt = $this->empleado->listar();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
        
        require_once 'views/asistencias/registrar_masivo.php';
    }
}
?>
