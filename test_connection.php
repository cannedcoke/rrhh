<?php
/**
 * Script de prueba para verificar la conexión a la base de datos
 * y funcionalidad básica del sistema
 */

// Incluir configuración
require_once 'config/database.php';

echo "<h2>🔧 Prueba de Conexión - Sistema RRHH</h2>";

try {
    // Probar conexión
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ <strong>Conexión a la base de datos:</strong> Exitosa</p>";
        
        // Verificar tablas
        $tables = ['usuario', 'empleado', 'contrato', 'asistencia', 'evento', 'liquidacion'];
        $existing_tables = [];
        
        foreach ($tables as $table) {
            $query = "SHOW TABLES LIKE '$table'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $existing_tables[] = $table;
                echo "<p style='color: green;'>✅ <strong>Tabla '$table':</strong> Existe</p>";
            } else {
                echo "<p style='color: red;'>❌ <strong>Tabla '$table':</strong> No existe</p>";
            }
        }
        
        // Verificar usuarios de prueba
        $query = "SELECT COUNT(*) as total FROM usuario";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<p style='color: blue;'>📊 <strong>Usuarios en el sistema:</strong> " . $result['total'] . "</p>";
        
        if ($result['total'] > 0) {
            echo "<p style='color: green;'>✅ <strong>Usuarios de prueba:</strong> Configurados</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ <strong>Usuarios de prueba:</strong> No encontrados</p>";
        }
        
        // Verificar eventos
        $query = "SELECT COUNT(*) as total FROM evento";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<p style='color: blue;'>📊 <strong>Eventos en el sistema:</strong> " . $result['total'] . "</p>";
        
        if ($result['total'] > 0) {
            echo "<p style='color: green;'>✅ <strong>Eventos de prueba:</strong> Configurados</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ <strong>Eventos de prueba:</strong> No encontrados</p>";
        }
        
        echo "<hr>";
        echo "<h3>🎯 Estado del Sistema</h3>";
        
        if (count($existing_tables) == count($tables)) {
            echo "<p style='color: green; font-weight: bold;'>✅ <strong>Sistema listo para usar</strong></p>";
            echo "<p>Puede acceder al sistema en: <a href='index.php'>index.php</a></p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ <strong>Sistema incompleto</strong></p>";
            echo "<p>Faltan tablas en la base de datos. Ejecute el script database.sql</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ <strong>Error de conexión:</strong> No se pudo conectar a la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>📋 Información del Sistema</h3>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>PDO MySQL:</strong> " . (extension_loaded('pdo_mysql') ? 'Disponible' : 'No disponible') . "</li>";
echo "<li><strong>Directorio actual:</strong> " . __DIR__ . "</li>";
echo "<li><strong>Servidor:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido') . "</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Desarrollado por:</strong> Miqueas Zarate, Rodney Fariña, Cinthia González, Abel Molinas</p>";
echo "<p><strong>Profesor:</strong> Ruben Delgado | <strong>Año:</strong> 2025</p>";
?>

