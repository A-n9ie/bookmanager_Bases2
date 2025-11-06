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
  <title>Inicio - Biblioteca Contemporánea Nocturna</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">

  <style>
  body {
    margin: 0;
    font-family: "Playfair Display", serif;
    background: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') center/cover fixed no-repeat;
    background-blend-mode: multiply;
    background-color: #1a1410;
    color: #f1f1f1;
    height: 100vh;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  /* Contenedor de partículas */
  #particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
  }

  .particle {
    position: absolute;
    background: radial-gradient(circle, rgba(255,215,100,0.9) 0%, rgba(255,215,0,0) 70%);
    border-radius: 50%;
    width: 6px;
    height: 6px;
    animation: float 10s infinite linear;
    opacity: 0.7;
  }

  @keyframes float {
    0% { transform: translateY(0) scale(1); opacity: 0.8; }
    50% { transform: translateY(-100vh) scale(1.4); opacity: 0.3; }
    100% { transform: translateY(0) scale(1); opacity: 0.8; }
  }

  /* Título */
  .main-title {
    font-family: "Cinzel Decorative", serif;
    font-size: 3rem;
    text-align: center;
    color: #fffaf0;
    text-shadow: 0 0 35px rgba(255,223,120,0.55);
    animation: glow 6s ease-in-out infinite;
    z-index: 2;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
  }

  .main-title img {
    width: 70px;
    height: 70px;
  }

  @keyframes glow {
    0%, 100% { text-shadow: 0 0 25px rgba(255,210,100,0.6); }
    50% { text-shadow: 0 0 45px rgba(255,240,160,0.9); }
  }

  /* Contenedor del botón */
  .form-container {
    background: rgba(20, 15, 10, 0.85);
    border-radius: 20px;
    padding: 2rem 3rem;
    box-shadow: 0 0 40px rgba(0,0,0,0.6);
    position: relative;
    z-index: 2;
    text-align: center;
  }

  /* Botón */
  .btn-custom {
    display: inline-block;
    background: linear-gradient(135deg, #6b540b, #c19e32);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0.9rem 2.5rem;
    font-size: 1.2rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 0 10px rgba(255,215,0,0.15);
  }

  .btn-custom:hover {
    background: linear-gradient(135deg, #c8a945, #f8e27b);
    color: #1a1410;
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(255,215,0,0.4);
  }

  footer {
    text-align: center;
    color: #ccc;
    font-size: 0.9rem;
    margin-top: 2rem;
    z-index: 2;
    position: relative;
  }
  </style>
</head>

<body>
<div id="particles"></div>

  <h1 class="main-title">
    BIENVENIDO A TU BIBLIOTECA PERSONAL
  </h1>

  <div class="form-container">
    <a href="login.php" class="btn-custom">🔒 Iniciar sesión</a>
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
  </script>
</body>
</html>
