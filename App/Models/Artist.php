<?php
require_once __DIR__ . '/../../Config/database.php';

class Artist {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll($search = null) {
        if ($search) {
            $stmt = $this->pdo->prepare("SELECT * FROM Artist WHERE Name LIKE ?");
            $stmt->execute(['%' . $search . '%']);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM Artist");
        }
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Artist WHERE ArtistId = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // GET /artists/<artist_id>/albums
    public function getAlbums($artistId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Album WHERE ArtistId = ?");
        $stmt->execute([$artistId]);
        return $stmt->fetchAll();
    }

    // POST /artists
    public function create($name) {
        $stmt = $this->pdo->prepare("INSERT INTO Artist (Name) VALUES (?)");
        $stmt->execute([$name]);
        return $this->getById($this->pdo->lastInsertId());
    }

    // DELETE /artists/<artist_id>
    public function delete($id) {
        // Only delete if artist has no albums
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Album WHERE ArtistId = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }
        $stmt = $this->pdo->prepare("DELETE FROM Artist WHERE ArtistId = ?");
        return $stmt->execute([$id]);
    }
}