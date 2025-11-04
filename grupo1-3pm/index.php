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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio - Biblioteca</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css?v=6">
</head>
<body>
  <!-- Título principal -->
  <h1 class="main-title">📚 Bienvenido a tu Biblioteca Personal</h1>

  <!-- Contenedor con botón -->
  <div class="form-container text-center">
    <a href="login.php" class="btn btn-custom w-100">Iniciar sesión</a>
  </div>
</body>
</html>
