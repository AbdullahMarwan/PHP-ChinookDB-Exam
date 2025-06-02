<?php
require_once __DIR__ . '/../../Config/database.php';

class Track {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // GET /tracks, /tracks?s=, /tracks?composer=
    public function getAll($search = null, $composer = null) {
        try {
            $sql = "SELECT Track.*, MediaType.Name AS MediaType, Genre.Name AS Genre FROM Track
                    JOIN MediaType ON Track.MediaTypeId = MediaType.MediaTypeId
                    JOIN Genre ON Track.GenreId = Genre.GenreId";
            $params = [];
            $where = [];
            if ($search) {
                $where[] = "Track.Name LIKE ?";
                $params[] = '%' . $search . '%';
            }
            if ($composer) {
                $where[] = "Track.Composer = ?";
                $params[] = $composer;
            }
            if ($where) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }

    // GET /tracks/<track_id>
    public function getById($id) {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT Track.*, MediaType.Name AS MediaType, Genre.Name AS Genre FROM Track
                 JOIN MediaType ON Track.MediaTypeId = MediaType.MediaTypeId
                 JOIN Genre ON Track.GenreId = Genre.GenreId
                 WHERE Track.TrackId = ?"
            );
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }

    // POST /tracks
    public function create($data) {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO Track (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $data['name'], $data['album_id'], $data['media_type_id'], $data['genre_id'],
                $data['composer'], $data['milliseconds'], $data['bytes'], $data['unit_price']
            ]);
            return $this->getById($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }

    // PUT /tracks/<track_id>
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [];
            foreach (['name', 'album_id', 'media_type_id', 'genre_id', 'composer', 'milliseconds', 'bytes', 'unit_price'] as $field) {
                if (isset($data[$field])) {
                    $fields[] = ucfirst($field) . " = ?";
                    $params[] = $data[$field];
                }
            }
            if (empty($fields)) return false;
            $params[] = $id;
            $sql = "UPDATE Track SET " . implode(', ', $fields) . " WHERE TrackId = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $this->getById($id);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }

    // DELETE /tracks/<track_id>
    public function delete($id) {
        try {
            // Only delete if not in any playlist
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM PlaylistTrack WHERE TrackId = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) return false;
            $stmt = $this->pdo->prepare("DELETE FROM Track WHERE TrackId = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }
}