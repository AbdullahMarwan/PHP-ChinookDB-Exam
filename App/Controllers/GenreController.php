<?php
// filepath: /Applications/MAMP/htdocs/PHP-ChinookDB-Exam/App/Controllers/GenreController.php
require_once __DIR__ . '/../Models/Genre.php';

class GenreController {
    private $model;

    public function __construct() {
        $this->model = new Genre();
    }

    public function index() {
        try {
            return $this->model->getAll();
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }
}