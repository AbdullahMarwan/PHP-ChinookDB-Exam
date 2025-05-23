<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../App/Models/Artist.php';

// Get the HTTP method and path
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path if needed
$basePath = '/PHP-ChinookDB-Exam/public';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$uri = trim($uri, '/');

// Simple routing example
header('Content-Type: application/json');

if ($uri === 'artists' && $method === 'GET') {
    $artistModel = new Artist();
    $search = $_GET['s'] ?? null;
    $artists = $artistModel->getAll($search);
    echo json_encode($artists);
} elseif (preg_match('#^artists/(\d+)$#', $uri, $matches)) {
    $artistModel = new Artist();
    $artist = $artistModel->getById($matches[1]);
    if ($artist) {
        echo json_encode($artist);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Artist not found']);
    }
    exit;
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}