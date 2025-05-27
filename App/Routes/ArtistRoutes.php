<?php
// filepath: App/Routes/artistRoutes.php
require_once __DIR__ . '/../Controllers/ArtistController.php';
$controller = new ArtistController();

// GET /artists and /artists?s=
if ($uri === 'artists' && $method === 'GET') {
    echo json_encode($controller->index($_GET));
    exit;
}

// GET /artists/<artist_id>
if (preg_match('#^artists/(\d+)$#', $uri, $matches) && $method === 'GET') {
    echo json_encode($controller->show($matches[1]));
    exit;
}

// GET /artists/<artist_id>/albums
if (preg_match('#^artists/(\d+)/albums$#', $uri, $matches) && $method === 'GET') {
    echo json_encode($controller->albums($matches[1]));
    exit;
}

// POST /artists
if ($uri === 'artists' && $method === 'POST') {
    $data = $_POST;
    echo json_encode($controller->create($data));
    exit;
}

// DELETE /artists/<artist_id>
if (preg_match('#^artists/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    echo json_encode($controller->delete($matches[1]));
    exit;
}