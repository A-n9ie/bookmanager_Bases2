<?php
session_start();

$db = new PDO('sqlite:db/biblioteca.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css?v=7">
</head>
<body>
  <!-- Título flotante, igual concepto que en index -->
  <h1 class="main-title">🔐 Acceso al Sistema</h1>

  <!-- Panel de login centrado -->
  <div class="form-container text-start">
    <h2 class="mb-3 text-center" style="margin-top:-8px;">Iniciar Sesión</h2>
    <form method="POST" autocomplete="off" novalidate>
      <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" name="username" class="form-control" placeholder="Ingresa tu usuario" required>
      </div>
      <div class="mb-2">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-custom w-100 mt-3">Entrar</button>

      <?php if(isset($error)): ?>
        <div class="alert alert-warning mt-3 mb-0 text-center"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
