<?php
// filepath: /Applications/MAMP/htdocs/PHP-ChinookDB-Exam/App/Controllers/MediaTypeController.php
require_once __DIR__ . '/../Models/MediaType.php';

class MediaTypeController {
    private $model;

    public function __construct() {
        $this->model = new MediaType();
    }

    public function index() {
        return $this->model->getAll();
    }
}