<?php
class Book {
    private $db;

    public function __construct($dbPath = 'db/biblioteca.db') {
        try {
            $this->db = new PDO('sqlite:' . $dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("❌ Error de conexión: " . $e->getMessage());
        }
    }
    /** 
     * Obtiene por ID
     */	
	public function getBookById($id) {
    $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    /** 
     * Elimina un libro por su ID
     */
    public function deleteBook($id) {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene los géneros únicos
     */
    public function getGenres() {
        return $this->db->query("SELECT DISTINCT genre FROM books ORDER BY genre ASC")
                        ->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Obtiene una lista de libros con filtros opcionales
     */
    public function getBooks($search = '', $genre = '', $year = '', $order = 'desc') {
        $query = "SELECT * FROM books WHERE 1";
        $params = [];

        if ($search !== '') {
            $query .= " AND (title LIKE ? OR author LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($genre !== '') {
            $query .= " AND genre = ?";
            $params[] = $genre;
        }

        if ($year !== '') {
            $query .= " AND year = ?";
            $params[] = $year;
        }

        $order = ($order === 'asc') ? 'ASC' : 'DESC';
        $query .= " ORDER BY year $order, id $order";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
