<?php
// filepath: /Applications/MAMP/htdocs/PHP-ChinookDB-Exam/App/Controllers/TrackController.php
require_once __DIR__ . '/../Models/Track.php';

class TrackController {
    private $model;

    public function __construct() {
        $this->model = new Track();
    }

    public function index($params) {
        try {
            $search = $params['s'] ?? null;
            $composer = $params['composer'] ?? null;
            return $this->model->getAll($search, $composer);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    public function show($id) {
        try {
            $track = $this->model->getById($id);
            if (!$track) {
                http_response_code(404);
                return ['error' => 'Track not found'];
            }
            return $track;
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    public function create($data) {
        try {
            $required = ['name', 'album_id', 'media_type_id', 'genre_id', 'composer', 'milliseconds', 'bytes', 'unit_price'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    http_response_code(400);
                    return ['error' => "$field is required"];
                }
            }
            return $this->model->create($data);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }

    public function update($id, $data) {
        try {
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
                return ['error' => 'Cannot delete track (may belong to a playlist or not found)'];
            }
            return ['success' => true];
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            return ['error' => 'Internal server error'];
        }
    }
}