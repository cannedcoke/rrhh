    </div> <!-- Cierre del container-fluid principal -->

    <!-- Footer acorde al header -->
    <footer class="bg-dark text-light mt-5">
        <div class="container py-4">
            <div class="row">
                <!-- Información del sistema -->
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white p-2 rounded me-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white">Sistema RRHH</h5>
                            <small class="text-info">Gestión de Talento Humano</small>
                        </div>
                    </div>
                    <p class="text-light-emphasis mb-3">
                        Sistema especializado en la gestión integral de recursos humanos, 
                        generación de contratos y liquidación de salarios para empresas.
                    </p>
                </div>

                <!-- Enlaces rápidos -->
                <div class="col-md-3 mb-4">
                    <h6 class="text-primary mb-3">Módulos</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="index.php?controller=empleado&action=index" 
                               class="text-light-emphasis text-decoration-none hover-white">
                                <i class="bi bi-person-badge me-1"></i>Empleados
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="index.php?controller=contrato&action=index" 
                               class="text-light-emphasis text-decoration-none hover-white">
                                <i class="bi bi-file-text me-1"></i>Contratos
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="index.php?controller=asistencia&action=index" 
                               class="text-light-emphasis text-decoration-none hover-white">
                                <i class="bi bi-calendar-check me-1"></i>Asistencias
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="index.php?controller=liquidacion&action=index" 
                               class="text-light-emphasis text-decoration-none hover-white">
                                <i class="bi bi-cash-stack me-1"></i>Liquidaciones
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contacto -->
                <div class="col-md-3 mb-4">
                    <h6 class="text-primary mb-3">Contacto</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-envelope text-info me-2 mt-1"></i>
                            <span class="text-light-emphasis small">soporte@rrhh.com</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-telephone text-info me-2 mt-1"></i>
                            <span class="text-light-emphasis small">+595 21 123 456</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-geo-alt text-info me-2 mt-1"></i>
                            <span class="text-light-emphasis small">Limpio, Paraguay</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Línea separadora -->
            <hr class="border-secondary my-4">

            <!-- Copyright y información académica -->
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="text-light-emphasis small mb-0">
                        &copy; 2025 <strong class="text-info">Sistema RRHH</strong>. 
                        Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-light-emphasis small mb-0">
                        <i class="bi bi-mortarboard text-info me-1"></i>
                        Universidad Evangélica del Paraguay - Facultad de Ciencias Tecnológicas
                    </p>
                </div>
            </div>

            <!-- Desarrolladores -->
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="text-light-emphasis small mb-0">
                        Desarrollado por: 
                        <span class="text-info">Miqueas Zarate</span>, 
                        <span class="text-info">Rodney Farifa</span>, 
                        <span class="text-info">Cinthia Gonzalez</span>, 
                        <span class="text-info">Abel Molinas</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Estilo para hover -->
    <style>
        .hover-white:hover {
            color: #fff !important;
            transition: color 0.2s ease;
        }
    </style>
</body>
</html>