<?php 
$titulo = "Lista de Contratos";
include 'views/templates/header.php'; 
?>

<div class="card shadow-sm">
  <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="bi bi-list"></i> Contratos Registrados</h5>
    <a href="index.php?controller=contrato&action=crear" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-circle"></i> Generar Contrato
    </a>
  </div>

  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Empleado</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Archivo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contratos as $c): ?>
        <tr>
          <td><?= $c['id_contrato'] ?></td>
          <td><?= htmlspecialchars($c['id_empleado']) ?></td>
          <td><?= ucfirst($c['tipo_contrato']) ?></td>
          <td>
            <?php 
              $badge = 'secondary';
              if ($c['estado'] === 'activo') $badge = 'success';
              elseif ($c['estado'] === 'finalizado') $badge = 'dark';
              elseif ($c['estado'] === 'suspendido') $badge = 'warning';
            ?>
            <span class="badge bg-<?= $badge ?>"><?= ucfirst($c['estado']) ?></span>
          </td>
          <td>
            <?php if (!empty($c['archivo_pdf'])): ?>
              <a href="<?= $c['archivo_pdf'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Ver
              </a>
            <?php else: ?>
              <span class="text-muted">Sin archivo</span>
            <?php endif; ?>
          </td>
          <td>
            <form action="index.php?controller=contrato&action=eliminar" method="POST" style="display:inline;" onsubmit="return confirmarEliminacion(this);">
  <input type="hidden" name="id_contrato" value="<?= $c['id_contrato'] ?>">
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-trash"></i>
  </button>
</form>

            <a href="index.php?controller=contrato&action=detalle&id=<?= $c['id_contrato'] ?>" class="btn btn-info btn-sm" title="Ver Detalle">
              <i class="bi bi-eye"></i>
            </a>
            <a href="index.php?controller=contrato&action=editar&id=<?= $c['id_contrato'] ?>" class="btn btn-warning btn-sm" title="Editar">
              <i class="bi bi-pencil"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function confirmarEliminacion(form) {
  const pass = prompt("Ingrese la contraseña de administrador para eliminar el contrato:");
  if (pass === null) return false; // Usuario canceló
  
  // Crear campo oculto con la contraseña
  const inputPass = document.createElement('input');
  inputPass.type = 'hidden';
  inputPass.name = 'password_admin';
  inputPass.value = pass;
  form.appendChild(inputPass);
  
  return true; // Enviar el formulario
}
</script>

<?php include 'views/templates/footer.php'; ?>
