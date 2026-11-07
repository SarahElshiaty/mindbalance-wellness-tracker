<?php
$host = getenv('MINDBALANCE_DB_HOST') ?: 'localhost';
$db = getenv('MINDBALANCE_DB_NAME') ?: 'mindbalance_db';
$user = getenv('MINDBALANCE_DB_USER') ?: 'root';
$pass = getenv('MINDBALANCE_DB_PASSWORD');
$pass = $pass === false ? '' : $pass;
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false];
try {
    $pdo = new PDO($dsn,$user,$pass,$options);
} catch (PDOException $e) {
    error_log('MindBalance database connection failed: '.$e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check local settings.');
}
