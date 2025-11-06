# Funcionalidades para Empleados - Sistema RRHH

## 🎯 Nuevas Funcionalidades Implementadas

### 1. **Registro Automático de Usuarios**
- Al registrar un empleado, se puede crear automáticamente un usuario del sistema
- El empleado puede acceder con su correo y contraseña
- Rol automático: "empleado"

### 2. **Sistema de Roles**
- **Administradores**: Acceso completo al sistema
- **Empleados**: Solo acceso a su interfaz de asistencias
- Redirección automática según el tipo de usuario

### 3. **Interfaz de Empleado**
- Panel de control de asistencia con hora actual
- Botones de entrada y salida con timestamp automático
- Historial de asistencias del mes actual
- Resumen estadístico (días presentes, ausencias, tardanzas, total de horas)

### 4. **Control de Asistencias**
- **Registro de Entrada**: Toma la hora actual automáticamente
- **Registro de Salida**: Calcula automáticamente las horas trabajadas
- **Validaciones**: No permite registrar salida sin entrada previa
- **Estados**: Presente, Ausente, Tardanza, Permiso

### 5. **Cálculo Automático de Horas**
- Cálculo automático entre hora de entrada y salida
- Precisión en decimales (ej: 8.5 horas)
- No permite manipulación de fechas por parte del empleado

### 6. **Generación de Reportes**
- **Formatos**: CSV y PDF
- **Períodos**: Semanal, Mensual, Personalizado
- **Contenido**: Fecha, hora entrada, hora salida, horas trabajadas, estado
- **Descarga**: Automática con nombre descriptivo

## 🔧 Archivos Modificados/Creados

### Nuevos Archivos:
- `models/Usuario.php` - Modelo para gestión de usuarios
- `views/empleados/asistencia.php` - Interfaz de empleado
- `FUNCIONALIDADES_EMPLEADOS.md` - Esta documentación

### Archivos Modificados:
- `controllers/EmpleadoController.php` - Lógica de registro y gestión de empleados
- `controllers/AuthController.php` - Redirección por roles
- `views/empleados/crear.php` - Formulario con campos de usuario
- `views/templates/header.php` - Menú dinámico según rol

## 🚀 Cómo Usar

### Para Administradores:
1. **Registrar Empleado con Usuario**:
   - Ir a "Empleados" → "Crear Nuevo"
   - Completar datos del empleado
   - En la sección "Acceso al Sistema", proporcionar correo y contraseña
   - El sistema creará automáticamente el usuario con rol "empleado"

### Para Empleados:
1. **Acceso al Sistema**:
   - Usar el correo y contraseña proporcionados por el administrador
   - Será redirigido automáticamente a su interfaz de asistencias

2. **Registrar Asistencia**:
   - **Entrada**: Hacer clic en "Registrar Entrada" (toma la hora actual)
   - **Salida**: Hacer clic en "Registrar Salida" (calcula horas automáticamente)

3. **Ver Historial**:
   - Visualizar todas las asistencias del mes actual
   - Ver resumen estadístico
   - Generar reportes en CSV o PDF

## 🔒 Seguridad Implementada

- **Validación de Roles**: Los empleados solo pueden acceder a su interfaz
- **Timestamp Automático**: No se puede manipular la hora de entrada/salida
- **Validaciones**: No permite registrar salida sin entrada previa
- **Contraseñas Hasheadas**: Almacenamiento seguro con bcrypt

## 📊 Características Técnicas

### Base de Datos:
- Tabla `usuario` con roles diferenciados
- Relación entre `empleado` y `usuario` mediante `id_usuario`
- Cálculo automático de horas trabajadas

### Interfaz:
- **Responsive**: Compatible con dispositivos móviles
- **Tiempo Real**: Hora actual actualizada cada segundo
- **Bootstrap 5**: Diseño moderno y profesional
- **JavaScript**: Interactividad para reportes y validaciones

### Reportes:
- **CSV**: Formato compatible con Excel
- **UTF-8**: Soporte para caracteres especiales
- **Nombres Descriptivos**: Incluyen cédula y fecha

## 🎨 Interfaz de Usuario

### Panel de Control:
- **Hora Actual**: Reloj en tiempo real
- **Estado del Día**: Muestra si ya registró entrada/salida
- **Botones Dinámicos**: Cambian según el estado actual

### Historial:
- **Tabla Responsiva**: Fácil visualización en cualquier dispositivo
- **Colores de Estado**: Verde (presente), Rojo (ausente), Amarillo (tardanza)
- **Resumen Visual**: Tarjetas con estadísticas del mes

### Reportes:
- **Modal Interactivo**: Selección de período y formato
- **Fechas Personalizadas**: Para reportes específicos
- **Descarga Inmediata**: Sin necesidad de guardar archivos

## 🔄 Flujo de Trabajo

1. **Administrador registra empleado** → Crea usuario automáticamente
2. **Empleado inicia sesión** → Redirigido a su interfaz
3. **Empleado registra entrada** → Timestamp automático
4. **Empleado registra salida** → Cálculo automático de horas
5. **Empleado genera reportes** → Descarga en formato deseado

## ✅ Beneficios

- **Automatización**: Elimina errores manuales en el registro
- **Transparencia**: Los empleados pueden ver su historial
- **Eficiencia**: Cálculo automático de horas trabajadas
- **Flexibilidad**: Múltiples formatos de reporte
- **Seguridad**: No se puede manipular la hora de registro
- **Usabilidad**: Interfaz intuitiva y fácil de usar

---

**Sistema implementado exitosamente** ✅
**Fecha**: <?php echo date('d/m/Y H:i:s'); ?>
**Desarrollado por**: Asistente IA

