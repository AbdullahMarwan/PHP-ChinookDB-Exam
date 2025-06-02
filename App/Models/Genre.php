<?php
require_once __DIR__ . '/../../Config/database.php';

class Genre {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Genre");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }
}