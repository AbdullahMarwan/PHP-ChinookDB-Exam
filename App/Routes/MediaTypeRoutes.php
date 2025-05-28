<?php
require_once __DIR__ . '/../Controllers/MediaTypeController.php';
$controller = new MediaTypeController();

if ($uri === 'media_types' && $method === 'GET') {
    echo json_encode($controller->index());
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}