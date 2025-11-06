<?php
/**
 * Controlador: Empleado
 * Maneja las acciones relacionadas con empleados
 */

require_once 'models/Empleado.php';
require_once 'models/Usuario.php';

class EmpleadoController {
    private $db;
    private $empleado;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->empleado = new Empleado($this->db);
        $this->usuario = new Usuario($this->db);
    }

    /**
     * Mostrar lista de empleados
     */
    public function index() {
        $stmt = $this->empleado->listar();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/empleados/listar.php';
    }

    /**
     * Mostrar formulario para crear empleado
     */
    public function crear() {
        require_once 'views/empleados/crear.php';
    }

    /**
     * Guardar nuevo empleado
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verificar si se proporcionó correo y contraseña para crear usuario
            $crear_usuario = !empty($_POST['correo_usuario']) && !empty($_POST['contrasena_usuario']);
            
            if ($crear_usuario) {
                // Verificar si el correo ya existe
                $this->usuario->correo = $_POST['correo_usuario'];
                if ($this->usuario->existeCorreo()) {
                    $_SESSION['mensaje'] = "El correo electrónico ya está registrado";
                    $_SESSION['tipo_mensaje'] = "error";
                    header("Location: index.php?controller=empleado&action=crear");
                    exit();
                }
                
                // Crear usuario primero
                $this->usuario->nombre = $_POST['nombre'];
                $this->usuario->apellido = $_POST['apellido'];
                $this->usuario->correo = $_POST['correo_usuario'];
                $this->usuario->contrasena = password_hash($_POST['contrasena_usuario'], PASSWORD_DEFAULT);
                $this->usuario->tipo_usuario = 'empleado';
                
                if (!$this->usuario->crear()) {
                    $_SESSION['mensaje'] = "Error al crear usuario";
                    $_SESSION['tipo_mensaje'] = "error";
                    header("Location: index.php?controller=empleado&action=crear");
                    exit();
                }
            }

            // Crear empleado
            $this->empleado->nombre = $_POST['nombre'];
            $this->empleado->apellido = $_POST['apellido'];
            $this->empleado->cedula = $_POST['cedula'];
            $this->empleado->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $this->empleado->direccion = $_POST['direccion'];
            $this->empleado->telefono = $_POST['telefono'];
            $this->empleado->correo = $_POST['correo'];
            $this->empleado->salario_base = $_POST['salario_base'];
            $this->empleado->fecha_ingreso = $_POST['fecha_ingreso'];
            $this->empleado->id_usuario = $crear_usuario ? $this->usuario->id_usuario : null;

            if ($this->empleado->crear()) {
                $mensaje = "Empleado creado exitosamente";
                if ($crear_usuario) {
                    $mensaje .= " y usuario del sistema configurado";
                }
                $_SESSION['mensaje'] = $mensaje;
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=empleado&action=index");
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al crear empleado";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=empleado&action=crear");
                exit();
            }
        }
    }

    /**
     * Mostrar detalle de un empleado
     */
    public function detalle() {
        if (isset($_GET['id'])) {
            $this->empleado->id_empleado = $_GET['id'];
            
            if ($this->empleado->obtenerPorId()) {
                // Obtener contratos del empleado
                $stmt_contratos = $this->empleado->obtenerContratos();
                $contratos = $stmt_contratos->fetchAll(PDO::FETCH_ASSOC);
                
                // Obtener contrato activo
                $contrato_activo = $this->empleado->obtenerContratoActivo();
                
                require_once 'views/empleados/detalle.php';
            } else {
                $_SESSION['mensaje'] = "Empleado no encontrado";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=empleado&action=index");
                exit();
            }
        } else {
            header("Location: index.php?controller=empleado&action=index");
            exit();
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function editar() {
        if (isset($_GET['id'])) {
            $this->empleado->id_empleado = $_GET['id'];
            
            if ($this->empleado->obtenerPorId()) {
                // Obtener contratos del empleado
                $stmt_contratos = $this->empleado->obtenerContratos();
                $contratos = $stmt_contratos->fetchAll(PDO::FETCH_ASSOC);
                
                // Obtener contrato activo
                $contrato_activo = $this->empleado->obtenerContratoActivo();
                
                require_once 'views/empleados/editar.php';
            } else {
                $_SESSION['mensaje'] = "Empleado no encontrado";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=empleado&action=index");
                exit();
            }
        } else {
            header("Location: index.php?controller=empleado&action=index");
            exit();
        }
    }

    /**
     * Actualizar empleado
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->empleado->id_empleado = $_POST['id_empleado'];
            $this->empleado->nombre = $_POST['nombre'];
            $this->empleado->apellido = $_POST['apellido'];
            $this->empleado->cedula = $_POST['cedula'];
            $this->empleado->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $this->empleado->direccion = $_POST['direccion'];
            $this->empleado->telefono = $_POST['telefono'];
            $this->empleado->correo = $_POST['correo'];
            $this->empleado->salario_base = $_POST['salario_base'];
            $this->empleado->fecha_ingreso = $_POST['fecha_ingreso'];

            if ($this->empleado->actualizar()) {
                $_SESSION['mensaje'] = "Empleado actualizado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=empleado&action=detalle&id=" . $this->empleado->id_empleado);
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al actualizar empleado";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=empleado&action=editar&id=" . $this->empleado->id_empleado);
                exit();
            }
        }
    }

    /**
     * Eliminar empleado (baja lógica)
     */
    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->empleado->id_empleado = $_GET['id'];
            
            if ($this->empleado->eliminar()) {
                $_SESSION['mensaje'] = "Empleado eliminado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar empleado";
                $_SESSION['tipo_mensaje'] = "error";
            }
        }
        
        header("Location: index.php?controller=empleado&action=index");
        exit();
    }

    /**
     * Buscar empleados
     */
    public function buscar() {
        if (isset($_GET['q'])) {
            $termino = $_GET['q'];
            $stmt = $this->empleado->buscar($termino);
            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            require_once 'views/empleados/listar.php';
        } else {
            $this->index();
        }
    }

    /**
     * Interfaz de empleado para gestión de asistencias
     */
    public function asistencia() {
        // Verificar que el usuario sea empleado
        if ($_SESSION['usuario_tipo'] != 'empleado') {
            header("Location: index.php");
            exit();
        }

        // Obtener información del empleado asociado al usuario
        $this->usuario->id_usuario = $_SESSION['usuario_id'];
        $empleado = $this->usuario->obtenerEmpleadoAsociado();
        
        if (!$empleado) {
            $_SESSION['mensaje'] = "No se encontró información del empleado";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: index.php?controller=auth&action=logout");
            exit();
        }

        // Obtener asistencias del mes actual
        $fecha_inicio = date('Y-m-01');
        $fecha_fin = date('Y-m-t');
        
        require_once 'models/Asistencia.php';
        $asistencia = new Asistencia($this->db);
        $stmt = $asistencia->listarPorEmpleado($empleado['id_empleado'], $fecha_inicio, $fecha_fin);
        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar asistencia de hoy
        $hoy = date('Y-m-d');
        $asistencia_hoy = null;
        foreach ($asistencias as $asist) {
            if ($asist['fecha'] == $hoy) {
                $asistencia_hoy = $asist;
                break;
            }
        }

        require_once 'views/empleados/asistencia.php';
    }

    /**
     * Registrar entrada
     */
    public function registrarEntrada() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['usuario_tipo'] == 'empleado') {
            $this->usuario->id_usuario = $_SESSION['usuario_id'];
            $empleado = $this->usuario->obtenerEmpleadoAsociado();
            
            if ($empleado) {
                require_once 'models/Asistencia.php';
                $asistencia = new Asistencia($this->db);
                
                $hoy = date('Y-m-d');
                $hora_actual = date('H:i:s');
                
                // Verificar si ya existe asistencia hoy
                if ($asistencia->existeAsistencia($empleado['id_empleado'], $hoy)) {
                    $_SESSION['mensaje'] = "Ya registró su entrada hoy";
                    $_SESSION['tipo_mensaje'] = "warning";
                } else {
                    $asistencia->id_empleado = $empleado['id_empleado'];
                    $asistencia->fecha = $hoy;
                    $asistencia->hora_entrada = $hora_actual;
                    $asistencia->estado = 'presente';
                    
                    if ($asistencia->registrar()) {
                        $_SESSION['mensaje'] = "Entrada registrada exitosamente";
                        $_SESSION['tipo_mensaje'] = "success";
                    } else {
                        $_SESSION['mensaje'] = "Error al registrar entrada";
                        $_SESSION['tipo_mensaje'] = "error";
                    }
                }
            }
        }
        
        header("Location: index.php?controller=empleado&action=asistencia");
        exit();
    }

    /**
     * Registrar salida
     */
    public function registrarSalida() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['usuario_tipo'] == 'empleado') {
            $this->usuario->id_usuario = $_SESSION['usuario_id'];
            $empleado = $this->usuario->obtenerEmpleadoAsociado();
            
            if ($empleado) {
                require_once 'models/Asistencia.php';
                $asistencia = new Asistencia($this->db);
                
                $hoy = date('Y-m-d');
                $hora_actual = date('H:i:s');
                
                // Buscar asistencia de hoy
                $stmt = $asistencia->listarPorEmpleado($empleado['id_empleado'], $hoy, $hoy);
                $asistencia_hoy = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($asistencia_hoy && $asistencia_hoy['hora_entrada'] && !$asistencia_hoy['hora_salida']) {
                    // Calcular horas trabajadas
                    $entrada = new DateTime($asistencia_hoy['fecha'] . ' ' . $asistencia_hoy['hora_entrada']);
                    $salida = new DateTime($hoy . ' ' . $hora_actual);
                    if ($salida < $entrada) { // Turno nocturno que cruza la medianoche
                        $salida->modify('+1 day');
                    }
                    // Calcular la diferencia en segundos y convertir a horas
                    $horas_trabajadas = ($salida->getTimestamp() - $entrada->getTimestamp()) / 3600;
                    
                    $asistencia->id_asistencia = $asistencia_hoy['id_asistencia'];
                    $asistencia->hora_salida = $hora_actual;
                    $asistencia->horas_trabajadas = round($horas_trabajadas, 2);
                    
                    if ($asistencia->actualizar()) {
                        $_SESSION['mensaje'] = "Salida registrada exitosamente";
                        $_SESSION['tipo_mensaje'] = "success";
                    } else {
                        $_SESSION['mensaje'] = "Error al registrar salida";
                        $_SESSION['tipo_mensaje'] = "error";
                    }
                } else {
                    $_SESSION['mensaje'] = "Debe registrar entrada primero";
                    $_SESSION['tipo_mensaje'] = "warning";
                }
            }
        }
        
        header("Location: index.php?controller=empleado&action=asistencia");
        exit();
    }

    /**
     * Generar reporte de asistencias
     */
    public function generarReporte() {
        if ($_SESSION['usuario_tipo'] != 'empleado') {
            header("Location: index.php");
            exit();
        }

        $this->usuario->id_usuario = $_SESSION['usuario_id'];
        $empleado = $this->usuario->obtenerEmpleadoAsociado();
        
        if (!$empleado) {
            header("Location: index.php?controller=auth&action=logout");
            exit();
        }

        $tipo_reporte = $_GET['tipo'] ?? 'semanal';
        $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-t');
        $formato = $_GET['formato'] ?? 'csv';

        require_once 'models/Asistencia.php';
        $asistencia = new Asistencia($this->db);
        $stmt = $asistencia->listarPorEmpleado($empleado['id_empleado'], $fecha_inicio, $fecha_fin);
        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($formato == 'csv') {
            $this->generarCSV($asistencias, $empleado, $fecha_inicio, $fecha_fin);
        } else {
            $this->generarPDF($asistencias, $empleado, $fecha_inicio, $fecha_fin);
        }
    }


    public function obtenerDatos() {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $empleado = $this->empleado->obtenerPorId($id);
        if ($empleado) {
            echo json_encode([
                'success' => true,
                'empleado' => [
                    'nombre' => $empleado->nombre,
                    'apellido' => $empleado->apellido,
                    'salario_base' => $empleado->salario_base
                    // agrega más campos si quieres
                ]
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
    exit;
}


    private function generarCSV($asistencias, $empleado, $fecha_inicio, $fecha_fin) {
        $filename = "reporte_asistencias_" . $empleado['cedula'] . "_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        fputcsv($output, ['Fecha', 'Hora Entrada', 'Hora Salida', 'Horas Trabajadas', 'Estado']);
        
        foreach ($asistencias as $asist) {
            fputcsv($output, [
                $asist['fecha'],
                $asist['hora_entrada'] ?? '',
                $asist['hora_salida'] ?? '',
                $asist['horas_trabajadas'] ?? '0',
                $asist['estado']
            ]);
        }
        
        fclose($output);
        exit();
    }

    private function generarPDF($asistencias, $empleado, $fecha_inicio, $fecha_fin) {
        // Implementación básica de PDF (requiere librería como TCPDF o FPDF)
        // Por ahora, redirigir a CSV
        $this->generarCSV($asistencias, $empleado, $fecha_inicio, $fecha_fin);
    }
}
?>
