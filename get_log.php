<?php
session_start();
require __DIR__ . '/db_connect.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }
$id=filter_input(INPUT_GET,'log_id',FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit; }
$stmt=$pdo->prepare('SELECT log_id,mood,note,log_date FROM mood_logs WHERE log_id=? AND user_id=?');
$stmt->execute([$id,$_SESSION['user_id']]);
$log=$stmt->fetch();
if (!$log) { http_response_code(404); exit; }
echo json_encode($log);
