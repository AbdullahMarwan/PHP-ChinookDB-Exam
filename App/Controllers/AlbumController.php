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
        $search = $params['s'] ?? null;
        return $this->model->getAll($search);
    }

    // GET /albums/<album_id>
    public function show($id) {
        $album = $this->model->getById($id);
        if (!$album) {
            http_response_code(404);
            return ['error' => 'Album not found'];
        }
        return $album;
    }

    // GET /albums/<album_id>/tracks
    public function tracks($albumId) {
        return $this->model->getTracks($albumId);
    }

    // POST /albums
    public function create($data) {
        if (empty($data['title']) || empty($data['artist_id'])) {
            http_response_code(400);
            return ['error' => 'Title and artist_id are required'];
        }
        return $this->model->create($data['title'], $data['artist_id']);
    }

    // PUT /albums/<album_id> (use POST for PUT as per assignment)
    public function update($id, $data) {
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
    }

    // DELETE /albums/<album_id>
    public function delete($id) {
        if (!$this->model->delete($id)) {
            http_response_code(400);
            return ['error' => 'Cannot delete album with tracks or album not found'];
        }
        return ['success' => true];
    }
}