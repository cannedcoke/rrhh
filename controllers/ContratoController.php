<?php
/**
 * Controlador: Contrato
 * Maneja las acciones relacionadas con contratos laborales
 */

require_once 'models/Contrato.php';
require_once 'models/Empleado.php';

class ContratoController
{
    private $db;
    private $contrato;
    private $empleado;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->contrato = new Contrato($this->db);
        $this->empleado = new Empleado($this->db);
    }

    /** Mostrar lista de contratos */
    public function index()
    {
        $stmt = $this->contrato->listar();
        $contratos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/contratos/listar.php';
    }

    /** Mostrar formulario para crear contrato */
    public function crear()
    {
        $stmt = $this->empleado->listar();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $id_empleado_sel = $_GET['empleado'] ?? null;
        require_once 'views/contratos/crear.php';
    }

    /** Guardar nuevo contrato */
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->contrato->id_empleado = $_POST['id_empleado'];
            $this->contrato->tipo_contrato = strtolower($_POST['tipo_contrato']);
            $this->contrato->fecha_inicio = $_POST['fecha_inicio'];
            $this->contrato->fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
            $this->contrato->observaciones = $_POST['observaciones'] ?? '';

            switch ($this->contrato->tipo_contrato) {
                case 'mensualero':
                    $this->contrato->monto_base = $_POST['monto_base'] ?? 0;
                    $this->contrato->monto_hora = null;
                    break;
                case 'catedratico':
                    $this->contrato->monto_base = 0;
                    $this->contrato->monto_hora = $_POST['monto_hora'] ?? 0;
                    break;
                default:
                    $this->contrato->monto_base = $_POST['monto_base'] ?? 0;
                    $this->contrato->monto_hora = $_POST['monto_hora'] ?? 0;
                    break;
            }

            if (!empty($_FILES['archivo_pdf']['tmp_name'])) {
                $ruta = 'uploads/contratos/';
                if (!is_dir($ruta))
                    mkdir($ruta, 0775, true);
                $nombre = uniqid('contrato_') . '.pdf';
                move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $ruta . $nombre);
                $this->contrato->archivo_pdf = $ruta . $nombre;
            } else {
                $this->contrato->archivo_pdf = null;
            }

            $this->contrato->firmado_por_usuario = 0;
            $this->contrato->validado_por_admin = 0;

            if ($this->contrato->crear()) {
                $idContrato = $this->db->lastInsertId();

                if (!empty($_POST['dias'])) {
                    $sql = "INSERT INTO contrato_horario (id_contrato, dia_semana, hora_inicio, hora_fin)
                            VALUES (:id_contrato, :dia_semana, :hora_inicio, :hora_fin)";
                    $stmt = $this->db->prepare($sql);
                    foreach ($_POST['dias'] as $i => $dia) {
                        $stmt->execute([
                            'id_contrato' => $idContrato,
                            'dia_semana' => $dia,
                            'hora_inicio' => $_POST['hora_inicio'][$i],
                            'hora_fin' => $_POST['hora_fin'][$i]
                        ]);
                    }
                }

                $_SESSION['mensaje'] = "Contrato creado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al crear contrato";
                $_SESSION['tipo_mensaje'] = "error";
            }

            header("Location: index.php?controller=contrato&action=index");
            exit();
        }
    }

    /** Mostrar detalle de un contrato */
    public function detalle()
    {
        if (isset($_GET['id'])) {
            $this->contrato->id_contrato = $_GET['id'];
            $contrato = $this->contrato->obtenerPorId();

            if ($contrato) {
                $contrato['archivo_pdf'] = $contrato['archivo_pdf'] ?? null;
                $contrato['firmado_por_usuario'] = $contrato['firmado_por_usuario'] ?? 0;
                $contrato['validado_por_admin'] = $contrato['validado_por_admin'] ?? 0;
                require_once 'views/contratos/detalle.php';
            } else {
                $_SESSION['mensaje'] = "Contrato no encontrado";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=contrato&action=index");
                exit();
            }
        }
    }

    /** Mostrar formulario de edición */
    public function editar()
    {
        if (isset($_GET['id'])) {
            $this->contrato->id_contrato = $_GET['id'];
            $contrato = $this->contrato->obtenerPorId();

            if ($contrato) {
                $contrato['archivo_pdf'] = $contrato['archivo_pdf'] ?? null;
                $stmt = $this->empleado->listar();
                $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                require_once 'views/contratos/editar.php';
            } else {
                $_SESSION['mensaje'] = "Contrato no encontrado";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=contrato&action=index");
                exit();
            }
        }
    }

    /** Actualizar contrato */
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['id_contrato'])) {
                $_SESSION['mensaje'] = "ID de contrato no recibido.";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=contrato&action=index");
                exit();
            }

            $this->contrato->id_contrato = $_POST['id_contrato'];
            $contratoExistente = $this->contrato->obtenerPorId();

            if (!$contratoExistente) {
                $_SESSION['mensaje'] = "Contrato no encontrado.";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=contrato&action=index");
                exit();
            }

            $this->contrato->id_empleado = $contratoExistente['id_empleado'];
            $this->contrato->tipo_contrato = $_POST['tipo_contrato'];
            $this->contrato->fecha_inicio = $_POST['fecha_inicio'];
            $this->contrato->fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
            $this->contrato->estado = $_POST['estado'];
            $this->contrato->observaciones = $_POST['observaciones'] ?? '';

            switch ($this->contrato->tipo_contrato) {
                case 'mensualero':
                    $this->contrato->monto_base = $_POST['monto_base'] ?? 0;
                    $this->contrato->monto_hora = null;
                    break;
                case 'catedratico':
                    $this->contrato->monto_base = 0;
                    $this->contrato->monto_hora = $_POST['monto_hora'] ?? 0;
                    break;
                default:
                    $this->contrato->monto_base = $_POST['monto_base'] ?? 0;
                    $this->contrato->monto_hora = $_POST['monto_hora'] ?? 0;
                    break;
            }

            $this->contrato->archivo_pdf = $contratoExistente['archivo_pdf'] ?? null;

            if ($this->contrato->actualizar()) {
                $_SESSION['mensaje'] = "Contrato actualizado correctamente.";
                $_SESSION['tipo_mensaje'] = "success";
                header("Location: index.php?controller=contrato&action=detalle&id=" . $this->contrato->id_contrato);
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al actualizar contrato.";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=contrato&action=editar&id=" . $this->contrato->id_contrato);
                exit();
            }
        }
    }

    /** Firmar contrato */
