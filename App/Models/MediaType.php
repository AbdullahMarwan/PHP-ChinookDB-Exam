<?php
require_once __DIR__ . '/../../Config/database.php';

class MediaType {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM MediaType");
        return $stmt->fetchAll();
    }
}