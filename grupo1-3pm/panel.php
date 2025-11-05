<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }

try {
    $db = new PDO('sqlite:db/biblioteca.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

// === ELIMINAR ===
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $stmt = $db->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: panel.php");
    exit;
}

// === BUSCAR ===
$searchTerm = '';
if (isset($_GET['buscar'])) {
    $searchTerm = trim($_GET['buscar']);
    $stmt = $db->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $result = $db->query("SELECT * FROM books ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel - Biblioteca Contemporánea Nocturna</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">
<style>
body {
  margin:0;
  font-family:"Playfair Display",serif;
  background:url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') center/cover fixed no-repeat;
  background-blend-mode:multiply;
  background-color:#1a1410;
  color:#f1f1f1;
  overflow-x:hidden;
}
header {
  text-align:center;
  padding:2rem;
  background:rgba(25,20,15,0.6);
  border-bottom:1px solid rgba(255,215,0,0.25);
  position:relative;
  z-index:2;
}
h1 {
  font-family:"Cinzel Decorative",serif;
  font-size:3rem;
  color:#fffaf0;
  text-shadow:0 0 30px rgba(255,223,120,0.45);
}
.logout {
  position:absolute;
  right:30px;
  top:25px;
  background:linear-gradient(135deg,#7a1f0d,#d63031);
  color:#fff;
  border:none;
  border-radius:8px;
  padding:0.6rem 1rem;
  cursor:pointer;
  z-index:3;
}
.container {
  max-width:950px;
  margin:3rem auto;
  background:rgba(20,15,10,0.85);
  border-radius:20px;
  padding:2.5rem;
  box-shadow:0 0 40px rgba(0,0,0,0.6);
  position:relative;
  z-index:2;
}
h2 {
  color:#f3c13a;
  text-align:center;
  margin-bottom:1.8rem;
}

/* === BARRA DE BÚSQUEDA === */
.search-bar {
  display:flex;
  justify-content:center;
  align-items:center;
  gap:0.7rem;
  flex-wrap:wrap;
  margin-bottom:2rem;
}
.search-bar input {
  width:360px;
  height:48px;
  border:none;
  border-radius:10px;
  padding:0 1rem;
  font-size:1rem;
  text-align:center;
  color:#fff;
  background:rgba(255,255,255,0.12);
  box-shadow:inset 0 0 6px rgba(255,215,0,0.3);
  transition:all 0.3s ease;
}
.search-bar input::placeholder { color:#ddd; }
.search-bar input:focus {
  outline:none;
  background:rgba(255,255,255,0.25);
  box-shadow:0 0 10px rgba(255,215,0,0.5);
}

/* === BOTONES DORADOS === */
.btn-golden {
  height:48px;
  min-width:130px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:0.4rem;
  padding:0 1.5rem;
  font-family:"Playfair Display",serif;
  font-size:1rem;
  font-weight:600;
  color:#fff;
  background:linear-gradient(135deg,#6b540b,#c19e32);
  border:none;
  border-radius:10px;
  text-decoration:none;
  cursor:pointer;
  box-shadow:0 0 10px rgba(255,215,0,0.15);
  transition:all 0.3s ease;
}
.btn-golden:hover {
  background:linear-gradient(135deg,#c8a945,#f8e27b);
  color:#1a1410;
  transform:scale(1.04);
}

/* === BOTÓN AGREGAR === */
.add-btn {
  display:flex;
  justify-content:center;
  margin-bottom:1.5rem;
}
.add-btn a {
  height:48px;
  padding:0 2rem;
}

/* === TABLA === */
table {
  width:100%;
  border-collapse:separate;
  border-spacing:0 8px;
}
th,td { text-align:center; padding:0.9rem; }
th { color:#ffd700; border-bottom:2px solid rgba(255,215,0,0.4); }
td { background:rgba(255,255,255,0.05); border-radius:6px; }
a.action { color:#ffd700; text-decoration:none; margin:0 6px; }

/* === RESPONSIVO === */
@media (max-width:650px) {
  .search-bar { flex-direction:column; }
  .search-bar input, .btn-golden { width:90%; }
}

/* === PARTÍCULAS DORADAS === */
#particles {
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  z-index:0;
  pointer-events:none;
  overflow:hidden;
}
.particle {
  position:absolute;
  background:radial-gradient(circle, rgba(255,215,100,0.9) 0%, rgba(255,215,0,0) 70%);
  border-radius:50%;
  width:6px;
  height:6px;
  animation:float 10s infinite linear;
  opacity:0.7;
}
@keyframes float {
  0% { transform:translateY(0) scale(1); opacity:0.8; }
  50% { transform:translateY(-100vh) scale(1.5); opacity:0.3; }
  100% { transform:translateY(0) scale(1); opacity:0.8; }
}
</style>
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
    <input type="text" name="buscar" placeholder="Buscar por título o autor" value="<?= htmlspecialchars($searchTerm) ?>">
    <button type="submit" class="btn-golden">🔍 Buscar</button>
    <a href="panel.php" class="btn-golden">🧹 Limpiar</a>
  </form>

  <div class="add-btn">
    <a href="add.php" class="btn-golden">➕ Agregar nuevo libro</a>
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
          <a class="action" href="?del=<?=$row['id']?>" onclick="return confirm('¿Eliminar este libro?')">🗑️</a>
        </td>
      </tr>
    <?php endforeach; endif;?>
  </table>
</div>

<footer>© 2025 Biblioteca Contemporánea Nocturna</footer>

<script>
// Generador de partículas doradas ✨
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
</script>
</body>
</html>
