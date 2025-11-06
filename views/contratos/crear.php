<?php 
$titulo = "Crear Nuevo Contrato";
include 'views/templates/header.php'; 
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        <h4 class="mb-0"><i class="bi bi-file-earmark-plus"></i> Crear Nuevo Contrato</h4>
      </div>
      <div class="card-body">
        <form action="index.php?controller=contrato&action=guardar" method="POST" enctype="multipart/form-data">
          <div class="row">

            <div class="col-md-12 mb-3">
              <label class="form-label">Empleado *</label>
              <select name="id_empleado" class="form-select" required>
                <option value="">Seleccione...</option>
                <?php foreach($empleados as $emp): ?>
                <option value="<?= $emp['id_empleado'] ?>">
                  <?= htmlspecialchars($emp['apellido'] . ', ' . $emp['nombre'] . ' - CI: ' . $emp['cedula']); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Tipo de Contrato *</label>
              <select name="tipo_contrato" id="tipo_contrato" class="form-select" required onchange="toggleCamposContrato(this.value)">
                <option value="">Seleccione...</option>
                <option value="mensualero">Mensualero</option>
                <option value="catedratico">Catedrático</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Fecha de Inicio *</label>
              <input type="date" name="fecha_inicio" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Fecha de Fin</label>
              <input type="date" name="fecha_fin" class="form-control">
              <small class="text-muted">Vacío = indefinido</small>
            </div>

            <div class="col-md-6 mb-3" id="campo-monto-base" style="display:none;">
              <label class="form-label">Salario Base (₲)</label>
              <input type="number" name="monto_base" class="form-control" step="0.01" min="0">
            </div>

            <div class="col-md-6 mb-3" id="campo-monto-hora" style="display:none;">
              <label class="form-label">Monto por Hora (₲)</label>
              <input type="number" name="monto_hora" class="form-control" step="0.01" min="0">
            </div>
              <div class="col-md-12 mb-3">
  <label class="form-label">Días y Horarios de Trabajo</label>
  <div id="horarios-container">
    <div class="row horario-item mb-2">
      <div class="col-md-4">
        <select name="dias[]" class="form-select">
          <option value="Lunes">Lunes</option>
          <option value="Martes">Martes</option>
          <option value="Miércoles">Miércoles</option>
          <option value="Jueves">Jueves</option>
          <option value="Viernes">Viernes</option>
          <option value="Sábado">Sábado</option>
          <option value="Domingo">Domingo</option>
        </select>
      </div>
      <div class="col-md-3">
        <input type="time" name="hora_inicio[]" class="form-control">
      </div>
      <div class="col-md-3">
        <input type="time" name="hora_fin[]" class="form-control">
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-danger" onclick="this.closest('.horario-item').remove()">X</button>
      </div>
    </div>
  </div>
  <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarHorario()">+ Agregar día</button>
</div>

<script>
function agregarHorario() {
  const cont = document.getElementById('horarios-container');
  const item = cont.children[0].cloneNode(true);
  item.querySelectorAll('input').forEach(e => e.value = '');
  cont.appendChild(item);
}
</script>

            <div class="col-md-12 mb-3">
              <label class="form-label">Archivo PDF del Contrato</label>
              <input type="file" name="archivo_pdf" class="form-control" accept=".pdf">
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Observaciones</label>
              <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <a href="index.php?controller=contrato&action=index" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar Contrato</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function toggleCamposContrato(tipo) {
  document.getElementById('campo-monto-base').style.display =
    (tipo === 'mensualero') ? 'block' : 'none';
  document.getElementById('campo-monto-hora').style.display =
    (tipo === 'catedratico') ? 'block' : 'none';
}

</script>

<?php include 'views/templates/footer.php'; ?>
