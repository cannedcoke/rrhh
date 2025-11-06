<?php
/**
 * Modelo: Evento
 * Maneja eventos que afectan el salario (bonificaciones y descuentos)
 */

class Evento {
    private $conn;
    private $table_name = "evento";

    // Propiedades
    public $id_evento;
    public $nombre_evento;
    public $tipo;
    public $monto;
    public $descripcion;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear un nuevo evento
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                SET nombre_evento=:nombre_evento, tipo=:tipo,
                    monto=:monto, descripcion=:descripcion";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre_evento = htmlspecialchars(strip_tags($this->nombre_evento));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        // Vincular parámetros
        $stmt->bindParam(":nombre_evento", $this->nombre_evento);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":monto", $this->monto);
        $stmt->bindParam(":descripcion", $this->descripcion);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Listar todos los eventos activos
     */
    public function listar() {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE activo = 1
                  ORDER BY tipo, nombre_evento";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Listar eventos por tipo
     */
    public function listarPorTipo($tipo) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE tipo = :tipo AND activo = 1
                  ORDER BY nombre_evento";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tipo", $tipo);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtener evento por ID
     */
    public function obtenerPorId($id_evento) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_evento = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_evento); // Usamos el valor que pasamos por parámetro
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_evento = $row['id_evento'];
            $this->nombre_evento = $row['nombre_evento'];
            $this->tipo = $row['tipo'];
            $this->monto = $row['monto'];
            $this->descripcion = $row['descripcion'];
            $this->activo = $row['activo'];
            return true;
        }
        return false;
    }


    /**
     * Actualizar evento
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre_evento=:nombre_evento, tipo=:tipo,
                    monto=:monto, descripcion=:descripcion
                WHERE id_evento=:id_evento";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre_evento = htmlspecialchars(strip_tags($this->nombre_evento));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        // Vincular parámetros
        $stmt->bindParam(":nombre_evento", $this->nombre_evento);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":monto", $this->monto);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":id_evento", $this->id_evento);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Desactivar evento
     */
    public function desactivar() {
        $query = "UPDATE " . $this->table_name . "
                SET activo = 0
                WHERE id_evento = :id_evento";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_evento", $this->id_evento);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Aplicar evento a un empleado
     */
    public function aplicarAEmpleado($id_empleado, $fecha_aplicacion, $monto_aplicado, $observacion = '') {
        $query = "INSERT INTO empleado_evento
                SET id_empleado=:id_empleado, id_evento=:id_evento,
                    fecha_aplicacion=:fecha_aplicacion, monto_aplicado=:monto_aplicado,
                    observacion=:observacion";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":id_evento", $this->id_evento);
        $stmt->bindParam(":fecha_aplicacion", $fecha_aplicacion);
        $stmt->bindParam(":monto_aplicado", $monto_aplicado);
        $stmt->bindParam(":observacion", $observacion);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Obtener eventos aplicados a un empleado en un período
     */
    public function obtenerEventosEmpleado($id_empleado, $fecha_desde, $fecha_hasta) {
        $query = "SELECT ee.*, e.nombre_evento, e.tipo
                  FROM empleado_evento ee
                  INNER JOIN evento e ON ee.id_evento = e.id_evento
                  WHERE ee.id_empleado = :id_empleado
                  AND ee.fecha_aplicacion BETWEEN :fecha_desde AND :fecha_hasta
                  ORDER BY ee.fecha_aplicacion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":fecha_desde", $fecha_desde);
        $stmt->bindParam(":fecha_hasta", $fecha_hasta);
        $stmt->execute();

        return $stmt;
    }
}
?>
