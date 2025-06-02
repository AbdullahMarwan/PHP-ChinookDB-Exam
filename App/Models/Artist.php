<?php
require_once __DIR__ . '/../../Config/database.php';

class Artist {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll($search = null) {
        try {
            if ($search) {
                $stmt = $this->pdo->prepare("SELECT * FROM Artist WHERE Name LIKE ?");
                $stmt->execute(['%' . $search . '%']);
            } else {
                $stmt = $this->pdo->query("SELECT * FROM Artist");
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Artist WHERE ArtistId = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }

    // GET /artists/<artist_id>/albums
    public function getAlbums($artistId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Album WHERE ArtistId = ?");
            $stmt->execute([$artistId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }

    // POST /artists
    public function create($name) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Artist (Name) VALUES (?)");
            $stmt->execute([$name]);
            return $this->getById($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }

    // DELETE /artists/<artist_id>
    public function delete($id) {
        try {
            // Only delete if artist has no albums
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Album WHERE ArtistId = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                return false;
            }
            $stmt = $this->pdo->prepare("DELETE FROM Artist WHERE ArtistId = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['error' => 'Database error'];
        }
    }
}