public function firmar()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int) $_POST['id_contrato'];

        // guardar firma digital
        if (!empty($_POST['firma_digital'])) {
            $imgData = $_POST['firma_digital'];
            $imgData = str_replace('data:image/png;base64,', '', $imgData);
            $imgData = str_replace(' ', '+', $imgData);

            $ruta = 'uploads/firmas/';
            if (!is_dir($ruta)) mkdir($ruta, 0775, true);
            $archivo = $ruta . 'firma_' . $id . '_' . time() . '.png';
            file_put_contents($archivo, base64_decode($imgData));

            // actualizar contrato
            $sql = "UPDATE contrato SET firmado_por_usuario = 1, firma_digital = :firma WHERE id_contrato = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['firma' => $archivo, 'id' => $id]);
        }

        // regenerar PDF automáticamente
        header("Location: index.php?controller=contrato&action=generarPDF&id=$id");
        exit();
    }
}



    /** Validar contrato (por RRHH) */
    public function validar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) $_POST['id_contrato'];
            $sql = "UPDATE contrato SET validado_por_admin = 1 WHERE id_contrato = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            $_SESSION['mensaje'] = "Contrato validado exitosamente";
            $_SESSION['tipo_mensaje'] = "success";
            header("Location: index.php?controller=contrato&action=detalle&id=$id");
            exit();
        }
    }

    /** Eliminar contrato */
    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_contrato'])) {
            // Verificar contraseña de administrador
            if (!isset($_POST['password_admin']) || empty($_POST['password_admin'])) {
                $_SESSION['mensaje'] = "Debe ingresar la contraseña de administrador";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=contrato&action=index");
                exit();
            }

            // Obtener contraseña del admin desde la base de datos
            $sql = "SELECT contrasena FROM usuario WHERE tipo_usuario = 'admin' LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$admin) {
                $_SESSION['mensaje'] = "No se encontró usuario administrador";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=contrato&action=index");
                exit();
            }

            // Verificar la contraseña
            if (!password_verify($_POST['password_admin'], $admin['contrasena'])) {
                $_SESSION['mensaje'] = "Contraseña incorrecta. No se eliminó el contrato";
                $_SESSION['tipo_mensaje'] = "error";
                header("Location: index.php?controller=contrato&action=index");
                exit();
            }

            // Si la contraseña es correcta, proceder a eliminar
            $this->contrato->id_contrato = $_POST['id_contrato'];
            if ($this->contrato->eliminar()) {
                $_SESSION['mensaje'] = "Contrato eliminado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar contrato";
                $_SESSION['tipo_mensaje'] = "error";
            }
        }
        header("Location: index.php?controller=contrato&action=index");
        exit();
    }


    /** Generar PDF del contrato */
    public function generarPDF()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['mensaje'] = "Contrato no encontrado";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: index.php?controller=contrato&action=index");
            exit();
        }

        $this->contrato->id_contrato = $_GET['id'];
        $contrato = $this->contrato->obtenerPorId();

        if (!$contrato) {
            $_SESSION['mensaje'] = "Contrato no encontrado";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: index.php?controller=contrato&action=index");
            exit();
        }

        $empleadoModel = new Empleado($this->db);
        $empleadoModel->id_empleado = $contrato['id_empleado'];
        $empleado = $empleadoModel->obtenerPorId();

        $sql = "SELECT dia_semana, hora_inicio, hora_fin FROM contrato_horario WHERE id_contrato = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $_GET['id']]);
        $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clausulaHorarios = "";
        if (!empty($horarios)) {
            $textoHorarios = "";
            foreach ($horarios as $h) {
                $inicio = substr($h['hora_inicio'], 0, 5);
                $fin = substr($h['hora_fin'], 0, 5);
                $textoHorarios .= "- {$h['dia_semana']}: $inicio a $fin\n";
            }
            $clausulaHorarios = "TERCERA - Horarios de Trabajo:\nEl profesional prestará servicios en los siguientes días y horarios:\n$textoHorarios";
        }

        function limpiarTexto($texto)
        {
            $buscar = ['“', '”', '‘', '’', '–', '—', '•', '´', '…', '°'];
            $reempl = ['"', '"', "'", "'", '-', '-', '*', "'", "...", "º"];
            return utf8_decode(str_replace($buscar, $reempl, $texto));
        }

        $fechaES = function ($fecha) {
            if (!$fecha)
                return 'indefinido';
            $meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
            $t = strtotime($fecha);
            return date('d', $t) . ' de ' . $meses[date('n', $t) - 1] . ' de ' . date('Y', $t);
        };

        $nombre = $empleado['nombre'] . ' ' . $empleado['apellido'];
        $cedula = $empleado['cedula'] ?? 'N/D';
        $direccion = $empleado['direccion'] ?? 'no especificada';
        $tipo = strtolower(trim($contrato['tipo_contrato'] ?? 'no especificado'));
        $inicio = $fechaES($contrato['fecha_inicio']);
        $fin = $fechaES($contrato['fecha_fin']);
        $estado = strtoupper($contrato['estado'] ?? 'ACTIVO');
        $firmado = $contrato['firmado_por_usuario'] ? 'Sí' : 'No';
        $validado = $contrato['validado_por_admin'] ? 'Sí' : 'No';
        $montoBase = number_format((float) ($contrato['monto_base'] ?? 0), 0, ',', '.');
        $montoHora = number_format((float) ($contrato['monto_hora'] ?? 0), 0, ',', '.');
        $observ = $contrato['observaciones'] ? "Observaciones: " . $contrato['observaciones'] : '';

        if ($tipo === 'catedratico') {
            $honorarios = "SEGUNDA - Honorarios Profesionales:
Queda convenida entre las partes que el Honorario Profesional será:
- Monto por hora cátedra: Gs $montoHora
El profesional debe emitir factura contado una vez pagado sus honorarios.
Se deja constancia que el Profesional no goza I.P.S.";
        } else {
            $honorarios = "SEGUNDA - Honorarios Profesionales:
Queda convenida entre las partes que el Honorario Profesional será:
- Monto base mensual: Gs $montoBase
El profesional debe emitir factura contado una vez pagado sus honorarios.
Se deja constancia que el Profesional goza de I.P.S.";
        }

        require_once __DIR__ . '/../fpdf.php';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);
        
        // === AGREGAR LOGO EN LA PARTE SUPERIOR ===
        $logoPath = __DIR__ . '/../logo.png';
        if (file_exists($logoPath)) {
            // Centrar el logo horizontalmente
            $pageWidth = $pdf->GetPageWidth();
            $logoWidth = 50; // Ancho del logo en mm
            $xPos = ($pageWidth - $logoWidth) / 2;
            $pdf->Image($logoPath, $xPos, 10, $logoWidth);
            $pdf->Ln(35); // Espacio después del logo
        }
        
        $pdf->SetFont('Arial', '', 12);

        $texto = "
