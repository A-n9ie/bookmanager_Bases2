<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Conexión a la base de datos
try {
    $db = new PDO('sqlite:db/biblioteca.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

// === CREATE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $stmt = $db->prepare("INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['author'], $_POST['year'], $_POST['genre']]);
    header("Location: panel.php");
    exit;
}

// === DELETE ===
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $stmt = $db->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: panel.php");
    exit;
}

// === READ ===
$result = $db->query("SELECT * FROM books ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Biblioteca</title>
<link rel="stylesheet" href="style.css">
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #f5f6fa;
  color: #2f3640;
  margin: 0;
  padding: 0;
}
header {
  background-color: #273c75;
  color: white;
  padding: 15px;
  text-align: center;
}
.container {
  max-width: 800px;
  margin: 40px auto;
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h2 { color: #192a56; text-align: center; }
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
th, td {
  padding: 10px;
  border-bottom: 1px solid #dcdde1;
  text-align: left;
}
th { background: #718093; color: white; }
tr:hover { background: #f1f2f6; }
form input {
  padding: 8px;
  margin: 5px;
  width: 180px;
}
button {
  background: #44bd32;
  border: none;
  color: white;
  padding: 10px 15px;
  border-radius: 4px;
  cursor: pointer;
}
button:hover { background: #4cd137; }
.logout {
  float: right;
  background: #e84118;
}
.logout:hover { background: #ff4d4d; }
</style>
</head>
<body>

<header>
  <h1>📚 Panel de Biblioteca</h1>
  <form style="display:inline;" method="POST" action="logout.php">
    <button class="logout" type="submit">Cerrar sesión</button>
  </form>
</header>

<div class="container">
  <h2>Agregar nuevo libro</h2>
  <form method="POST">
    <input type="text" name="title" placeholder="Título" required>
    <input type="text" name="author" placeholder="Autor" required>
    <input type="number" name="year" placeholder="Año">
    <input type="text" name="genre" placeholder="Género">
    <button name="agregar" type="submit">➕ Agregar</button>
  </form>

  <h2>Lista de libros</h2>
  <table>
    <tr>
      <th>ID</th><th>Título</th><th>Autor</th><th>Año</th><th>Género</th><th>Acción</th>
    </tr>
    <?php if (count($result) === 0): ?>
      <tr><td colspan="6" style="text-align:center;">No hay libros registrados.</td></tr>
    <?php else: ?>
      <?php foreach ($result as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['author']) ?></td>
          <td><?= htmlspecialchars($row['year']) ?></td>
          <td><?= htmlspecialchars($row['genre']) ?></td>
          <td><a href="?del=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar este libro?')">🗑️</a></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>
</div>
</body>
</html>
