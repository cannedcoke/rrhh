<?php
// Vista para generar un recibo de liquidación imprimible

if (!$liquidacion) {
    die("Error: Datos de liquidación no encontrados.");
}

// Formatear números a moneda Guaraní
function format_gs($value) {
    return number_format($value, 0, ',', '.');
}

$fecha_liquidacion_dt = new DateTime($liquidacion['fecha_liquidacion']);
$periodo_desde_dt = new DateTime($liquidacion['periodo_desde']);
$periodo_hasta_dt = new DateTime($liquidacion['periodo_hasta']);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Salario - <?php echo htmlspecialchars($liquidacion['apellido'] . ', ' . $liquidacion['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .recibo-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #dee2e6;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .recibo-header {
            background-color: #f1f1f1;
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .recibo-body {
            padding: 30px;
        }
        .recibo-footer {
            padding: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #6c757d;
        }
        .firma-linea {
            border-top: 1px solid #000;
            width: 250px;
            margin: 80px auto 10px auto;
            padding-top: 5px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                background-color: #fff;
            }
            .recibo-container {
                box-shadow: none;
                border: none;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container no-print text-center my-3">
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Imprimir Recibo</button>
        <a href="javascript:history.back()" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>

    <div class="recibo-container">
        <div class="recibo-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h4>SISTEMA RRHH S.A.</h4>
                    <p class="mb-0">RUC: 80012345-6</p>
                </div>
                <div class="col-4 text-end">
                    <h5>RECIBO DE SALARIO</h5>
                    <p class="mb-0">N° Liquidación: <?php echo $liquidacion['id_liquidacion']; ?></p>
                </div>
            </div>
        </div>

        <div class="recibo-body">
            <h6>Datos del Empleado</h6>
            <table class="table table-sm table-bordered mb-4">
                <tr>
                    <th style="width: 20%;">Nombre y Apellido</th>
                    <td><?php echo htmlspecialchars($liquidacion['nombre'] . ' ' . $liquidacion['apellido']); ?></td>
                </tr>
                <tr>
                    <th>C.I. N°</th>
                    <td><?php echo htmlspecialchars($liquidacion['cedula']); ?></td>
                </tr>
                <tr>
                    <th>Período</th>
                    <td><?php echo $periodo_desde_dt->format('d/m/Y') . ' al ' . $periodo_hasta_dt->format('d/m/Y'); ?></td>
                </tr>
            </table>

            <h6>Detalle de Liquidación</h6>
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Concepto</th>
                        <th class="text-end">Haberes (₲)</th>
                        <th class="text-end">Descuentos (₲)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Salario Básico</td>
                        <td class="text-end"><?php echo format_gs($liquidacion['total_bruto']); ?></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td>Aporte I.P.S. (9%)</td>
                        <td class="text-end"></td>
                        <td class="text-end"><?php echo format_gs($liquidacion['total_descuentos']); ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td>Totales</td>
                        <td class="text-end">₲ <?php echo format_gs($liquidacion['total_bruto']); ?></td>
                        <td class="text-end">₲ <?php echo format_gs($liquidacion['total_descuentos']); ?></td>
                    </tr>
                    <tr class="fw-bold table-success">
                        <td colspan="2">NETO A COBRAR</td>
                        <td class="text-end">₲ <?php echo format_gs($liquidacion['neto_cobrar']); ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center firma-linea">
                Firma del Empleado
            </div>
        </div>
        <div class="recibo-footer">
            Recibo generado el <?php echo $fecha_liquidacion_dt->format('d/m/Y H:i:s'); ?> por el Sistema RRHH.
        </div>
    </div>
</body>
</html>