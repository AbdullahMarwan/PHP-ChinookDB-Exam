<?php
require_once __DIR__ . '/../../Config/database.php';

class Artist {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll($search = null) {
        if ($search) {
            $stmt = $this->pdo->prepare("SELECT * FROM Artist WHERE Name LIKE ?");
            $stmt->execute(['%' . $search . '%']);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM Artist");
        }
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Artist WHERE ArtistId = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}