<?php
/**
 * Modelo: Contrato
 * Tabla: contrato
 */
class Contrato
{
    private $conn;
    private $table_name = "contrato";

    // Campos
    public $id_contrato;
    public $id_empleado;
    public $tipo_contrato;        // mensualero | jornalero | catedratico
    public $fecha_inicio;
    public $fecha_fin;            // NULL si indefinido
    public $monto_base;           // mensual
    public $monto_hora;           // por hora (jornalero o catedrático)
    public $observaciones;
    public $estado;               // activo | inactivo | finalizado
    public $archivo_pdf;          // ruta archivo
    public $firmado_por_usuario;  // TINYINT(1)
    public $firma_digital;        // hash o ruta de imagen
    public $validado_por_admin;   // TINYINT(1)

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function obtenerPorId()
    {
        $sql = "SELECT * FROM contrato WHERE id_contrato = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id_contrato, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listado
    public function listar()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id_contrato DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }



    // Crear
    public function crear()
    {
        $sql = "INSERT INTO {$this->table_name}
                (id_empleado, tipo_contrato, fecha_inicio, fecha_fin, monto_base, monto_hora,
                 observaciones, estado, archivo_pdf, firmado_por_usuario, firma_digital, validado_por_admin)
                VALUES
                (:id_empleado, :tipo_contrato, :fecha_inicio, :fecha_fin, :monto_base, :monto_hora,
                 :observaciones, :estado, :archivo_pdf, :firmado_por_usuario, :firma_digital, :validado_por_admin)";
        $stmt = $this->conn->prepare($sql);

        $params = [
            'id_empleado' => $this->id_empleado,
            'tipo_contrato' => $this->tipo_contrato,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin ?: null,
            'monto_base' => $this->monto_base ?? null,
            'monto_hora' => $this->monto_hora ?? null,
            'observaciones' => $this->observaciones ?? '',
            'estado' => $this->estado ?? 'activo',
            'archivo_pdf' => $this->archivo_pdf ?? null,
            'firmado_por_usuario' => $this->firmado_por_usuario ?? 0,
            'firma_digital' => $this->firma_digital ?? null,
            'validado_por_admin' => $this->validado_por_admin ?? 0
        ];
        return $stmt->execute($params);
    }

    // Actualizar
    public function actualizar()
    {
        $sql = "UPDATE {$this->table_name} SET
                    id_empleado = :id_empleado,
                    tipo_contrato = :tipo_contrato,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    monto_base = :monto_base,
                    monto_hora = :monto_hora,
                    observaciones = :observaciones,
                    estado = :estado,
                    archivo_pdf = :archivo_pdf
                WHERE id_contrato = :id_contrato";
        $stmt = $this->conn->prepare($sql);

        $params = [
            'id_empleado' => $this->id_empleado,
            'tipo_contrato' => $this->tipo_contrato,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin ?: null,
            'monto_base' => $this->monto_base ?? null,
            'monto_hora' => $this->monto_hora ?? null,
            'observaciones' => $this->observaciones ?? '',
            'estado' => $this->estado ?? 'activo',
            'archivo_pdf' => $this->archivo_pdf ?? null,
            'id_contrato' => $this->id_contrato
        ];
        return $stmt->execute($params);
    }

    // Eliminar
    public function eliminar()
    {
        $sql = "DELETE FROM {$this->table_name} WHERE id_contrato = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $this->id_contrato]);
    }

