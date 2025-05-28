<?php
require_once __DIR__ . '/../Controllers/PlaylistController.php';
$controller = new PlaylistController();

// GET /playlists and /playlists?s=
if ($uri === 'playlists' && $method === 'GET') {
    echo json_encode($controller->index($_GET));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// GET /playlists/<playlist_id>
if (preg_match('#^playlists/(\d+)$#', $uri, $matches) && $method === 'GET') {
    echo json_encode($controller->show($matches[1]));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// POST /playlists
if ($uri === 'playlists' && $method === 'POST') {
    $data = $_POST;
    echo json_encode($controller->create($data));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// POST /playlists/<playlist_id>/tracks
if (preg_match('#^playlists/(\d+)/tracks$#', $uri, $matches) && $method === 'POST') {
    $trackId = $_POST['track_id'] ?? null;
    echo json_encode($controller->addTrack($matches[1], $trackId));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// DELETE /playlists/<playlist_id>/tracks/<track_id>
if (preg_match('#^playlists/(\d+)/tracks/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    echo json_encode($controller->removeTrack($matches[1], $matches[2]));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}

// DELETE /playlists/<playlist_id>
if (preg_match('#^playlists/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    echo json_encode($controller->delete($matches[1]));
    Logger::logRequest($method, $uri, $_REQUEST, http_response_code());
    exit;
}