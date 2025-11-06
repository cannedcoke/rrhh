<?php 
$titulo = "Editar Contrato";
include 'views/templates/header.php'; 
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h4><i class="bi bi-pencil-square"></i> Editar Contrato</h4>
      </div>
      <div class="card-body">
        <form action="index.php?controller=contrato&action=actualizar" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">

          <div class="col-md-12 mb-3">
            <label class="form-label">Empleado</label>
            <select class="form-select" disabled>
              <?php foreach($empleados as $emp): ?>
              <option value="<?= $emp['id_empleado'] ?>" <?= $emp['id_empleado']==$contrato['id_empleado']?'selected':'' ?>>
                <?= htmlspecialchars($emp['apellido'] . ', ' . $emp['nombre']); ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Tipo de Contrato</label>
            <select name="tipo_contrato" class="form-select" onchange="toggleCamposContrato(this.value)">
              <option value="mensualero" <?= $contrato['tipo_contrato']=='mensualero'?'selected':'' ?>>Mensualero</option>
              <option value="jornalero" <?= $contrato['tipo_contrato']=='jornalero'?'selected':'' ?>>Jornalero</option>
              <option value="catedratico" <?= $contrato['tipo_contrato']=='catedratico'?'selected':'' ?>>Catedrático</option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= $contrato['fecha_inicio'] ?>">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Fecha de Fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= $contrato['fecha_fin'] ?>">
          </div>

          <div class="col-md-6 mb-3" id="campo-monto-base" style="display:none;">
            <label class="form-label">Salario Base</label>
            <input type="number" name="monto_base" class="form-control" value="<?= $contrato['monto_base'] ?>">
          </div>

          <div class="col-md-6 mb-3" id="campo-monto-hora" style="display:none;">
            <label class="form-label">Monto por Hora</label>
            <input type="number" name="monto_hora" class="form-control" value="<?= $contrato['monto_hora'] ?>">
          </div>

          <div class="col-md-12 mb-3">
            <label class="form-label">Reemplazar PDF</label>
            <input type="file" name="archivo_pdf" class="form-control" accept=".pdf">
            <?php if ($contrato['archivo_pdf']): ?>
              <small>Actual: <a href="<?= $contrato['archivo_pdf'] ?>" target="_blank">ver</a></small>
            <?php endif; ?>
          </div>

          <div class="col-md-12 mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3"><?= $contrato['observaciones'] ?></textarea>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
              <option value="activo" <?= $contrato['estado']=='activo'?'selected':'' ?>>Activo</option>
              <option value="inactivo" <?= $contrato['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
              <option value="finalizado" <?= $contrato['estado']=='finalizado'?'selected':'' ?>>Finalizado</option>
            </select>
          </div>

          <div class="d-flex justify-content-between">
            <a href="index.php?controller=contrato&action=index" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', ()=>{
  toggleCamposContrato('<?= $contrato['tipo_contrato'] ?>');
});
function toggleCamposContrato(tipo) {
  document.getElementById('campo-monto-base').style.display =
    (tipo==='mensualero'||tipo==='catedratico')?'block':'none';
  document.getElementById('campo-monto-hora').style.display =
    (tipo==='jornalero')?'block':'none';
}
</script>

<?php include 'views/templates/footer.php'; ?>
