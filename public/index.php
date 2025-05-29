<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Utils/Logger.php';

// Get the HTTP method and path
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path if needed
$basePath = $_ENV['BASE_PATH'] ?? '';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$uri = trim($uri, '/');

header('Content-Type: application/json');

// Include all route files
require_once __DIR__ . '/../App/Routes/ArtistRoutes.php';
require_once __DIR__ . '/../App/Routes/AlbumRoutes.php';
require_once __DIR__ . '/../App/Routes/TrackRoutes.php';
require_once __DIR__ . '/../App/Routes/PlaylistRoutes.php';
require_once __DIR__ . '/../App/Routes/GenreRoutes.php';
require_once __DIR__ . '/../App/Routes/MediaTypeRoutes.php';

// If no route matched, return 404
Logger::logRequest($method, $uri, $_REQUEST, 404);
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);