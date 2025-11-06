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
	
	public function getBooksAdvanced($searchTerm = '', $selectedGenres = [], $yearFrom = '', $yearTo = '', $order = 'desc') {
    $sql = "SELECT * FROM books WHERE 1=1";
    $params = [];

    if ($searchTerm) {
        $sql .= " AND (title LIKE :search OR author LIKE :search)";
        $params[':search'] = "%$searchTerm%";
    }

    if ($selectedGenres && is_array($selectedGenres)) {
        $placeholders = implode(',', array_fill(0, count($selectedGenres), '?'));
        $sql .= " AND genre IN ($placeholders)";
        $params = array_merge($params, $selectedGenres);
    }

    if ($yearFrom) {
        $sql .= " AND year >= ?";
        $params[] = $yearFrom;
    }
    if ($yearTo) {
        $sql .= " AND year <= ?";
        $params[] = $yearTo;
    }

    $sql .= " ORDER BY year $order";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
?>
