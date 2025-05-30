<?php
require_once __DIR__ . '/../Controllers/AlbumController.php';
$controller = new AlbumController();

// GET /albums and /albums?s=
if ($uri === 'albums' && $method === 'GET') {
    echo json_encode($controller->index($_GET));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// GET /albums/<album_id>
if (preg_match('#^albums/(\d+)$#', $uri, $matches) && $method === 'GET') {
    echo json_encode($controller->show($matches[1]));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// GET /albums/<album_id>/tracks
if (preg_match('#^albums/(\d+)/tracks$#', $uri, $matches) && $method === 'GET') {
    echo json_encode($controller->tracks($matches[1]));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// POST /albums
if ($uri === 'albums' && $method === 'POST') {
    $data = $_POST;
    echo json_encode($controller->create($data));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// PUT (as POST) /albums/<album_id>
if (preg_match('#^albums/(\d+)$#', $uri, $matches) && $method === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $data = $_POST;
    unset($data['_method']);
    echo json_encode($controller->update($matches[1], $data));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// DELETE /albums/<album_id>
if (preg_match('#^albums/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    echo json_encode($controller->delete($matches[1]));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}