En la Ciudad de Limpio, República del Paraguay, en fecha $inicio, se reúnen, por una parte; la Facultad de Ciencias Tecnológicas-FACUTEC, patrocinada legalmente por la Fundación Oportunidades para la Educación (OPED) con RUC Nro. 80083254-0, y representada en este acto por el Lic. Rodrigo Acevedo, con C.I N° 1.059.481, en su carácter de Decano, paraguayo, mayor de edad, casado, domiciliado en calle Juan Silvano Godoy N°978 entre Colón y Montevideo de la ciudad de Asunción, en adelante denominado 'FACUTEC', y por otra parte; el Sr./Sra. $nombre, con C.I.N° $cedula, mayor de edad, paraguayo, domiciliado en $direccion, en adelante 'EL PROFESIONAL', convienen celebrar el presente contrato de prestación de servicios bajo las siguientes cláusulas y condiciones:

PRIMERA - Modalidad del contrato:
La Facutec contrata al profesional, quien acepta, a fin de que el mismo ejecute sus servicios profesionales como DOCENTE TITULAR en la modalidad '$tipo'. El profesional prestará sus servicios en el local de la Facutec, sede Limpio.

$honorarios
";

        if ($clausulaHorarios !== "")
            $texto .= "\n$clausulaHorarios\n";

        $texto .= "
