<?php
$titulo = "Detalle del Contrato";
include 'views/templates/header.php';
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5><i class="bi bi-file-earmark-text"></i> Contrato #<?= $contrato['id_contrato'] ?></h5>
    </div>
    <div class="card-body">
        <p><strong>Empleado:</strong> <?= htmlspecialchars($contrato['id_empleado']) ?></p>
        <p><strong>Tipo:</strong> <?= ucfirst($contrato['tipo_contrato']) ?></p>
        <p><strong>Estado:</strong> <?= ucfirst($contrato['estado']) ?></p>

        <?php if (!empty($contrato['archivo_pdf'])): ?>
            <iframe src="<?= $contrato['archivo_pdf'] ?>" width="100%" height="500px"></iframe>
        <?php else: ?>
            <p class="text-muted">No se ha cargado un contrato PDF.</p>
        <?php endif; ?>

        <hr>

        <?php if ($contrato['firmado_por_usuario'] == 0): ?>
            <form id="formFirma" method="POST" action="index.php?controller=contrato&action=firmar">
                <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">
                <canvas id="canvasFirma" width="400" height="150" style="border:1px solid #ccc;"></canvas>
                <input type="hidden" name="firma_digital" id="firmaInput">
                <button type="button" class="btn btn-secondary mt-2" onclick="borrarFirma()">Borrar</button>
                <button type="submit" class="btn btn-success mt-2" onclick="guardarFirma()">Firmar contrato</button>
            </form>
            <script>
                const canvas = document.getElementById('canvasFirma');
                const ctx = canvas.getContext('2d');
                let dibujando = false;
                canvas.addEventListener('mousedown', e => { dibujando = true; ctx.beginPath(); ctx.moveTo(e.offsetX, e.offsetY); });
                canvas.addEventListener('mousemove', e => { if (dibujando) { ctx.lineTo(e.offsetX, e.offsetY); ctx.stroke(); } });
                canvas.addEventListener('mouseup', () => dibujando = false);
                function borrarFirma() { ctx.clearRect(0, 0, canvas.width, canvas.height); }
                function guardarFirma() { document.getElementById('firmaInput').value = canvas.toDataURL(); }
            </script>
        <?php else: ?>
            <p class="text-success"><i class="bi bi-check-circle"></i> Contrato firmado</p>
        <?php endif; ?>




        <?php if ($contrato['validado_por_admin'] == 0): ?>
            <form method="POST" action="index.php?controller=contrato&action=validar" class="mt-2">
                <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">
                <button class="btn btn-primary">Validar contrato</button>
            </form>
        <?php else: ?>
            <p class="text-info"><i class="bi bi-shield-check"></i> Contrato validado por RRHH</p>
        <?php endif; ?>
        <a href="index.php?controller=contrato&action=generarPDF&id=<?= $contrato['id_contrato'] ?>"
            class="btn btn-outline-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Generar contrato PDF
        </a>

        <a href="index.php?controller=contrato&action=index" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>
</div>

<?php include 'views/templates/footer.php'; ?>