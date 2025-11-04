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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Biblioteca</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>📚 Bienvenido a tu Biblioteca Personal</h2>
    <p><a href="login.php">Iniciar sesión</a></p>
</body>
</html>
