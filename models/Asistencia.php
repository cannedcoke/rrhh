<?php
/**
 * Modelo: Asistencia
 * Maneja todas las operaciones relacionadas con el registro de asistencias
 */

class Asistencia {
    private $conn;
    private $table_name = "asistencia";

    // Propiedades
    public $id_asistencia;
    public $id_empleado;
    public $fecha;
    public $hora_entrada;
    public $hora_salida;
    public $horas_trabajadas;
    public $observacion;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registrar asistencia
     */
    public function registrar() {
        $query = "INSERT INTO " . $this->table_name . "
                SET id_empleado=:id_empleado, fecha=:fecha,
                    hora_entrada=:hora_entrada, hora_salida=:hora_salida,
                    horas_trabajadas=:horas_trabajadas, observacion=:observacion,
                    estado=:estado";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->observacion = htmlspecialchars(strip_tags($this->observacion));

        // Vincular parámetros
        $stmt->bindParam(":id_empleado", $this->id_empleado);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":hora_entrada", $this->hora_entrada);
        $stmt->bindParam(":hora_salida", $this->hora_salida);
        $stmt->bindParam(":horas_trabajadas", $this->horas_trabajadas);
        $stmt->bindParam(":observacion", $this->observacion);
        $stmt->bindParam(":estado", $this->estado);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Listar asistencias por fecha
     */
    public function listarPorFecha($fecha) {
        $query = "SELECT a.*, e.nombre, e.apellido, e.cedula
                  FROM " . $this->table_name . " a
                  INNER JOIN empleado e ON a.id_empleado = e.id_empleado
                  WHERE a.fecha = :fecha
                  ORDER BY e.apellido, e.nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Listar asistencias por empleado
     */
    public function listarPorEmpleado($id_empleado, $fecha_inicio = null, $fecha_fin = null) {
        if ($fecha_inicio && $fecha_fin) {
            $query = "SELECT * FROM " . $this->table_name . "
                      WHERE id_empleado = :id_empleado
                      AND fecha BETWEEN :fecha_inicio AND :fecha_fin
                      ORDER BY fecha DESC";
        } else {
            $query = "SELECT * FROM " . $this->table_name . "
                      WHERE id_empleado = :id_empleado
                      ORDER BY fecha DESC";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        
        if ($fecha_inicio && $fecha_fin) {
            $stmt->bindParam(":fecha_inicio", $fecha_inicio);
            $stmt->bindParam(":fecha_fin", $fecha_fin);
        }
        
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtener asistencia por ID
     */
    public function obtenerPorId() {
        $query = "SELECT a.*, e.nombre, e.apellido, e.cedula
                  FROM " . $this->table_name . " a
                  INNER JOIN empleado e ON a.id_empleado = e.id_empleado
                  WHERE a.id_asistencia = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_asistencia);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verificar si existe asistencia en una fecha
     */
    public function existeAsistencia($id_empleado, $fecha) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                  WHERE id_empleado = :id_empleado AND fecha = :fecha";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }

    /**
     * Actualizar asistencia
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                SET hora_entrada=:hora_entrada, hora_salida=:hora_salida,
                    horas_trabajadas=:horas_trabajadas, observacion=:observacion,
                    estado=:estado
                WHERE id_asistencia=:id_asistencia";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->observacion = htmlspecialchars(strip_tags($this->observacion));

        // Vincular parámetros
        $stmt->bindParam(":hora_entrada", $this->hora_entrada);
        $stmt->bindParam(":hora_salida", $this->hora_salida);
        $stmt->bindParam(":horas_trabajadas", $this->horas_trabajadas);
        $stmt->bindParam(":observacion", $this->observacion);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id_asistencia", $this->id_asistencia);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Eliminar asistencia
     */
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . "
                WHERE id_asistencia = :id_asistencia";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_asistencia", $this->id_asistencia);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Calcular horas trabajadas en un período
     */
    public function calcularHorasPeriodo($id_empleado, $fecha_inicio, $fecha_fin) {
        $query = "SELECT SUM(horas_trabajadas) as total_horas
                  FROM " . $this->table_name . "
                  WHERE id_empleado = :id_empleado
                  AND fecha BETWEEN :fecha_inicio AND :fecha_fin
                  AND estado = 'presente'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":fecha_inicio", $fecha_inicio);
        $stmt->bindParam(":fecha_fin", $fecha_fin);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_horas'] ? $row['total_horas'] : 0;
    }

    /**
     * Obtener asistencias del mes actual
     */
    public function listarMesActual() {
        $query = "SELECT a.*, e.nombre, e.apellido, e.cedula
                  FROM " . $this->table_name . " a
                  INNER JOIN empleado e ON a.id_empleado = e.id_empleado
                  WHERE MONTH(a.fecha) = MONTH(CURDATE())
                  AND YEAR(a.fecha) = YEAR(CURDATE())
                  ORDER BY a.fecha DESC, e.apellido";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtener estadísticas de asistencia por empleado
     */
    public function estadisticasPorEmpleado($id_empleado, $mes, $anio) {
        $query = "SELECT 
                    COUNT(*) as total_dias,
                    SUM(CASE WHEN estado = 'presente' THEN 1 ELSE 0 END) as presentes,
                    SUM(CASE WHEN estado = 'ausente' THEN 1 ELSE 0 END) as ausencias,
                    SUM(CASE WHEN estado = 'tardanza' THEN 1 ELSE 0 END) as tardanzas,
                    SUM(CASE WHEN estado = 'permiso' THEN 1 ELSE 0 END) as permisos,
                    SUM(horas_trabajadas) as total_horas
                  FROM " . $this->table_name . "
                  WHERE id_empleado = :id_empleado
                  AND MONTH(fecha) = :mes
                  AND YEAR(fecha) = :anio";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":mes", $mes);
        $stmt->bindParam(":anio", $anio);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
