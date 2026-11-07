<?php
session_start();
require 'db_connect.php'; // assumes you have PDO connection set as $pdo

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['title'], $data['text'], $data['category'], $data['icon'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing data']);
  exit;
}

$stmt = $pdo->prepare("INSERT INTO favorites (user_id, tip_title, tip_text, category, icon) VALUES (?, ?, ?, ?, ?)");
$success = $stmt->execute([
  $_SESSION['user_id'],
  $data['title'],
  $data['text'],
  $data['category'],
  $data['icon']
]);

echo json_encode(['success' => $success]);
