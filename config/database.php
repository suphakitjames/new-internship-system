<?php
// config/database.php

$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'internship_system';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

$maxRetries = 5;
$retryDelay = 2; // seconds
$attempt = 0;
$connected = false;

while ($attempt < $maxRetries && !$connected) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $connected = true;
    } catch (PDOException $e) {
        $attempt++;
        if ($attempt >= $maxRetries) {
            die("Connection failed after $maxRetries attempts: " . $e->getMessage());
        }
        sleep($retryDelay);
    }
}

// Security: Disable error display in production
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error.log');
