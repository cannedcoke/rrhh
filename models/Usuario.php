<?php
/**
 * Modelo: Usuario
 * Maneja todas las operaciones relacionadas con usuarios del sistema
 */

class Usuario {
    private $conn;
    private $table_name = "usuario";

    // Propiedades del usuario
    public $id_usuario;
    public $nombre;
    public $apellido;
    public $correo;
    public $contrasena;
    public $tipo_usuario;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear un nuevo usuario
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                SET nombre=:nombre, apellido=:apellido, correo=:correo,
                    contrasena=:contrasena, tipo_usuario=:tipo_usuario";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->correo = htmlspecialchars(strip_tags($this->correo));

        // Vincular parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":contrasena", $this->contrasena);
        $stmt->bindParam(":tipo_usuario", $this->tipo_usuario);

        if ($stmt->execute()) {
            $this->id_usuario = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_usuario = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_usuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->correo = $row['correo'];
            $this->tipo_usuario = $row['tipo_usuario'];
            $this->estado = $row['estado'];
            return true;
        }

        return false;
    }

    /**
     * Obtener usuario por correo
     */
    public function obtenerPorCorreo() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE correo = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->correo);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verificar si existe un usuario con el correo
     */
    public function existeCorreo() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                  WHERE correo = :correo";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }

    /**
     * Obtener empleado asociado al usuario
     */
    public function obtenerEmpleadoAsociado() {
        $query = "SELECT e.* FROM empleado e 
                  WHERE e.id_usuario = :id_usuario 
                  AND e.estado = 1 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

