<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ruta del archivo SQLite
$dbDir  = __DIR__ . '/db';
$dbFile = $dbDir . '/biblioteca.db';

// Crear carpeta si no existe
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0777, true);
}

try {
    // Si la BD ya existe, redirigir a index
    if (file_exists($dbFile)) {
        header('Location: index.php');
        exit;
    }

    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear tabla de libros
    $db->exec("
        CREATE TABLE books (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            author TEXT NOT NULL,
            year INTEGER,
            genre TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // Crear tabla de usuarios
    $db->exec("
        CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            email TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // Crear usuario admin (password hash seguro)
    $hash = password_hash('clave123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $hash, 'admin@biblioteca.edu']);

    // Insertar datos de ejemplo
    $sample = $db->prepare("INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)");
    $ejemplos = [
        ['Cien años de soledad', 'Gabriel García Márquez', 1967, 'Realismo mágico'],
        ['El Principito', 'Antoine de Saint-Exupéry', 1943, 'Fábula'],
        ['1984', 'George Orwell', 1949, 'Distopía']
    ];
    foreach ($ejemplos as $b) {
        $sample->execute($b);
    }

    echo "<h2>✅ Instalación completada correctamente</h2>";
    echo "<p>Se ha creado la base de datos y el usuario <b>admin</b>.</p>";
    echo "<meta http-equiv='refresh' content='2;url=index.php'>";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
