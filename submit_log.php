<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('Please log in to submit logs.'); window.location.href='signin.php';</script>";
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $mood = $_POST['mood'] ?? '';
  $note = $_POST['note'] ?? '';
  $log_date = $_POST['log_date'] ?? date('Y-m-d');

  if ($mood && $log_date) {
    $stmt = $pdo->prepare("INSERT INTO mood_logs (user_id, mood, note, log_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $mood, $note, $log_date]);
    echo "<script>alert('✅ Mood log submitted.'); window.location.href='history.php';</script>";
  } else {
    echo "<script>alert('Mood and date are required.'); window.history.back();</script>";
  }
} else {
  header("Location: index.php");
  exit();
}
?>
