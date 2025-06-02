<?php
require_once __DIR__ . '/../../Config/database.php';

class MediaType {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM MediaType");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }
}