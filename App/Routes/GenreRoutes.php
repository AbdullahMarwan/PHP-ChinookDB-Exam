<?php
require_once __DIR__ . '/../Controllers/GenreController.php';
$controller = new GenreController();

if ($uri === 'genres' && $method === 'GET') {
    echo json_encode($controller->index());
    exit;
}