<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'models/Book.php';

$book = new Book();
// === FILTROS ===
$searchTerm = $_GET['buscar'] ?? '';
$selectedGenres = $_GET['genres'] ?? []; // ahora es un array
$yearFrom = $_GET['year_from'] ?? '';
$yearTo = $_GET['year_to'] ?? '';
$order = $_GET['order'] ?? 'desc';

// === Datos filtrados ===
$data = $book->getBooksAdvanced($searchTerm, $selectedGenres, $yearFrom, $yearTo, $order);
$genres = $book->getGenres();

// === Preparar datos para gráficos ===
// 1. Conteo por género
$genreCount = [];
foreach ($data as $row) {
    $g = $row['genre'] ?: 'Sin género';
    $genreCount[$g] = ($genreCount[$g] ?? 0) + 1;
}

// 2. Conteo por año
$yearCount = [];
foreach ($data as $row) {
    $y = $row['year'] ?: 'Desconocido';
    $yearCount[$y] = ($yearCount[$y] ?? 0) + 1;
}

// Ordenar años ascendentemente
ksort($yearCount);

// === Resumen numérico ===
$totalBooks = count($data);
$mostPopularGenre = $genreCount ? array_keys($genreCount, max($genreCount))[0] : 'N/A';
$yearRange = $yearCount ? (min(array_keys($yearCount)) . ' - ' . max(array_keys($yearCount))) : 'N/A';

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Estadísticas - Biblioteca Nocturna</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/panel.css">
<link rel="stylesheet" href="css/stats.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
  <h1>Biblioteca Nocturna - Estadísticas</h1>
  <form method="POST" action="logout.php">
    <button class="logout">Cerrar sesión</button>
  </form>
</header>

<div class="container">
  <h2>Filtros</h2>
  <form class="search-bar" method="GET" action="">
  <div class="filter-group">
    <label>Géneros:</label>
    <div class="checkbox-group">
        <?php foreach ($genres as $g): ?>
            <label>
                <input type="checkbox" name="genres[]" value="<?= htmlspecialchars($g) ?>" 
                       <?= in_array($g, $selectedGenres) ? 'checked' : '' ?>>
                <?= htmlspecialchars($g) ?>
            </label>
        <?php endforeach; ?>
    </div>
</div>
  <input type="text" name="buscar" placeholder="Título o autor" 
         value="<?= htmlspecialchars($searchTerm) ?>">
  <input type="number" name="year_from" placeholder="Año desde" min="0" value="<?= htmlspecialchars($yearFrom) ?>" class="input-year">
  <input type="number" name="year_to" placeholder="Año hasta" min="0" value="<?= htmlspecialchars($yearTo) ?>" class="input-year">

  <select name="order" class="select-filter">
    <option value="desc" <?= ($order === 'desc') ? 'selected' : '' ?>>Más nuevos</option>
    <option value="asc" <?= ($order === 'asc') ? 'selected' : '' ?>>Más antiguos</option>
  </select>

  <button type="submit" class="btn-golden">Aplicar filtros</button>
  <a href="stats.php" class="btn-golden">Limpiar</a>
</form>

<table class="summary-table">
  <tr>
    <th>Total de libros filtrados</th>
    <th>Género más popular</th>
    <th>Rango de años</th>
  </tr>
  <tr>
    <td><?= $totalBooks ?></td>
    <td><?= htmlspecialchars($mostPopularGenre) ?></td>
    <td><?= htmlspecialchars($yearRange) ?></td>
  </tr>
</table>


  <h2>Gráficos de libros</h2>
  <div class="charts-container">
    <div class="chart-box">
      <h3>Libros por género</h3>
      <canvas id="genreChart"></canvas>
    </div>
    <div class="chart-box">
      <h3>Libros por año</h3>
      <canvas id="yearChart"></canvas>
    </div>
  <a href="panel.php" class="btn-golden">⬅ Volver al panel</a>
	
  </div>
</div>

<footer>© 2025 Biblioteca Contemporánea Nocturna</footer>

<script>
const genreCtx = document.getElementById('genreChart').getContext('2d');
const genreChart = new Chart(genreCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_keys($genreCount)) ?>,
        datasets: [{
            label: 'Libros por género',
            data: <?= json_encode(array_values($genreCount)) ?>,
            backgroundColor: [
                '#FFD700', // dorado
                '#FF8C00', // naranja
                '#FF69B4', // rosa
                '#8A2BE2', // morado
                '#00CED1', // turquesa
                '#32CD32', // verde suave 🌿
                '#DC143C',  // rojo
				'#FF7F50'  // salmón suave 🌅
            ],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { color: '#FFFFFF' }  }
        }
    }
});

const yearCtx = document.getElementById('yearChart').getContext('2d');
const yearChart = new Chart(yearCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($yearCount)) ?>,
        datasets: [{
            label: 'Libros por año',
            data: <?= json_encode(array_values($yearCount)) ?>,
            backgroundColor: '#6495ED',  // color único, suave y agradable
            borderColor: '#4177B5',      // contorno opcional más oscuro
            borderWidth: 1
        }]
    },
    options: {
    scales: {
        y: { 
            beginAtZero: true,
            ticks: { color: '#FFFFFF' } // Números eje Y en blanco
        },
        x: {
            ticks: { color: '#FFFFFF' } // Números eje X en blanco
        }
    },
    plugins: {
        legend: { labels: { color: '#FFFFFF' } } // Leyenda en blanco (si hubiera)
    }
}

});
</script>

</body>
</html>
