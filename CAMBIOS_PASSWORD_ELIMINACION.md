# Cambios: Verificación de Contraseña de Administrador para Eliminar Contratos

## Resumen
Se modificó el sistema para que al eliminar un contrato, la contraseña solicitada sea verificada contra la contraseña del administrador almacenada en la base de datos, en lugar de usar una contraseña fija en el código.

## Archivos Modificados

### 1. `controllers/ContratoController.php`
**Método:** `eliminar()`

**Cambios realizados:**
- Se agregó verificación de que se haya enviado la contraseña (`$_POST['password_admin']`)
- Se consulta la contraseña del usuario administrador desde la base de datos:
  ```php
  $sql = "SELECT contrasena FROM usuario WHERE tipo_usuario = 'admin' LIMIT 1";
  ```
- Se utiliza `password_verify()` para comparar la contraseña ingresada con la hash almacenada en la base de datos
- Se muestran mensajes apropiados según el resultado de la verificación
- Solo si la contraseña es correcta, se procede a eliminar el contrato

### 2. `views/contratos/listar.php`
**Función JavaScript:** `confirmarEliminacion()`

**Cambios realizados:**
- Se eliminó la verificación hardcodeada (`if (pass === "admin123")`)
- Ahora la función crea un campo oculto en el formulario con la contraseña ingresada:
  ```javascript
  const inputPass = document.createElement('input');
  inputPass.type = 'hidden';
  inputPass.name = 'password_admin';
  inputPass.value = pass;
  form.appendChild(inputPass);
  ```
- La contraseña se envía al servidor para su verificación

## Flujo de Funcionamiento

1. Usuario hace clic en el botón de eliminar contrato
2. Aparece un prompt solicitando la contraseña de administrador
3. Si el usuario cancela, no se hace nada
4. Si el usuario ingresa una contraseña, se agrega como campo oculto al formulario
5. El formulario se envía al servidor
6. El controlador verifica la contraseña contra la base de datos
7. Si es correcta, elimina el contrato; si no, muestra mensaje de error

## Seguridad

- La contraseña se verifica usando `password_verify()` que compara de forma segura con el hash almacenado
- La contraseña se envía por POST (no por GET)
- No hay contraseñas hardcodeadas en el código
- Se utilizan mensajes de sesión para informar el resultado

## Nota Importante

La contraseña del administrador en la base de datos está hasheada usando bcrypt. Según el archivo `database.sql`, el usuario administrador es:
- **Correo:** admin@rrhh.com
- **Contraseña por defecto:** 123456 (está hasheada en la base de datos)

Si se necesita cambiar la contraseña del administrador, debe hacerse a través del sistema de gestión de usuarios o directamente en la base de datos usando `password_hash()` en PHP.
