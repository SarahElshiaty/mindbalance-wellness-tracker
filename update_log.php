<?php
session_start();
require __DIR__ . '/db_connect.php';
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }
if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit; }
$id=filter_input(INPUT_POST,'log_id',FILTER_VALIDATE_INT);
$mood=trim($_POST['mood']??'');
$note=trim($_POST['note']??'');
$date=trim($_POST['log_date']??'');
if (!$id || $mood==='' || $date==='') { http_response_code(400); exit; }
$stmt=$pdo->prepare('UPDATE mood_logs SET mood=?,note=?,log_date=? WHERE log_id=? AND user_id=?');
$stmt->execute([$mood,$note,$date,$id,$_SESSION['user_id']]);
echo 'success';
