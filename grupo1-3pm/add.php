<?php
session_start();
if(!isset($_SESSION['user'])){header('Location: login.php');exit;}
try{
  $db=new PDO('sqlite:db/biblioteca.db');
  $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){die("❌ ".$e->getMessage());}

if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt=$db->prepare("INSERT INTO books(title,author,year,genre)VALUES(?,?,?,?)");
  $stmt->execute([$_POST['title'],$_POST['author'],$_POST['year'],$_POST['genre']]);
  header("Location: panel.php");exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Libro</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">
<style>
body{margin:0;font-family:"Playfair Display",serif;background:url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f')center/cover fixed no-repeat;background-blend-mode:multiply;background-color:#1a1410;color:#f1f1f1;display:flex;justify-content:center;align-items:center;min-height:100vh;}
form{background:rgba(20,15,10,0.85);padding:2rem;border-radius:20px;box-shadow:0 0 40px rgba(0,0,0,0.6);text-align:center;}
h2{color:#f3c13a;}
input{display:block;margin:0.6rem auto;padding:0.6rem;width:240px;border:none;border-radius:8px;}
button,a{background:linear-gradient(135deg,#493a0c,#c19e32);color:white;border:none;border-radius:8px;padding:0.6rem 1.2rem;font-weight:600;text-decoration:none;display:inline-block;margin-top:1rem;}
button:hover,a:hover{background:linear-gradient(135deg,#c8a945,#f8e27b);color:#1a1410;transform:scale(1.05);}
</style>
</head>
<body>
<form method="POST">
  <h2>➕ Agregar nuevo libro</h2>
  <input type="text" name="title" placeholder="Título" required>
  <input type="text" name="author" placeholder="Autor" required>
  <input type="number" name="year" placeholder="Año">
  <input type="text" name="genre" placeholder="Género">
  <button type="submit">Guardar</button>
  <a href="panel.php">Cancelar</a>
</form>
</body>
</html>
