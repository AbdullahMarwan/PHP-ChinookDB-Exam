<?php
require_once __DIR__ . '/../Config/database.php';

$db = Database::getInstance()->getConnection();
echo "Database connection successful.";