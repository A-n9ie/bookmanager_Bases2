<?php
$dbFile = __DIR__ . '/db/biblioteca.db';

// Si no existe la BD → redirigir a instalación
if (!file_exists($dbFile)) {
    header('Location: install.php');
    exit;
}

// Conexión a la BD
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Mostrar inicio básico
echo "<h2>📚 Bienvenido a tu Biblioteca Personal</h2>";
echo "<p><a href='login.php'>Iniciar sesión</a></p>";
?>
