<?php
/**
 * Modelo: Liquidacion
 * Maneja el cálculo y generación de liquidaciones de salario
 */

class Liquidacion {
    private $conn;
    private $table_name = "liquidacion";

    // Propiedades
    public $id_liquidacion;
    public $id_empleado;
    public $id_contrato;
    public $id_movimiento;
    public $fecha_liquidacion;
    public $periodo_desde;
    public $periodo_hasta;
    public $total_bruto;
    public $total_descuentos;
    public $neto_cobrar;
    public $estado;
    public $observaciones;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear liquidación
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                SET id_empleado=:id_empleado, id_contrato=:id_contrato,
                    id_movimiento=:id_movimiento, fecha_liquidacion=:fecha_liquidacion,
                    periodo_desde=:periodo_desde, periodo_hasta=:periodo_hasta,
                    total_bruto=:total_bruto, total_descuentos=:total_descuentos,
                    neto_cobrar=:neto_cobrar, estado=:estado,
                    observaciones=:observaciones";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->observaciones = htmlspecialchars(strip_tags($this->observaciones));

        // Vincular parámetros
        $stmt->bindParam(":id_empleado", $this->id_empleado);
        $stmt->bindParam(":id_contrato", $this->id_contrato);
        $stmt->bindParam(":id_movimiento", $this->id_movimiento);
        $stmt->bindParam(":fecha_liquidacion", $this->fecha_liquidacion);
        $stmt->bindParam(":periodo_desde", $this->periodo_desde);
        $stmt->bindParam(":periodo_hasta", $this->periodo_hasta);
        $stmt->bindParam(":total_bruto", $this->total_bruto);
        $stmt->bindParam(":total_descuentos", $this->total_descuentos);
        $stmt->bindParam(":neto_cobrar", $this->neto_cobrar);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":observaciones", $this->observaciones);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Listar todas las liquidaciones
     */
    public function listar() {
        $query = "SELECT l.*, e.nombre, e.apellido, e.cedula, c.tipo_contrato
                  FROM " . $this->table_name . " l
                  INNER JOIN empleado e ON l.id_empleado = e.id_empleado
                  INNER JOIN contrato c ON l.id_contrato = c.id_contrato
                  ORDER BY l.fecha_liquidacion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Listar liquidaciones por empleado
     */
    public function listarPorEmpleado($id_empleado) {
        $query = "SELECT l.*, c.tipo_contrato
                  FROM " . $this->table_name . " l
                  INNER JOIN contrato c ON l.id_contrato = c.id_contrato
                  WHERE l.id_empleado = :id_empleado
                  ORDER BY l.fecha_liquidacion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtener liquidación por ID
     */
    public function obtenerPorId() {
        $query = "SELECT l.*, e.nombre, e.apellido, e.cedula, 
                         c.tipo_contrato, c.monto_base, c.monto_hora
                  FROM " . $this->table_name . " l
                  INNER JOIN empleado e ON l.id_empleado = e.id_empleado
                  INNER JOIN contrato c ON l.id_contrato = c.id_contrato
                  WHERE l.id_liquidacion = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_liquidacion);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualizar estado de liquidación
     */
    public function actualizarEstado() {
        $query = "UPDATE " . $this->table_name . "
                SET estado = :estado
                WHERE id_liquidacion = :id_liquidacion";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id_liquidacion", $this->id_liquidacion);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Calcular liquidación para empleado mensualero
     */
    public function calcularMensualero($id_empleado, $id_contrato, $periodo_desde, $periodo_hasta) {
        // Obtener datos del contrato
        $queryContrato = "SELECT * FROM contrato WHERE id_contrato = ?";
        $stmtContrato = $this->conn->prepare($queryContrato);
        $stmtContrato->bindParam(1, $id_contrato);
        $stmtContrato->execute();
        $contrato = $stmtContrato->fetch(PDO::FETCH_ASSOC);

        if (!$contrato) {
            return false;
        }

        // Salario base mensual
        $salario_base = $contrato['monto_base'];

        // Obtener eventos (bonificaciones y descuentos)
        $queryEventos = "SELECT ee.*, e.tipo, e.nombre_evento
                        FROM empleado_evento ee
                        INNER JOIN evento e ON ee.id_evento = e.id_evento
                        WHERE ee.id_empleado = ?
                        AND ee.fecha_aplicacion BETWEEN ? AND ?";
        
        $stmtEventos = $this->conn->prepare($queryEventos);
        $stmtEventos->bindParam(1, $id_empleado);
        $stmtEventos->bindParam(2, $periodo_desde);
        $stmtEventos->bindParam(3, $periodo_hasta);
        $stmtEventos->execute();

        $bonificaciones = 0;
        $descuentos = 0;

        while ($evento = $stmtEventos->fetch(PDO::FETCH_ASSOC)) {
            if ($evento['tipo'] == '+') {
                $bonificaciones += $evento['monto_aplicado'];
            } else {
                $descuentos += $evento['monto_aplicado'];
            }
        }

        // Calcular IPS (9% del salario base)
        $ips = $salario_base * 0.09;
        $descuentos += $ips;

        // Totales
        $total_bruto = $salario_base + $bonificaciones;
        $neto_cobrar = $total_bruto - $descuentos;

        return [
            'salario_base' => $salario_base,
            'bonificaciones' => $bonificaciones,
            'descuentos' => $descuentos,
            'total_bruto' => $total_bruto,
            'neto_cobrar' => $neto_cobrar,
            'ips' => $ips
        ];
    }

    /**
     * Calcular liquidación para empleado jornalero
     */
    public function calcularJornalero($id_empleado, $id_contrato, $periodo_desde, $periodo_hasta) {
        // Obtener datos del contrato
        $queryContrato = "SELECT * FROM contrato WHERE id_contrato = ?";
        $stmtContrato = $this->conn->prepare($queryContrato);
        $stmtContrato->bindParam(1, $id_contrato);
        $stmtContrato->execute();
        $contrato = $stmtContrato->fetch(PDO::FETCH_ASSOC);

        if (!$contrato) {
            return false;
        }

        $monto_hora = $contrato['monto_hora'];

        // Obtener horas trabajadas del período
        $queryHoras = "SELECT SUM(horas_trabajadas) as total_horas
                      FROM asistencia
                      WHERE id_empleado = ?
                      AND fecha BETWEEN ? AND ?
                      AND estado = 'presente'";
        
        $stmtHoras = $this->conn->prepare($queryHoras);
        $stmtHoras->bindParam(1, $id_empleado);
        $stmtHoras->bindParam(2, $periodo_desde);
        $stmtHoras->bindParam(3, $periodo_hasta);
        $stmtHoras->execute();
        $result = $stmtHoras->fetch(PDO::FETCH_ASSOC);
        
        $horas_trabajadas = $result['total_horas'] ? $result['total_horas'] : 0;
        $salario_por_horas = $horas_trabajadas * $monto_hora;

        // Obtener eventos (bonificaciones y descuentos)
        $queryEventos = "SELECT ee.*, e.tipo, e.nombre_evento
                        FROM empleado_evento ee
                        INNER JOIN evento e ON ee.id_evento = e.id_evento
                        WHERE ee.id_empleado = ?
                        AND ee.fecha_aplicacion BETWEEN ? AND ?";
        
        $stmtEventos = $this->conn->prepare($queryEventos);
        $stmtEventos->bindParam(1, $id_empleado);
        $stmtEventos->bindParam(2, $periodo_desde);
        $stmtEventos->bindParam(3, $periodo_hasta);
        $stmtEventos->execute();

        $bonificaciones = 0;
        $descuentos = 0;

        while ($evento = $stmtEventos->fetch(PDO::FETCH_ASSOC)) {
            if ($evento['tipo'] == '+') {
                $bonificaciones += $evento['monto_aplicado'];
            } else {
                $descuentos += $evento['monto_aplicado'];
            }
        }

        // Totales
        $total_bruto = $salario_por_horas + $bonificaciones;
        $neto_cobrar = $total_bruto - $descuentos;

        return [
            'horas_trabajadas' => $horas_trabajadas,
            'monto_hora' => $monto_hora,
            'salario_por_horas' => $salario_por_horas,
            'bonificaciones' => $bonificaciones,
            'descuentos' => $descuentos,
            'total_bruto' => $total_bruto,
            'neto_cobrar' => $neto_cobrar
        ];
    }

    /**
     * Obtener total pagado en un período
     */
    public function totalPagadoPeriodo($fecha_desde, $fecha_hasta) {
        $query = "SELECT SUM(neto_cobrar) as total
                  FROM " . $this->table_name . "
                  WHERE fecha_liquidacion BETWEEN ? AND ?
                  AND estado = 'pagado'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $fecha_desde);
        $stmt->bindParam(2, $fecha_hasta);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ? $row['total'] : 0;
    }

    /**
     * Contar liquidaciones pendientes
     */
    public function contarPendientes() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                  WHERE estado = 'pendiente'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];
    }
}
?>
