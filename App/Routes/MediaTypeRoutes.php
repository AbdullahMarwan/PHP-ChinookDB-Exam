<?php
require_once __DIR__ . '/../Controllers/MediaTypeController.php';
$controller = new MediaTypeController();

if ($uri === 'media_types' && $method === 'GET') {
    echo json_encode($controller->index());
    exit;
}