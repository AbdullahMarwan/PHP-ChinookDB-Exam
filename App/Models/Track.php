<?php
require_once __DIR__ . '/../../Config/database.php';

class Track {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll($search = null, $composer = null) {
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
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare(
            "SELECT Track.*, MediaType.Name AS MediaType, Genre.Name AS Genre FROM Track
             JOIN MediaType ON Track.MediaTypeId = MediaType.MediaTypeId
             JOIN Genre ON Track.GenreId = Genre.GenreId
             WHERE Track.TrackId = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Track (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['name'], $data['album_id'], $data['media_type_id'], $data['genre_id'],
            $data['composer'], $data['milliseconds'], $data['bytes'], $data['unit_price']
        ]);
        return $this->getById($this->pdo->lastInsertId());
    }

    public function update($id, $data) {
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
    }

    public function delete($id) {
        // Only delete if not in any playlist
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM PlaylistTrack WHERE TrackId = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) return false;
        $stmt = $this->pdo->prepare("DELETE FROM Track WHERE TrackId = ?");
        return $stmt->execute([$id]);
    }
}