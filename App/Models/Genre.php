<?php
require_once __DIR__ . '/../../Config/database.php';

class Genre {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM Genre");
        return $stmt->fetchAll();
    }
}