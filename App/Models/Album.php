<?php
require_once __DIR__ . '/../../Config/database.php';

class Album {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // GET /albums and /albums?s=
    public function getAll($search = null) {
        if ($search) {
            $stmt = $this->pdo->prepare(
                "SELECT Album.*, Artist.Name AS ArtistName FROM Album JOIN Artist ON Album.ArtistId = Artist.ArtistId WHERE Album.Title LIKE ?"
            );
            $stmt->execute(['%' . $search . '%']);
        } else {
            $stmt = $this->pdo->query(
                "SELECT Album.*, Artist.Name AS ArtistName FROM Album JOIN Artist ON Album.ArtistId = Artist.ArtistId"
            );
        }
        return $stmt->fetchAll();
    }

    // GET /albums/<album_id>
    public function getById($id) {
        $stmt = $this->pdo->prepare(
            "SELECT Album.*, Artist.Name AS ArtistName FROM Album JOIN Artist ON Album.ArtistId = Artist.ArtistId WHERE Album.AlbumId = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // GET /albums/<album_id>/tracks
    public function getTracks($albumId) {
        $stmt = $this->pdo->prepare(
            "SELECT Track.*, MediaType.Name AS MediaType, Genre.Name AS Genre
             FROM Track
             JOIN MediaType ON Track.MediaTypeId = MediaType.MediaTypeId
             JOIN Genre ON Track.GenreId = Genre.GenreId
             WHERE Track.AlbumId = ?"
        );
        $stmt->execute([$albumId]);
        return $stmt->fetchAll();
    }

    // POST /albums
    public function create($title, $artistId) {
        $stmt = $this->pdo->prepare("INSERT INTO Album (Title, ArtistId) VALUES (?, ?)");
        $stmt->execute([$title, $artistId]);
        return $this->getById($this->pdo->lastInsertId());
    }

    // PUT /albums/<album_id>
    public function update($id, $title = null, $artistId = null) {
        $fields = [];
        $params = [];
        if ($title !== null) {
            $fields[] = "Title = ?";
            $params[] = $title;
        }
        if ($artistId !== null) {
            $fields[] = "ArtistId = ?";
            $params[] = $artistId;
        }
        if (empty($fields)) return false;
        $params[] = $id;
        $sql = "UPDATE Album SET " . implode(', ', $fields) . " WHERE AlbumId = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->getById($id);
    }

    // DELETE /albums/<album_id>
    public function delete($id) {
        // Only delete if no tracks
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Track WHERE AlbumId = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) return false;
        $stmt = $this->pdo->prepare("DELETE FROM Album WHERE AlbumId = ?");
        return $stmt->execute([$id]);
    }

    // GET /albums/artist/<artist_id>
    public function getByArtistId($artistId) {
        $stmt = $this->pdo->prepare(
            "SELECT Album.*, Artist.Name AS ArtistName FROM Album JOIN Artist ON Album.ArtistId = Artist.ArtistId WHERE Album.ArtistId = ?"
        );
        $stmt->execute([$artistId]);
        return $stmt->fetchAll();
    }
}