CUARTA - Plazo del contrato:
El presente contrato tiene vigencia desde $inicio hasta el $fin y será posible renovar si hay acuerdo entre ambas partes.

QUINTA - Servicios:
 * El profesional debe presentar a la Dirección Académica o equivalente el planeamiento de sus actividades académicas, de acuerdo con los criterios fijados por la Facutec.
 * El profesional debe tener especial consideración con los criterios institucionales respecto a la orientación técnico-pedagógica, así como también al marco Institucional, Estatutos, Valores, Principios, Visión y Misión de la Universidad Evangélica del Paraguay.

SEXTA - Cláusulas Especiales:
 * El contrato es de carácter indelegable, no pudiendo hacerse sustituir por otro en su puesto, sin el previo consentimiento del Decano de la Facutec con aviso de 48 hs. de antelación.
 * El profesional deberá proponer un reemplazante de su misma categoría.
 * Queda establecido el deber de confidencialidad y obligación de estricta reserva respecto de los datos e informaciones que el profesional conozca, directa o indirectamente, en virtud de sus funciones.
 * La Facutec no se hace responsable por daños ocasionados a terceros, ni por daños propios sufridos por El Profesional.
 * Ley aplicable: Este contrato se rige por los artículos 715, 845 al 851 del Código Civil Paraguayo.

SÉPTIMA - Terminación del contrato:
 * Por negligencia grave o reiterada del profesional en el desempeño de sus funciones.
 * Por incumplimiento de cualquiera de las cláusulas.
 * Por incompatibilidad manifiesta de las partes.
 * También conforme al art. 851 del Código Civil Paraguayo.
Las partes podrán rescindir este contrato con aviso por escrito con 30 días de anticipación. Si alguna parte omite la comunicación, perderá el derecho a percibir honorarios o deberá indemnizar a la otra parte con el equivalente a un mes de facturación.

$observ
";

        $pdf->MultiCell(0, 7, limpiarTexto($texto));
        $pdf->Ln(20);
        $pdf->Cell(90, 10, utf8_decode('_____________________________'), 0, 0, 'C');
        $pdf->Cell(90, 10, utf8_decode('_____________________________'), 0, 1, 'C');
        $pdf->Cell(90, 8, utf8_decode($nombre), 0, 0, 'C');
        $pdf->Cell(90, 8, utf8_decode('Lic. Rodrigo Acevedo'), 0, 1, 'C');
        $pdf->Cell(90, 8, utf8_decode('Profesional'), 0, 0, 'C');
        $pdf->Cell(90, 8, utf8_decode('Decano - FACUTEC'), 0, 1, 'C');
        // === Insertar firma digital del profesional si existe ===
        if (!empty($contrato['firma_digital']) && file_exists($contrato['firma_digital'])) {
            $pdf->Image(__DIR__ . '/../' . $contrato['firma_digital'], 35, $pdf->GetY() - 40, 50, 25);
 // coordenadas y tamaño
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->MultiCell(0, 6, utf8_decode("Estado del contrato: $estado | Firmado por usuario: $firmado | Validado por RRHH: $validado | Fecha de creación: {$contrato['fecha_creacion']}"));

        // === GUARDAR PDF EN SERVIDOR Y ACTUALIZAR BD ===
        $ruta = 'uploads/contratos/';
        if (!is_dir($ruta))
            mkdir($ruta, 0775, true);
        $nombreSeguro = preg_replace('/[^A-Za-z0-9_-]/', '', $nombre);
        $nombreArchivo = 'Contrato_' . $nombreSeguro . '_' . time() . '.pdf';
        $pdf->Output('F', $ruta . $nombreArchivo);

        $sql = "UPDATE contrato SET archivo_pdf = :archivo WHERE id_contrato = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'archivo' => $ruta . $nombreArchivo,
            'id' => $_GET['id']
        ]);

        if (ob_get_length())
            ob_end_clean();
        $pdf->Output('I', 'Contrato_FACUTEC_' . $nombreSeguro . '.pdf');
    }

}
?>