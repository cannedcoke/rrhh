<?php 
$titulo = "Crear Nuevo Empleado";
include 'views/templates/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-person-plus"></i> Crear Nuevo Empleado</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=empleado&action=guardar" method="POST">
                    <div class="row">
                        <!-- Información Personal -->
                        <div class="col-md-12 mb-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-person"></i> Información Personal</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido *</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula *</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento *</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-telephone"></i> Información de Contacto</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                        </div>

                        <!-- Información Laboral -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-briefcase"></i> Información Laboral</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="salario_base" class="form-label">Salario Base (₲) *</label>
                            <input type="number" class="form-control" id="salario_base" name="salario_base" 
                                   step="0.01" min="0" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso *</label>
                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <!-- Información de Usuario del Sistema -->
                        <div class="col-md-12 mb-3 mt-3">
                            <h5 class="border-bottom pb-2"><i class="bi bi-person-gear"></i> Acceso al Sistema (Opcional)</h5>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Si proporciona correo y contraseña, el empleado podrá acceder al sistema para gestionar sus asistencias.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="correo_usuario" class="form-label">Correo para Acceso al Sistema</label>
                            <input type="email" class="form-control" id="correo_usuario" name="correo_usuario" 
                                   placeholder="empleado@empresa.com">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contrasena_usuario" class="form-label">Contraseña para Acceso</label>
                            <input type="password" class="form-control" id="contrasena_usuario" name="contrasena_usuario" 
                                   placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?controller=empleado&action=index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/templates/footer.php'; ?>
