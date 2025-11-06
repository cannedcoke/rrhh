# Sistema de Gestión de Recursos Humanos

Sistema completo para la gestión de empleados, contratos, asistencias y liquidaciones de salarios.

## 👥 Integrantes del Proyecto
- Miqueas Zarate
- Rodney Fariña
- Cinthia González
- Abel Molinas

**Docente:** Prof. Ruben Delgado  
**Año:** 2025  
**Ciudad:** Limpio

## 📋 Descripción

Sistema web desarrollado en PHP (Frontend) y preparado para integración con Java (Backend) para la gestión completa del área de Recursos Humanos, incluyendo:

- ✅ Gestión de empleados
- ✅ Generación de contratos laborales
- ✅ Registro de asistencias con calendario
- ✅ Cálculo automático de liquidaciones
- ✅ Gestión de eventos (bonificaciones y descuentos)
- ✅ Generación de informes en PDF y CSV

## 🛠️ Tecnologías

- **Frontend:** PHP, HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5.3
- **Base de Datos:** MySQL/MariaDB
- **Iconos:** Bootstrap Icons
- **Arquitectura:** MVC (Modelo-Vista-Controlador)

## 📦 Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o MariaDB 10.3 o superior
- Servidor web (Apache, Nginx)
- Extensiones PHP: PDO, pdo_mysql

### Servidor Local Recomendado
- XAMPP 8.0+
- WAMP Server
- Laragon

## 🚀 Instalación

### 1. Clonar o descargar el proyecto

Coloca la carpeta `rrhh_salarios` en la carpeta de tu servidor web:
- **XAMPP:** `C:\xampp\htdocs\rrhh_salarios`
- **WAMP:** `C:\wamp64\www\rrhh_salarios`

### 2. Crear la base de datos

1. Abre phpMyAdmin (`http://localhost/phpmyadmin`)
2. Crea una nueva base de datos llamada `rrhh_salarios`
3. Importa el archivo `database.sql` que se encuentra en la raíz del proyecto
4. O ejecuta el contenido del archivo `database.sql` en la pestaña SQL

### 3. Configurar la conexión

Edita el archivo `config/database.php` si es necesario:

```php
private $host = "localhost";
private $db_name = "rrhh_salarios";
private $username = "root";
private $password = "";
```

### 4. Acceder al sistema

Abre tu navegador y ve a:
```
http://localhost/rrhh_salarios
```

## 👤 Usuarios de Prueba

El sistema viene con usuarios pre-configurados:

**Administrador:**
- Usuario: `admin@rrhh.com`
- Contraseña: `123456`

**RRHH:**
- Usuario: `rrhh@rrhh.com`
- Contraseña: `123456`

## 📁 Estructura del Proyecto

```
rrhh_salarios/
│
├── index.php              # Enrutador principal
├── database.sql           # Script de base de datos
├── README.md             # Este archivo
│
├── config/
│   └── database.php      # Configuración de BD
│
├── controllers/          # Controladores MVC
│   ├── HomeController.php
│   ├── EmpleadoController.php
│   ├── ContratoController.php
│   ├── AsistenciaController.php
│   ├── LiquidacionController.php
│   └── EventoController.php
│
├── models/               # Modelos de datos
│   ├── Empleado.php
│   ├── Contrato.php
│   ├── Asistencia.php
│   ├── Liquidacion.php
│   └── Evento.php
│
├── views/                # Vistas de la aplicación
│   ├── home/
│   ├── empleados/
│   ├── contratos/
│   ├── asistencias/
│   ├── liquidaciones/
│   └── templates/
│       ├── header.php
│       └── footer.php
│
└── assets/               # Recursos estáticos
    ├── css/
    │   └── style.css
    └── js/
        └── main.js
```

## 🎯 Funcionalidades Principales

### 1. Gestión de Empleados
- Registro completo de información personal y laboral
- Búsqueda y filtrado de empleados
- Actualización y eliminación (baja lógica)
- Vista detallada con historial

### 2. Contratos Laborales
- Creación de contratos para:
  - Mensualeros
  - Jornaleros
  - Catedráticos
- Control de vigencia y estados
- Vinculación automática con empleados

### 3. Registro de Asistencias
- Registro de entrada y salida
- Cálculo automático de horas trabajadas
- Estados: Presente, Ausente, Tardanza, Permiso
- Vista de calendario mensual

### 4. Liquidaciones de Salario
- Cálculo automático según tipo de contrato
- Aplicación de bonificaciones y descuentos
- Descuento automático de IPS (9%)
- Generación de recibos de pago

### 5. Eventos
- Gestión de bonificaciones (+)
- Gestión de descuentos (-)
- Aplicación a empleados específicos
- Historial de eventos aplicados

## 📊 Base de Datos

El sistema utiliza las siguientes tablas principales:

- `usuario` - Usuarios del sistema
- `empleado` - Información de empleados
- `contrato` - Contratos laborales
- `asistencia` - Registro de asistencias
- `evento` - Eventos (bonificaciones/descuentos)
- `empleado_evento` - Relación empleado-evento
- `movimiento` - Cálculos intermedios
- `liquidacion` - Liquidaciones de salario
- `informe_cobro` - Informes generados

## 🔒 Seguridad

- Contraseñas hasheadas con bcrypt
- Validación de datos en servidor
- Protección contra SQL Injection (PDO)
- Sanitización de entradas HTML

## 📝 Próximas Mejoras

- [ ] Sistema de autenticación completo
- [ ] Generación de PDFs (liquidaciones, contratos)
- [ ] Exportación de informes a CSV
- [ ] Envío de notificaciones por correo
- [ ] Dashboard con gráficos estadísticos
- [ ] Módulo de permisos y roles
- [ ] API REST para integración con Java backend

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
- Verifica que MySQL esté ejecutándose
- Confirma las credenciales en `config/database.php`
- Asegúrate de que la base de datos existe

### Página en blanco
- Activa la visualización de errores en `php.ini`:
  ```ini
  display_errors = On
  error_reporting = E_ALL
  ```
- Revisa los logs de Apache/PHP

### Errores de permisos
- Asegúrate de que el servidor web tenga permisos de lectura en todos los archivos

## 📞 Soporte

Para reportar problemas o sugerencias, contacta al equipo de desarrollo.

## 📄 Licencia

Proyecto educativo - 2025

---

**Desarrollado con ❤️ por el equipo de desarrollo**
