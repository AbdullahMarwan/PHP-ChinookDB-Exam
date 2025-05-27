<?php
// filepath: /Applications/MAMP/htdocs/PHP-ChinookDB-Exam/App/Controllers/PlaylistController.php
require_once __DIR__ . '/../Models/Playlist.php';

class PlaylistController {
    private $model;

    public function __construct() {
        $this->model = new Playlist();
    }

    public function index($params) {
        $search = $params['s'] ?? null;
        return $this->model->getAll($search);
    }

    public function show($id) {
        $playlist = $this->model->getById($id);
        if (!$playlist) {
            http_response_code(404);
            return ['error' => 'Playlist not found'];
        }
        return $playlist;
    }

    public function create($data) {
        if (empty($data['name'])) {
            http_response_code(400);
            return ['error' => 'Name is required'];
        }
        return $this->model->create($data['name']);
    }

    public function addTrack($playlistId, $trackId) {
        if ($this->model->addTrack($playlistId, $trackId)) {
            return ['success' => true];
        }
        http_response_code(400);
        return ['error' => 'Could not add track to playlist'];
    }

    public function removeTrack($playlistId, $trackId) {
        if ($this->model->removeTrack($playlistId, $trackId)) {
            return ['success' => true];
        }
        http_response_code(400);
        return ['error' => 'Could not remove track from playlist'];
    }

    public function delete($id) {
        if (!$this->model->delete($id)) {
            http_response_code(400);
            return ['error' => 'Cannot delete playlist with tracks or playlist not found'];
        }
        return ['success' => true];
    }
}