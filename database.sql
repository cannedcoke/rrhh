-- Base de datos para Sistema de Gestión de RRHH
-- Proyecto: Generación de contratos y liquidación de salarios

CREATE DATABASE IF NOT EXISTS rrhh_salarios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rrhh_salarios;

-- Tabla: Usuario
CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin', 'empleado', 'rrhh') DEFAULT 'empleado',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: Empleado
CREATE TABLE IF NOT EXISTS empleado (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    correo VARCHAR(150),
    salario_base DECIMAL(10,2) DEFAULT 0.00,
    fecha_ingreso DATE NOT NULL,
    id_usuario INT,
    estado TINYINT(1) DEFAULT 1,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: Contrato
CREATE TABLE IF NOT EXISTS contrato (
    id_contrato INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    tipo_contrato ENUM('mensualero', 'catedratico') NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    monto_base DECIMAL(10,2) NOT NULL,
    monto_hora DECIMAL(10,2),
    estado ENUM('activo', 'finalizado', 'suspendido') DEFAULT 'activo',
    observaciones TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: Asistencia
CREATE TABLE IF NOT EXISTS asistencia (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    fecha DATE NOT NULL,
    hora_entrada TIME,
    hora_salida TIME,
    horas_trabajadas DECIMAL(5,2) DEFAULT 0.00,
    observacion TEXT,
    estado ENUM('presente', 'ausente', 'tardanza', 'permiso') DEFAULT 'presente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado) ON DELETE CASCADE,
    UNIQUE KEY unique_asistencia (id_empleado, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: Evento
CREATE TABLE IF NOT EXISTS evento (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_evento VARCHAR(100) NOT NULL,
    tipo ENUM('+', '-') NOT NULL COMMENT '+ para bonificación, - para descuento',
    monto DECIMAL(10,2) NOT NULL,
    descripcion TEXT,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla intermedia: Empleado_Evento
CREATE TABLE IF NOT EXISTS empleado_evento (
    id_empleado_evento INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    id_evento INT NOT NULL,
    fecha_aplicacion DATE NOT NULL,
    monto_aplicado DECIMAL(10,2) NOT NULL,
    observacion TEXT,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado) ON DELETE CASCADE,
    FOREIGN KEY (id_evento) REFERENCES evento(id_evento) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: Movimiento (Tabla central de cálculo)
CREATE TABLE IF NOT EXISTS movimiento (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    id_contrato INT NOT NULL,
    periodo_desde DATE NOT NULL,
    periodo_hasta DATE NOT NULL,
    total_horas DECIMAL(10,2) DEFAULT 0.00,
    total_eventos INT DEFAULT 0,
    total_bonificaciones DECIMAL(10,2) DEFAULT 0.00,
    total_descuentos DECIMAL(10,2) DEFAULT 0.00,
    total_calculado DECIMAL(10,2) DEFAULT 0.00,
    estado ENUM('en_calculo', 'confirmado', 'liquidado') DEFAULT 'en_calculo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado) ON DELETE CASCADE,
    FOREIGN KEY (id_contrato) REFERENCES contrato(id_contrato) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: Liquidacion
CREATE TABLE IF NOT EXISTS liquidacion (
    id_liquidacion INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    id_contrato INT NOT NULL,
    id_movimiento INT,
    fecha_liquidacion DATE NOT NULL,
    periodo_desde DATE NOT NULL,
    periodo_hasta DATE NOT NULL,
    total_bruto DECIMAL(10,2) NOT NULL,
    total_descuentos DECIMAL(10,2) DEFAULT 0.00,
    neto_cobrar DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'pagado', 'anulado') DEFAULT 'pendiente',
    observaciones TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado) ON DELETE CASCADE,
    FOREIGN KEY (id_contrato) REFERENCES contrato(id_contrato) ON DELETE CASCADE,
    FOREIGN KEY (id_movimiento) REFERENCES movimiento(id_movimiento) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: Informe de Cobro
CREATE TABLE IF NOT EXISTS informe_cobro (
    id_informe INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    periodo_desde DATE NOT NULL,
    periodo_hasta DATE NOT NULL,
    total_percibido DECIMAL(10,2) NOT NULL,
    fecha_generacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    generado_por INT,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado) ON DELETE CASCADE,
    FOREIGN KEY (generado_por) REFERENCES usuario(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos de ejemplo

-- Usuarios de prueba (contraseña: 123456)
INSERT INTO usuario (nombre, apellido, correo, contrasena, tipo_usuario) VALUES
('Admin', 'Sistema', 'admin@rrhh.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('RRHH', 'Manager', 'rrhh@rrhh.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rrhh');

-- Eventos comunes
INSERT INTO evento (nombre_evento, tipo, monto, descripcion) VALUES
('Bono por desempeño', '+', 500000.00, 'Bonificación por buen desempeño laboral'),
('Horas extras', '+', 15000.00, 'Pago por hora extra trabajada'),
('Anticipo', '-', 0.00, 'Anticipo de salario'),
('Ausencia injustificada', '-', 100000.00, 'Descuento por ausencia sin justificar'),
('IPS (9%)', '-', 0.00, 'Aporte al IPS - 9% del salario'),
('Llegada tardía', '-', 50000.00, 'Descuento por tardanza');

-- Índices para optimizar consultas
CREATE INDEX idx_empleado_cedula ON empleado(cedula);
CREATE INDEX idx_asistencia_fecha ON asistencia(fecha);
CREATE INDEX idx_asistencia_empleado ON asistencia(id_empleado);
CREATE INDEX idx_contrato_empleado ON contrato(id_empleado);
CREATE INDEX idx_liquidacion_empleado ON liquidacion(id_empleado);
CREATE INDEX idx_movimiento_periodo ON movimiento(periodo_desde, periodo_hasta);
