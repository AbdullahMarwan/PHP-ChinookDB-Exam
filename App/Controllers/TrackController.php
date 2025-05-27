<?php
// filepath: /Applications/MAMP/htdocs/PHP-ChinookDB-Exam/App/Controllers/TrackController.php
require_once __DIR__ . '/../Models/Track.php';

class TrackController {
    private $model;

    public function __construct() {
        $this->model = new Track();
    }

    public function index($params) {
        $search = $params['s'] ?? null;
        $composer = $params['composer'] ?? null;
        return $this->model->getAll($search, $composer);
    }

    public function show($id) {
        $track = $this->model->getById($id);
        if (!$track) {
            http_response_code(404);
            return ['error' => 'Track not found'];
        }
        return $track;
    }

    public function create($data) {
        $required = ['name', 'album_id', 'media_type_id', 'genre_id', 'composer', 'milliseconds', 'bytes', 'unit_price'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                return ['error' => "$field is required"];
            }
        }
        return $this->model->create($data);
    }

    public function update($id, $data) {
        if (empty($data)) {
            http_response_code(400);
            return ['error' => 'No data to update'];
        }
        $updated = $this->model->update($id, $data);
        if (!$updated) {
            http_response_code(404);
            return ['error' => 'Track not found or nothing to update'];
        }
        return $updated;
    }

    public function delete($id) {
        if (!$this->model->delete($id)) {
            http_response_code(400);
            return ['error' => 'Cannot delete track (may belong to a playlist or not found)'];
        }
        return ['success' => true];
    }
}