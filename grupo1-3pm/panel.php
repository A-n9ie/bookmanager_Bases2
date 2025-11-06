<?php
session_start();
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit; 
}

require_once 'models/Book.php';
$book = new Book();

// === ELIMINAR ===
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $book->deleteBook($id);
    header("Location: panel.php");
    exit;
}

// === FILTROS ===
$searchTerm = $_GET['buscar'] ?? '';
$genre = $_GET['genre'] ?? '';
$year = $_GET['year'] ?? '';
$order = $_GET['order'] ?? 'desc';

// === DATOS ===
$result = $book->getBooks($searchTerm, $genre, $year, $order);
$genres = $book->getGenres();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel - Biblioteca Contemporánea Nocturna</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/panel.css">
</head>

<body>
<div id="particles"></div>

<header>
  <h1>Biblioteca Nocturna</h1>
  <form method="POST" action="logout.php">
    <button class="logout">Cerrar sesión</button>
  </form>
</header>

<div class="container">
  <h2>Gestión de libros</h2>

  <form class="search-bar" method="GET" action="">
    <input type="text" name="buscar" placeholder="Buscar por título o autor" 
           value="<?= htmlspecialchars($searchTerm) ?>">

    <select name="genre" class="select-filter">
      <option value="">Todos los géneros</option>
      <?php foreach ($genres as $g): ?>
        <option value="<?= htmlspecialchars($g) ?>" <?= ($g == $genre) ? 'selected' : '' ?>>
          <?= htmlspecialchars($g) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <input type="number" name="year" placeholder="Año" min="0" 
           value="<?= htmlspecialchars($year) ?>" class="input-year">

    <select name="order" class="select-filter">
      <option value="desc" <?= ($order === 'desc') ? 'selected' : '' ?>>Más nuevos</option>
      <option value="asc" <?= ($order === 'asc') ? 'selected' : '' ?>>Más antiguos</option>
    </select>

    <button type="submit" class="btn-golden">🔍 Buscar</button>
    <a href="panel.php" class="btn-golden">🧹 Limpiar</a>
  </form>

  <div class="add-btn">
    <a href="add.php" class="btn-golden">➕ Agregar nuevo libro</a>
	<a href="export.php?type=pdf&<?= http_build_query($_GET) ?>" class="btn-golden">📄 Exportar PDF</a>
	<a href="export.php?type=excel&<?= http_build_query($_GET) ?>" class="btn-golden">📊 Exportar Excel</a>
	<a href="stats.php" class="btn-golden">📊 Ver estadísticas</a>
  </div>
  


  <h2>Lista de libros</h2>
  <table>
    <tr><th>ID</th><th>Título</th><th>Autor</th><th>Año</th><th>Género</th><th>Acciones</th></tr>
    <?php if(!$result): ?>
      <tr><td colspan="6">No hay resultados.</td></tr>
    <?php else: foreach($result as $row): ?>
      <tr>
        <td><?=$row['id']?></td>
        <td><?=htmlspecialchars($row['title'])?></td>
        <td><?=htmlspecialchars($row['author'])?></td>
        <td><?=htmlspecialchars($row['year'])?></td>
        <td><?=htmlspecialchars($row['genre'])?></td>
        <td>
		  <a class="action" href="edit.php?id=<?=$row['id']?>">✏️</a>
		  <a class="action delete-btn" 
			 data-id="<?=$row['id']?>" 
			 data-title="<?=htmlspecialchars($row['title'])?>" 
			 href="#">🗑️</a>
		</td>
      </tr>
    <?php endforeach; endif;?>
  </table>
</div>

<!-- 🗑️ Modal de confirmación personalizado -->
<div id="confirmModal" class="modal-overlay">
  <div class="modal-box">
    <h3>Confirmar eliminación</h3>
    <p id="modalMessage"></p>
    <div class="modal-actions">
      <button id="cancelBtn" class="btn-cancel">Cancelar</button>
      <button id="confirmDeleteBtn" class="btn-confirm">Eliminar</button>
    </div>
  </div>
</div>

<footer>© 2025 Biblioteca Contemporánea Nocturna</footer>


<script>
// Partículas doradas ✨
const particlesContainer = document.getElementById("particles");
const numParticles = 50;
for (let i = 0; i < numParticles; i++) {
  const p = document.createElement("div");
  p.classList.add("particle");
  p.style.left = Math.random() * 100 + "%";
  p.style.top = Math.random() * 100 + "%";
  p.style.animationDuration = (8 + Math.random() * 5) + "s";
  p.style.animationDelay = Math.random() * 5 + "s";
  p.style.width = p.style.height = (3 + Math.random() * 5) + "px";
  particlesContainer.appendChild(p);
}

// 💫 Modal de confirmación personalizado
const modal = document.getElementById("confirmModal");
const modalMessage = document.getElementById("modalMessage");
const cancelBtn = document.getElementById("cancelBtn");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

let deleteId = null;

// Al hacer clic en 🗑️
document.querySelectorAll(".delete-btn").forEach(btn => {
  btn.addEventListener("click", e => {
    e.preventDefault();
    deleteId = btn.dataset.id;
    const title = btn.dataset.title;
    modalMessage.textContent = `¿Estás seguro de que deseas eliminar «${title}»?`;
    modal.style.display = "flex";
  });
});

// Botón cancelar
cancelBtn.addEventListener("click", () => {
  modal.style.display = "none";
  deleteId = null;
});

// Botón confirmar
confirmDeleteBtn.addEventListener("click", () => {
  if (deleteId) {
    window.location.href = `?del=${deleteId}`;
  }
});
</script>


</body>
</html>
