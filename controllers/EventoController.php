<?php
/**
 * Controlador: Evento
 * Maneja las acciones relacionadas con eventos (bonificaciones y descuentos)
 */

require_once 'models/Evento.php';
require_once 'models/Empleado.php';

class EventoController {
    private $db;
    private $evento;
    private $empleado;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->evento = new Evento($this->db);
        $this->empleado = new Empleado($this->db);
    }

    /**
     * Mostrar lista de eventos
     */
    public function index() {
        $stmt = $this->evento->listar();
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/eventos/listar.php';
    }

    /**
     * Mostrar formulario para crear evento
     */
    public function crear() {
        require_once 'views/eventos/crear.php';
    }

    /**
     * Guardar nuevo evento
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->evento->nombre_evento = $_POST['nombre_evento'];
            $this->evento->tipo = $_POST['tipo'];
            $this->evento->monto = $_POST['monto'];
            $this->evento->descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

            if ($this->evento->crear()) {
                $_SESSION['mensaje'] = "Evento creado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=evento&action=index");
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al crear evento";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=evento&action=crear");
                exit();
            }
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function editar() {
        if (!isset($_GET['id_evento'])) {
            $_SESSION['mensaje'] = "No se especificó el evento a editar.";
            $_SESSION['tipo_mensaje'] = "danger";
            header("Location: index.php?controller=evento&action=index");
            exit();
        }

        $id = $_GET['id_evento'];
        if (!$this->evento->obtenerPorId($id)) { // Pasamos el id_evento aquí
            $_SESSION['mensaje'] = "El evento no existe o fue eliminado.";
            $_SESSION['tipo_mensaje'] = "danger";
            header("Location: index.php?controller=evento&action=index");
            exit();
        }

        $evento = $this->evento; // Objeto que se pasa a la vista
        include 'views/eventos/editar.php';
    }



    /**
     * Actualizar evento
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->evento->id_evento = $_POST['id_evento'];
            $this->evento->nombre_evento = $_POST['nombre_evento'];
            $this->evento->tipo = $_POST['tipo'];
            $this->evento->monto = $_POST['monto'];
            $this->evento->descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';

            if ($this->evento->actualizar()) {
                $_SESSION['mensaje'] = "Evento actualizado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=evento&action=index");
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al actualizar evento";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=evento&action=editar&id=" . $this->evento->id_evento);
                exit();
            }
        }
    }

    /**
     * Desactivar evento
     */
    public function desactivar() {
        if (isset($_GET['id'])) {
            $this->evento->id_evento = $_GET['id'];
            
            if ($this->evento->desactivar()) {
                $_SESSION['mensaje'] = "Evento desactivado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al desactivar evento";
                $_SESSION['tipo_mensaje'] = "error";
            }
        }
        
        header("Location: index.php?controller=evento&action=index");
        exit();
    }

    /**
     * Mostrar formulario para aplicar evento a empleado
     */
    public function aplicar() {
        // Obtener lista de empleados activos
        $stmt = $this->empleado->listar();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener lista de eventos activos
        $stmtEventos = $this->evento->listar();
        $eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);
        
        // Si viene el ID del empleado por parámetro
        $id_empleado_sel = isset($_GET['empleado']) ? $_GET['empleado'] : null;
        
        require_once 'views/eventos/aplicar.php';
    }

    /**
     * Guardar aplicación de evento a empleado
     */
    public function guardarAplicacion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->evento->id_evento = $_POST['id_evento'];
            $id_empleado = $_POST['id_empleado'];
            $fecha_aplicacion = $_POST['fecha_aplicacion'];
            $monto_aplicado = $_POST['monto_aplicado'];
            $observacion = isset($_POST['observacion']) ? $_POST['observacion'] : '';

            if ($this->evento->aplicarAEmpleado($id_empleado, $fecha_aplicacion, $monto_aplicado, $observacion)) {
                $_SESSION['mensaje'] = "Evento aplicado exitosamente al empleado";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=evento&action=historial&empleado=" . $id_empleado);
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al aplicar evento";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=evento&action=aplicar");
                exit();
            }
        }
    }

    /**
     * Ver historial de eventos de un empleado
     */
    public function historial() {
        if (isset($_GET['empleado'])) {
            $id_empleado = $_GET['empleado'];
            
            // Obtener datos del empleado
            $this->empleado->id_empleado = $id_empleado;
            $this->empleado->obtenerPorId();

            $empleado = $this->empleado; // ahora la vista tiene la variable $empleado
            
            // Establecer rango de fechas (mes actual por defecto)
            $fecha_desde = isset($_GET['desde']) ? $_GET['desde'] : date('Y-m-01');
            $fecha_hasta = isset($_GET['hasta']) ? $_GET['hasta'] : date('Y-m-d');
            
            // Obtener eventos aplicados
            $stmt = $this->evento->obtenerEventosEmpleado($id_empleado, $fecha_desde, $fecha_hasta);
            $eventos_aplicados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            require_once 'views/eventos/historial.php';
        } else {
            header("Location: index.php?controller=empleado&action=index");
            exit();
        }
    }
}
?>
