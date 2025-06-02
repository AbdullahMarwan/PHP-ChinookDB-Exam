<?php
// filepath: App/Controllers/AlbumController.php
require_once __DIR__ . '/../Models/Album.php';
require_once __DIR__ . '/../Models/Track.php';

class AlbumController {
    private $model;
    private $trackModel;

    public function __construct() {
        $this->model = new Album();
        $this->trackModel = new Track();
    }

    // GET /albums and /albums?s=
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

    // GET /albums/<album_id>
    public function show($id) {
        try {
            $album = $this->model->getById($id);
            if (!$album) {
                http_response_code(404);
                return ['error' => 'Album not found'];
            }
            return $album;
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    // GET /albums/<album_id>/tracks
    public function tracks($albumId) {
        try {
            return $this->model->getTracks($albumId);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    // POST /albums
    public function create($data) {
        try {
            if (empty($data['title']) || empty($data['artist_id'])) {
                http_response_code(400);
                return ['error' => 'Title and artist_id are required'];
            }
            return $this->model->create($data['title'], $data['artist_id']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    // PUT /albums/<album_id> (use POST for PUT)
    public function update($id, $data) {
        try {
            if (empty($data)) {
                http_response_code(400);
                return ['error' => 'No data to update'];
            }
            $updated = $this->model->update($id, $data['title'] ?? null, $data['artist_id'] ?? null);
            if (!$updated) {
                http_response_code(404);
                return ['error' => 'Album not found or nothing to update'];
            }
            return $updated;
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    // DELETE /albums/<album_id>
    public function delete($id) {
        try {
            if (!$this->model->delete($id)) {
                http_response_code(400);
                return ['error' => 'Cannot delete album with tracks or album not found'];
            }
            return ['success' => true];
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }
}