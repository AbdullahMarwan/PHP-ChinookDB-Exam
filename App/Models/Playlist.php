<?php
require_once __DIR__ . '/../../Config/database.php';

class Playlist {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll($search = null) {
        if ($search) {
            $stmt = $this->pdo->prepare("SELECT * FROM Playlist WHERE Name LIKE ?");
            $stmt->execute(['%' . $search . '%']);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM Playlist");
        }
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Playlist WHERE PlaylistId = ?");
        $stmt->execute([$id]);
        $playlist = $stmt->fetch();
        if ($playlist) {
            $playlist['Tracks'] = $this->getTracks($id);
        }
        return $playlist;
    }

    public function create($name) {
        $stmt = $this->pdo->prepare("INSERT INTO Playlist (Name) VALUES (?)");
        $stmt->execute([$name]);
        return $this->getById($this->pdo->lastInsertId());
    }

    public function addTrack($playlistId, $trackId) {
        $stmt = $this->pdo->prepare("INSERT INTO PlaylistTrack (PlaylistId, TrackId) VALUES (?, ?)");
        return $stmt->execute([$playlistId, $trackId]);
    }

    public function removeTrack($playlistId, $trackId) {
        $stmt = $this->pdo->prepare("DELETE FROM PlaylistTrack WHERE PlaylistId = ? AND TrackId = ?");
        return $stmt->execute([$playlistId, $trackId]);
    }

    public function delete($id) {
        // Only delete if no tracks
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM PlaylistTrack WHERE PlaylistId = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) return false;
        $stmt = $this->pdo->prepare("DELETE FROM Playlist WHERE PlaylistId = ?");
        return $stmt->execute([$id]);
    }

    public function getTracks($playlistId) {
        $stmt = $this->pdo->prepare(
            "SELECT Track.* FROM PlaylistTrack JOIN Track ON PlaylistTrack.TrackId = Track.TrackId WHERE PlaylistTrack.PlaylistId = ?"
        );
        $stmt->execute([$playlistId]);
        return $stmt->fetchAll();
    }
}