<?php
require_once __DIR__ . '/../Controllers/GenreController.php';
$controller = new GenreController();

if ($uri === 'genres' && $method === 'GET') {
    echo json_encode($controller->index());
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}