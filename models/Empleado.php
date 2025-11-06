<?php
/**
 * Modelo: Empleado
 * Maneja todas las operaciones relacionadas con empleados
 */

class Empleado {
    private $conn;
    private $table_name = "empleado";

    // Propiedades del empleado
    public $id_empleado;
    public $nombre;
    public $apellido;
    public $cedula;
    public $fecha_nacimiento;
    public $direccion;
    public $telefono;
    public $correo;
    public $salario_base;
    public $fecha_ingreso;
    public $id_usuario;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear un nuevo empleado
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                SET nombre=:nombre, apellido=:apellido, cedula=:cedula,
                    fecha_nacimiento=:fecha_nacimiento, direccion=:direccion,
                    telefono=:telefono, correo=:correo, salario_base=:salario_base,
                    fecha_ingreso=:fecha_ingreso, id_usuario=:id_usuario";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->cedula = htmlspecialchars(strip_tags($this->cedula));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":cedula", $this->cedula);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":salario_base", $this->salario_base);
        $stmt->bindParam(":fecha_ingreso", $this->fecha_ingreso);
        $stmt->bindParam(":id_usuario", $this->id_usuario);

        return $stmt->execute();
    }

    /**
     * Listar todos los empleados activos
     */
    public function listar() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE estado = 1 
                  ORDER BY apellido, nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Obtener un empleado por ID
     */
    public function obtenerPorId() {
        $sql = "SELECT * FROM empleado WHERE id_empleado = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id_empleado]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener empleado por cédula
     */
    public function obtenerPorCedula() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE cedula = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->cedula);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualizar empleado
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre=:nombre, apellido=:apellido, cedula=:cedula,
                    fecha_nacimiento=:fecha_nacimiento, direccion=:direccion,
                    telefono=:telefono, correo=:correo, salario_base=:salario_base,
                    fecha_ingreso=:fecha_ingreso
                WHERE id_empleado=:id_empleado";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->cedula = htmlspecialchars(strip_tags($this->cedula));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":cedula", $this->cedula);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":salario_base", $this->salario_base);
        $stmt->bindParam(":fecha_ingreso", $this->fecha_ingreso);
        $stmt->bindParam(":id_empleado", $this->id_empleado);

        return $stmt->execute();
    }

    /**
     * Eliminar empleado (baja lógica)
     */
    public function eliminar() {
        $query = "UPDATE " . $this->table_name . "
                SET estado = 0
                WHERE id_empleado = :id_empleado";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_empleado", $this->id_empleado);
        return $stmt->execute();
    }

    /**
     * Buscar empleados por nombre o cédula
     */
    public function buscar($termino) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE estado = 1 
                  AND (nombre LIKE ? OR apellido LIKE ? OR cedula LIKE ?)
                  ORDER BY apellido, nombre";

        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->bindParam(1, $termino);
        $stmt->bindParam(2, $termino);
        $stmt->bindParam(3, $termino);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Contar empleados activos
     */
    public function contarActivos() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE estado = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Obtener contratos de un empleado
     */
    public function obtenerContratos() {
        $query = "SELECT *
                  FROM contrato
                  WHERE id_empleado = :id_empleado
                  ORDER BY fecha_inicio DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_empleado', $this->id_empleado);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Obtener contrato activo de un empleado
     */
    public function obtenerContratoActivo() {
        $query = "SELECT *
                  FROM contrato
                  WHERE id_empleado = :id_empleado
                  AND estado = 'activo'
                  AND (fecha_fin IS NULL OR fecha_fin >= CURDATE())
                  ORDER BY fecha_inicio DESC
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_empleado', $this->id_empleado);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
public function obtenerPorIdArray() {
    $sql = "SELECT * FROM empleado WHERE id_empleado = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['id' => $this->id_empleado]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


}
?>
