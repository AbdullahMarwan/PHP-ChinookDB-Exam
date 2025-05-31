<?php
// filepath: Config/database.php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Database {
    private static $instance = null;
    private $pdo;

    private function getEnvVar($key, $default = null) {
        // Prefer getenv() for webapp/server, fallback to $_ENV for local/CLI
        $value = getenv($key);
        if ($value === false && isset($_ENV[$key])) {
            $value = $_ENV[$key];
        }
        return $value !== false && $value !== null ? $value : $default;
    }

    private function __construct() {
        $host = $this->getEnvVar('DB_HOST', 'localhost');
        $port = $this->getEnvVar('DB_PORT', 3306);
        $db   = $this->getEnvVar('DB_NAME', '');
        $user = $this->getEnvVar('DB_USER', '');
        $pass = $this->getEnvVar('DB_PASS', '');
        $charset = $this->getEnvVar('DB_CHARSET', 'utf8mb4');

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}