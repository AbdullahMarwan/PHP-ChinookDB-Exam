<?php
// filepath: /Applications/MAMP/htdocs/PHP-ChinookDB-Exam/App/Controllers/GenreController.php
require_once __DIR__ . '/../Models/Genre.php';

class GenreController {
    private $model;

    public function __construct() {
        $this->model = new Genre();
    }

    public function index() {
        return $this->model->getAll();
    }
}