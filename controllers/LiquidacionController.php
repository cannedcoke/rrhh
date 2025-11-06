<?php
/**
 * Controlador: Liquidacion
 * Maneja las acciones relacionadas con liquidaciones de salarios
 */

require_once 'models/Liquidacion.php';
require_once 'models/Empleado.php';
require_once 'models/Contrato.php';
require_once 'models/Asistencia.php';

class LiquidacionController {
    private $db;
    private $liquidacion;
    private $empleado;
    private $contrato;
    private $asistencia;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->liquidacion = new Liquidacion($this->db);
        $this->empleado = new Empleado($this->db);
        $this->contrato = new Contrato($this->db);
        $this->asistencia = new Asistencia($this->db);
    }

    /**
     * Mostrar lista de liquidaciones
     */
    public function index() {
        $stmt = $this->liquidacion->listar();
        $liquidaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/liquidaciones/listar.php';
    }

    /**
     * Mostrar formulario para calcular liquidación
     */
    public function calcular() {
        // Obtener lista de empleados activos con contrato vigente
        $stmt = $this->empleado->listar();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Si viene el ID del empleado por parámetro
        $id_empleado_sel = isset($_GET['empleado']) ? $_GET['empleado'] : null;
        
        // Establecer período por defecto (mes anterior)
        $periodo_desde = date('Y-m-01', strtotime('first day of last month'));
        $periodo_hasta = date('Y-m-t', strtotime('last day of last month'));
        
        require_once 'views/liquidaciones/calcular.php';
    }

    /**
     * Procesar cálculo de liquidación
     */
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_empleado = $_POST['id_empleado'];
            $periodo_desde = $_POST['periodo_desde'];
            $periodo_hasta = $_POST['periodo_hasta'];
            
            // Obtener contrato activo del empleado
            $contrato_activo = $this->contrato->obtenerContratoActivo($id_empleado);
            
            if (!$contrato_activo) {
                $_SESSION['mensaje'] = "El empleado no tiene un contrato activo";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=liquidacion&action=calcular");
                exit();
            }
            
            $id_contrato = $contrato_activo['id_contrato'];
            $tipo_contrato = $contrato_activo['tipo_contrato'];
            
            // Calcular según tipo de contrato
            if ($tipo_contrato == 'mensualero' || $tipo_contrato == 'catedratico') {
                $resultado = $this->liquidacion->calcularMensualero($id_empleado, $id_contrato, $periodo_desde, $periodo_hasta);
            } else if ($tipo_contrato == 'jornalero') {
                $resultado = $this->liquidacion->calcularJornalero($id_empleado, $id_contrato, $periodo_desde, $periodo_hasta);
            }
            
            if ($resultado) {
                // Mostrar vista de confirmación con los datos calculados
                $this->empleado->id_empleado = $id_empleado;
                $this->empleado->obtenerPorId();
                
                require_once 'views/liquidaciones/confirmar.php';
            } else {
                $_SESSION['mensaje'] = "Error al calcular la liquidación";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=liquidacion&action=calcular");
                exit();
            }
        }
    }

    /**
     * Guardar liquidación confirmada
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->liquidacion->id_empleado = $_POST['id_empleado'];
            $this->liquidacion->id_contrato = $_POST['id_contrato'];
            $this->liquidacion->id_movimiento = null; // Por ahora
            $this->liquidacion->fecha_liquidacion = date('Y-m-d');
            $this->liquidacion->periodo_desde = $_POST['periodo_desde'];
            $this->liquidacion->periodo_hasta = $_POST['periodo_hasta'];
            $this->liquidacion->total_bruto = $_POST['total_bruto'];
            $this->liquidacion->total_descuentos = $_POST['total_descuentos'];
            $this->liquidacion->neto_cobrar = $_POST['neto_cobrar'];
            $this->liquidacion->estado = 'pendiente';
            $this->liquidacion->observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : '';

            $id = $this->liquidacion->crear();
            
            if ($id) {
                $_SESSION['mensaje'] = "Liquidación generada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=liquidacion&action=detalle&id=" . $id);
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al guardar liquidación";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=liquidacion&action=calcular");
                exit();
            }
        }
    }

    /**
     * Mostrar detalle de una liquidación
     */
    public function detalle() {
        if (isset($_GET['id'])) {
            $this->liquidacion->id_liquidacion = $_GET['id'];
            $liquidacion = $this->liquidacion->obtenerPorId();
            
            if ($liquidacion) {
                require_once 'views/liquidaciones/detalle.php';
            } else {
                $_SESSION['mensaje'] = "Liquidación no encontrada";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=liquidacion&action=index");
                exit();
            }
        } else {
            header("Location: index.php?controller=liquidacion&action=index");
            exit();
        }
    }

    /**
     * Cambiar estado de liquidación
     */
    public function cambiarEstado() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $this->liquidacion->id_liquidacion = $_GET['id'];
            $this->liquidacion->estado = $_GET['estado'];
            
            if ($this->liquidacion->actualizarEstado()) {
                $_SESSION['mensaje'] = "Estado actualizado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar estado";
                $_SESSION['tipo_mensaje'] = "error";
            }
            
            header("Location: index.php?controller=liquidacion&action=detalle&id=" . $this->liquidacion->id_liquidacion);
            exit();
        }
    }

    /**
     * Ver liquidaciones por empleado
     */
    public function porEmpleado() {
        if (isset($_GET['id'])) {
            $id_empleado = $_GET['id'];
            
            // Obtener datos del empleado
            $this->empleado->id_empleado = $id_empleado;
            $this->empleado->obtenerPorId();
            
            // Obtener liquidaciones
            $stmt = $this->liquidacion->listarPorEmpleado($id_empleado);
            $liquidaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            require_once 'views/liquidaciones/por_empleado.php';
        } else {
            header("Location: index.php?controller=liquidacion&action=index");
            exit();
        }
    }

    /**
     * Generar recibo de pago (PDF)
     */
    public function recibo() {
        if (isset($_GET['id'])) {
            $this->liquidacion->id_liquidacion = $_GET['id'];
            $liquidacion = $this->liquidacion->obtenerPorId();
            
            if ($liquidacion) {
                require_once 'views/liquidaciones/recibo.php';
            } else {
                $_SESSION['mensaje'] = "Liquidación no encontrada";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=liquidacion&action=index");
                exit();
            }
        }
    }

    /**
     * Generar informe mensual de liquidaciones
     */
    public function informe() {
        $mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
        $anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
        
        $fecha_desde = "$anio-$mes-01";
        $fecha_hasta = date("Y-m-t", strtotime($fecha_desde));
        
        $total_pagado = $this->liquidacion->totalPagadoPeriodo($fecha_desde, $fecha_hasta);
        
        require_once 'views/liquidaciones/informe.php';
    }
}
?>
