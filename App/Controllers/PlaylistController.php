<?php
// filepath: /Applications/MAMP/htdocs/PHP-ChinookDB-Exam/App/Controllers/PlaylistController.php
require_once __DIR__ . '/../Models/Playlist.php';

class PlaylistController {
    private $model;

    public function __construct() {
        $this->model = new Playlist();
    }

    public function index($params) {
        try {
            $search = $params['s'] ?? null;
            return $this->model->getAll($search);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    public function show($id) {
        try {
            $playlist = $this->model->getById($id);
            if (!$playlist) {
                http_response_code(404);
                return ['error' => 'Playlist not found'];
            }
            return $playlist;
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    public function create($data) {
        try {
            if (empty($data['name'])) {
                http_response_code(400);
                return ['error' => 'Name is required'];
            }
            return $this->model->create($data['name']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    public function addTrack($playlistId, $trackId) {
        try {
            if ($this->model->addTrack($playlistId, $trackId)) {
                return ['success' => true];
            }
            http_response_code(400);
            return ['error' => 'Could not add track to playlist'];
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    public function removeTrack($playlistId, $trackId) {
        try {
            if ($this->model->removeTrack($playlistId, $trackId)) {
                return ['success' => true];
            }
            http_response_code(400);
            return ['error' => 'Could not remove track from playlist'];
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    public function delete($id) {
        try {
            if (!$this->model->delete($id)) {
                http_response_code(400);
                return ['error' => 'Cannot delete playlist with tracks or playlist not found'];
            }
            return ['success' => true];
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }
}