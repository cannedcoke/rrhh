# 🚀 Guía de Instalación - Sistema RRHH

## 📋 Requisitos Previos

### Software Necesario
- **XAMPP 8.0+** (recomendado) o WAMP Server
- **PHP 7.4+** con extensiones PDO y pdo_mysql
- **MySQL 5.7+** o MariaDB 10.3+
- **Navegador web** (Chrome, Firefox, Edge)

### Verificar Requisitos
1. Abra XAMPP Control Panel
2. Inicie **Apache** y **MySQL**
3. Verifique que ambos servicios estén en color verde

## 🔧 Instalación Paso a Paso

### 1. Preparar el Proyecto
```bash
# El proyecto ya debe estar en:
C:\xampp\htdocs\rrhh_salarios\
```

### 2. Configurar la Base de Datos

#### Opción A: Usando phpMyAdmin (Recomendado)
1. Abra su navegador y vaya a: `http://localhost/phpmyadmin`
2. Cree una nueva base de datos llamada `rrhh_salarios`
3. Seleccione la base de datos creada
4. Vaya a la pestaña "Importar"
5. Seleccione el archivo `database.sql` del proyecto
6. Haga clic en "Continuar"

#### Opción B: Usando línea de comandos
```bash
# Abra Command Prompt como administrador
cd C:\xampp\mysql\bin
mysql -u root -p
CREATE DATABASE rrhh_salarios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rrhh_salarios;
SOURCE C:\xampp\htdocs\rrhh_salarios\database.sql;
```

### 3. Verificar la Instalación

#### Prueba de Conexión
1. Abra su navegador y vaya a: `http://localhost/rrhh_salarios/test_connection.php`
2. Verifique que todas las verificaciones muestren ✅

#### Acceso al Sistema
1. Vaya a: `http://localhost/rrhh_salarios`
2. Debería redirigir automáticamente al login
3. Use las credenciales de prueba:
   - **Admin:** admin@rrhh.com / 123456
   - **RRHH:** rrhh@rrhh.com / 123456

## 🛠️ Configuración Avanzada

### Configurar Base de Datos Personalizada
Si necesita cambiar la configuración de la base de datos, edite el archivo `config/database.php`:

```php
private $host = "localhost";        // Servidor de BD
private $db_name = "rrhh_salarios"; // Nombre de la BD
private $username = "root";          // Usuario de BD
private $password = "";              // Contraseña de BD
```

### Configurar Puerto Personalizado
Si XAMPP usa un puerto diferente al 80, acceda usando:
```
http://localhost:8080/rrhh_salarios
```

## 🔍 Solución de Problemas

### Error: "No se puede conectar a la base de datos"
**Solución:**
1. Verifique que MySQL esté ejecutándose en XAMPP
2. Confirme las credenciales en `config/database.php`
3. Asegúrese de que la base de datos existe

### Error: "Página en blanco"
**Solución:**
1. Active la visualización de errores en `php.ini`:
   ```ini
   display_errors = On
   error_reporting = E_ALL
   ```
2. Revise los logs de Apache en `C:\xampp\apache\logs\error.log`

### Error: "Tabla no existe"
**Solución:**
1. Ejecute el script `database.sql` completo
2. Verifique que todas las tablas se crearon correctamente

### Error: "Permisos denegados"
**Solución:**
1. Asegúrese de que el servidor web tenga permisos de lectura
2. En Windows, ejecute XAMPP como administrador

## 📊 Verificación del Sistema

### Checklist de Instalación
- [ ] XAMPP ejecutándose (Apache + MySQL)
- [ ] Base de datos `rrhh_salarios` creada
- [ ] Tablas importadas correctamente
- [ ] Usuarios de prueba configurados
- [ ] Acceso al sistema funcionando
- [ ] Login exitoso con credenciales de prueba

### Pruebas Recomendadas
1. **Login:** Probar ambos usuarios de prueba
2. **Empleados:** Crear un empleado de prueba
3. **Contratos:** Crear un contrato para el empleado
4. **Asistencias:** Registrar una asistencia
5. **Eventos:** Crear un evento de prueba
6. **Liquidaciones:** Calcular una liquidación

## 🎯 Funcionalidades Disponibles

### Módulos del Sistema
- ✅ **Gestión de Empleados:** CRUD completo
- ✅ **Contratos Laborales:** Mensualeros, jornaleros, catedráticos
- ✅ **Control de Asistencias:** Registro con calendario
- ✅ **Eventos:** Bonificaciones y descuentos
- ✅ **Liquidaciones:** Cálculo automático de salarios
- ✅ **Autenticación:** Sistema de login seguro

### Características Técnicas
- ✅ **Arquitectura MVC:** Separación clara de responsabilidades
- ✅ **Seguridad:** Contraseñas hasheadas, protección SQL injection
- ✅ **Responsive:** Diseño adaptable a móviles
- ✅ **Bootstrap 5.3:** Interfaz moderna y profesional

## 📞 Soporte

### Información del Proyecto
- **Desarrollado por:** Miqueas Zarate, Rodney Fariña, Cinthia González, Abel Molinas
- **Profesor:** Ruben Delgado
- **Año:** 2025
- **Ciudad:** Limpio

### Recursos Adicionales
- **README.md:** Documentación completa del proyecto
- **test_connection.php:** Script de verificación
- **database.sql:** Estructura de la base de datos

---

**¡Sistema listo para usar! 🎉**

Si encuentra algún problema, revise esta guía o contacte al equipo de desarrollo.

