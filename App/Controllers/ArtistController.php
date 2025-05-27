<?php
// filepath: /Applications/MAMP/htdocs/PHP-ChinookDB-Exam/App/Controllers/ArtistController.php
require_once __DIR__ . '/../Models/Artist.php';
require_once __DIR__ . '/../Models/Album.php';

class ArtistController {
    private $model;
    private $albumModel;

    public function __construct() {
        $this->model = new Artist();
        $this->albumModel = new Album();
    }

    // GET /artists and /artists?s=
    public function index($params) {
        $search = $params['s'] ?? null;
        return $this->model->getAll($search);
    }

    // GET /artists/<artist_id>
    public function show($id) {
        $artist = $this->model->getById($id);
        if (!$artist) {
            http_response_code(404);
            return ['error' => 'Artist not found'];
        }
        return $artist;
    }

    // GET /artists/<artist_id>/albums
    public function albums($artistId) {
        return $this->albumModel->getByArtistId($artistId);
    }

    // POST /artists
    public function create($data) {
        if (empty($data['name'])) {
            http_response_code(400);
            return ['error' => 'Name is required'];
        }
        return $this->model->create($data['name']);
    }

    // DELETE /artists/<artist_id>
    public function delete($id) {
        // Check if artist exists
        if (!$this->model->getById($id)) {
            http_response_code(404);
            return ['error' => 'Artist not found'];
        }
        // Check if artist has albums
        $albums = $this->albumModel->getByArtistId($id);
        if (!empty($albums)) {
            http_response_code(400);
            return ['error' => 'Cannot delete artist with albums'];
        }
        if ($this->model->delete($id)) {
            return ['success' => true];
        }
        http_response_code(500);
        return ['error' => 'Failed to delete artist'];
    }
}