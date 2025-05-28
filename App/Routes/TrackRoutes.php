<?php
require_once __DIR__ . '/../Controllers/TrackController.php';
$controller = new TrackController();

// GET /tracks, /tracks?s=, /tracks?composer=
if ($uri === 'tracks' && $method === 'GET') {
    echo json_encode($controller->index($_GET));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// GET /tracks/<track_id>
if (preg_match('#^tracks/(\d+)$#', $uri, $matches) && $method === 'GET') {
    echo json_encode($controller->show($matches[1]));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// POST /tracks
if ($uri === 'tracks' && $method === 'POST') {
    $data = $_POST;
    echo json_encode($controller->create($data));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// PUT (as POST) /tracks/<track_id>
if (preg_match('#^tracks/(\d+)$#', $uri, $matches) && $method === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $data = $_POST;
    unset($data['_method']);
    echo json_encode($controller->update($matches[1], $data));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// DELETE /tracks/<track_id>
if (preg_match('#^tracks/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    echo json_encode($controller->delete($matches[1]));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}