    // Finalizar
    public function finalizar()
    {
        $sql = "UPDATE {$this->table_name}
                SET estado = 'finalizado',
                    fecha_fin = COALESCE(fecha_fin, CURDATE())
                WHERE id_contrato = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $this->id_contrato]);
    }

    // Firmar
    public function firmar($usuarioIdentificador)
    {
        $hash = hash('sha256', $usuarioIdentificador . '|' . time());
        $sql = "UPDATE {$this->table_name}
                SET firmado_por_usuario = 1, firma_digital = :hash
                WHERE id_contrato = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['hash' => $hash, 'id' => $this->id_contrato]);
    }

    // Validar
    public function validar()
    {
        $sql = "UPDATE {$this->table_name}
                SET validado_por_admin = 1
                WHERE id_contrato = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $this->id_contrato]);
    }

    // Obtener archivo PDF existente
    public function obtenerArchivoExistente()
    {
        $sql = "SELECT archivo_pdf FROM {$this->table_name} WHERE id_contrato = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $this->id_contrato]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['archivo_pdf'] : null;
    }

    // ---- Helpers ----
    private function fechaEnEspañol($fecha)
    {
        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre'
        ];
        $mesIngles = date('F', strtotime($fecha));
        return date('d', strtotime($fecha)) . ' de ' . $meses[$mesIngles] . ' de ' . date('Y', strtotime($fecha));
    }

    // Generar texto legal con formato FACUTEC–OPED
    public function generarTextoContrato(array $empleado)
    {
        $ini = $this->fechaEnEspañol($this->fecha_inicio);
        $fin = $this->fecha_fin ? $this->fechaEnEspañol($this->fecha_fin) : 'indefinido';
        $hoy = $this->fechaEnEspañol(date('Y-m-d'));

        $monto = $this->tipo_contrato === 'catedratico' ? $this->monto_hora : $this->monto_base;
        $detalleMonto = $this->tipo_contrato === 'catedratico'
            ? "por hora cátedra efectivamente realizada"
            : "mensuales";

        $texto = "
En la Ciudad de Limpio, República del Paraguay, en fecha {$hoy}, se reúnen, por una parte; 
la Facultad de Ciencias Tecnológicas – FACUTEC, patrocinada legalmente por la Fundación Oportunidades 
para la Educación (OPED) con RUC Nro. 80083254-0, y representada en este acto por el Lic. Rodrigo Acevedo, 
con C.I. N° 1.059.481, en su carácter de Decano, paraguayo, mayor de edad, casado, domiciliado en calle 
Juan Silvano Godoy N°978 entre Colón y Montevideo de la ciudad de Asunción, en adelante denominado 
\"FACUTEC\"; y por otra parte, el Sr. {$empleado['nombre']} {$empleado['apellido']}, con C.I.N° {$empleado['cedula']}, 
mayor de edad, paraguayo, domiciliado en {$empleado['direccion']}, en adelante \"EL PROFESIONAL\"; 
convienen celebrar el presente contrato de prestación de servicios bajo las siguientes cláusulas y condiciones:

PRIMERA – Modalidad del contrato:
La FACUTEC contrata al profesional, quien acepta, a fin de que el mismo ejecute sus servicios profesionales 
como DOCENTE TITULAR de las cátedras que le sean asignadas. El profesional prestará sus servicios en la sede Limpio de la FACUTEC.

SEGUNDA – Honorarios Profesionales:
Queda convenida entre las partes que el Honorario Profesional a percibir es de Gs. " . number_format($monto, 0, ',', '.') . " (guaraníes {$detalleMonto}). 
El profesional debe emitir factura contado una vez pagado sus honorarios. " . ($this->tipo_contrato === 'catedratico'
            ? "Se deja constancia que el Profesional no goza del beneficio de la jubilación del I.P.S."
            : "Se deja constancia que el Profesional goza del beneficio de la jubilación del I.P.S.") . "

TERCERA – Horas de Servicio:
El profesional ejercerá once horas semanales, teniendo en cuenta el horario académico, la disposición y/o distribución horaria que la FACUTEC establezca para las cátedras asignadas.

CUARTA – Plazo del contrato:
El presente contrato tiene vigencia desde {$ini} hasta {$fin} y será posible renovar si hay acuerdo entre ambas partes.

QUINTA – Servicios:
* El profesional debe presentar a la Dirección Académica o equivalente el planeamiento de sus actividades académicas, de acuerdo con los criterios fijados por la FACUTEC.
* El profesional debe tener especial consideración con los criterios institucionales respecto a la orientación técnico-pedagógica, así como también al marco Institucional, Estatutos, Valores, Principios, Visión y Misión de la Universidad Evangélica del Paraguay.

SEXTA – Cláusulas Especiales:
* El contrato es de carácter indelegable, no pudiendo hacerse sustituir por otro en su puesto, sin el previo consentimiento del Decano de la FACUTEC con aviso de 48 horas de antelación.
* El profesional deberá proponer un reemplazante de su misma categoría.
* Queda establecido el deber de confidencialidad y obligación de estricta reserva respecto de los datos e informaciones que el profesional conozca, directa o indirectamente, en virtud de sus funciones. Asimismo, se establecen los Derechos de Propiedad de la FACUTEC sobre los productos o servicios generados.
* La FACUTEC no se hace responsable por daños ocasionados a terceros en accidentes protagonizados por El Profesional, ni por daños propios sufridos por éste.
* El profesional no podrá tener participación directa o indirecta en operaciones comerciales, industriales o de servicio que se relacionen con las funciones objeto de este contrato.
* Ley aplicable: Este contrato se rige por los artículos 715, 845 al 851 del Código Civil Paraguayo.
* Domicilio legal: Las partes constituyen domicilio especial en los lugares especificados al inicio del instrumento, donde serán válidas todas las notificaciones judiciales o extrajudiciales.
* En caso de nulidad de alguna cláusula, la misma afectará solo a esa parte sin invalidar el resto del contrato.

SÉPTIMA – Terminación del contrato:
* Por negligencia grave o reiterada del profesional en el desempeño de sus funciones.
* Por incumplimiento de cualquiera de las cláusulas.
* Por incompatibilidad manifiesta de las partes.
* También conforme al art. 851 del Código Civil Paraguayo.
Las partes podrán rescindir este contrato con aviso por escrito con 30 días de anticipación. Si alguna parte omite la comunicación, perderá el derecho a percibir honorarios o deberá indemnizar a la otra parte con el equivalente a un mes de facturación.

En prueba de conformidad y aceptación, las partes suscriben el presente contrato en dos ejemplares de un mismo tenor y a un solo efecto en el lugar y fecha indicados.

FIRMAS:

{$empleado['nombre']} {$empleado['apellido']}                         Lic. Rodrigo Acevedo
Profesional                                                        Decano – FACUTEC
";
        return $texto;
    }

    // Contar contratos activos
    public function contarActivos()
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table_name} WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int) $row['total'] : 0;
    }
}
?>