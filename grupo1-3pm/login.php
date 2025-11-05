<?php
session_start();

try {
    $db = new PDO('sqlite:db/biblioteca.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header('Location: panel.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Biblioteca Contemporánea Nocturna · Iniciar Sesión</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">
<style>
/* ======== ESTILO GENERAL ======== */
body {
  margin: 0;
  font-family: "Playfair Display", serif;
  background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f');
  background-size: cover;
  background-attachment: fixed;
  background-position: center;
  background-blend-mode: multiply;
  background-color: #1a1410;
  color: #f1f1f1;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transition: all 1.5s ease;
}

/* ======== TITULO ======== */
h1 {
  font-family: "Cinzel Decorative", serif;
  font-size: 3.3rem;
  color: #fffaf0;
  text-shadow: 0 0 30px rgba(255, 223, 120, 0.45);
  animation: titlePulse 6s ease-in-out infinite;
  margin-bottom: 1rem;
  cursor: pointer;
}
@keyframes titlePulse {
  0%,100% { text-shadow: 0 0 25px rgba(255, 210, 100, 0.6); }
  50% { text-shadow: 0 0 45px rgba(255, 240, 160, 0.9); }
}

/* ======== FORMULARIO ======== */
.form-container {
  background: rgba(20, 15, 10, 0.85);
  padding: 2.5rem 3rem;
  border-radius: 20px;
  box-shadow: 0 0 50px rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(10px);
  text-align: center;
  width: 320px;
  max-width: 85%;
}
label {
  display: block;
  text-align: left;
  margin-bottom: 0.3rem;
  color: #ffd700;
  font-weight: 600;
}
input {
  width: 100%;
  padding: 0.6rem;
  margin-bottom: 1rem;
  border-radius: 8px;
  border: none;
  outline: none;
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  font-size: 1rem;
  transition: all 0.3s ease;
}
input:focus {
  background: rgba(255, 255, 255, 0.2);
  box-shadow: 0 0 10px rgba(255, 215, 0, 0.4);
}
button {
  background: linear-gradient(135deg, #493a0c, #c19e32);
  color: white;
  border: none;
  border-radius: 8px;
  padding: 0.6rem 1.2rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
  box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
}
button:hover {
  background: linear-gradient(135deg, #c8a945, #f8e27b);
  color: #1a1410;
  transform: scale(1.08);
  box-shadow: 0 0 15px rgba(255, 215, 0, 0.6);
}

/* ======== ALERTAS ======== */
.alert {
  background: rgba(255, 100, 100, 0.2);
  color: #ffaaaa;
  border: 1px solid rgba(255, 50, 50, 0.4);
  border-radius: 8px;
  padding: 0.6rem;
  margin-top: 1rem;
  font-size: 0.95rem;
}

/* ======== FOOTER ======== */
footer {
  text-align: center;
  color: #ddd;
  font-size: 0.9rem;
  margin-top: 2rem;
}

/* ======== EASTER EGGS ======== */
.particle {
  position: fixed;
  width: 4px;
  height: 4px;
  background: rgba(255, 215, 0, 0.8);
  border-radius: 50%;
  animation: float 8s linear infinite;
  pointer-events: none;
}
@keyframes float {
  0% { transform: translateY(0); opacity: 0.8; }
  100% { transform: translateY(-100vh); opacity: 0; }
}
.enchanted { background-color: #2a1d15 !important; filter: brightness(1.2) sepia(0.4); }
.infinite { animation: spinBg 60s linear infinite; }
@keyframes spinBg { from { background-position: 0 0; } to { background-position: 10000px 0; } }

#portal {
  display: none;
  position: fixed;
  top: 40%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 3rem;
  color: gold;
  text-shadow: 0 0 25px goldenrod;
  font-family: "Cinzel Decorative", serif;
  animation: pulse 3s infinite;
}
@keyframes pulse {
  0%,100%{transform:translate(-50%,-50%) scale(1)}
  50%{transform:translate(-50%,-50%) scale(1.2)}
}
#dragon {
  display: none;
  position: fixed;
  top: 50%;
  left: -200px;
  font-size: 6rem;
  animation: fly 8s linear infinite;
}
@keyframes fly {
  0% { left: -200px; transform: rotateY(0deg); }
  50% { left: 100vw; transform: rotateY(180deg); }
  100% { left: -200px; transform: rotateY(0deg); }
}
</style>
</head>

<body>
  <h1 id="titulo">Biblioteca Contemporánea Nocturna</h1>

  <div class="form-container">
    <h2 style="color:#f3c13a;">Iniciar Sesión</h2>
    <form method="POST" autocomplete="off" novalidate>
      <label>Usuario</label>
      <input type="text" name="username" placeholder="Ingresa tu usuario" required>
      <label>Contraseña</label>
      <input type="password" name="password" placeholder="••••••••" required>
      <button type="submit">Entrar</button>
      <?php if(isset($error)): ?>
        <div class="alert"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
    </form>
  </div>

  <footer>© 2025 Biblioteca Contemporánea Nocturna</footer>

  <!-- ELEMENTOS OCULTOS -->
  <div id="portal">⚜️ 𐌔𐌉𐌋𐌄𐌊𐌔 ⚜️</div>
  <audio id="audioAmbient" src="https://cdn.pixabay.com/download/audio/2022/03/15/audio_1b9ffb0c02.mp3?filename=library-ambience-19295.mp3"></audio>

<script>
// === PARTICULAS ===
const numParticles = 25;
for (let i = 0; i < numParticles; i++) {
  const p = document.createElement("div");
  p.classList.add("particle");
  p.style.left = Math.random() * 100 + "vw";
  p.style.top = Math.random() * 100 + "vh";
  p.style.animationDelay = Math.random() * 8 + "s";
  document.body.appendChild(p);
}

// === EASTER EGGS ===
const titulo = document.getElementById("titulo");
titulo.addEventListener("click", () => {
  document.body.classList.toggle("enchanted");
});

const portal = document.getElementById("portal");

document.addEventListener("keydown", (e) => {
  if (e.key.toLowerCase() === "b") document.body.classList.toggle("infinite");
  if (e.key.toLowerCase() === "o")
    portal.style.display = portal.style.display === "block" ? "none" : "block";

});

document.addEventListener("dblclick", () => {
  document.body.classList.remove("enchanted", "infinite");
  portal.style.display = "none";
  audio.pause();
});
</script>
</body>
</html>
