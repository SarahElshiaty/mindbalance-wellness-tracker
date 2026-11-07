<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  // Get user from DB by email
  $stmt = $pdo->prepare("SELECT user_id, name, password, role FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  // Check if user exists and password is correct
  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['login_success'] = true;

    // Redirect based on role
    if ($user['role'] === 'admin') {
      header("Location: admin.php");
    } else {
      header("Location: index.php");
    }
    exit();
  } else {
    $_SESSION['error_login'] = true;
    header("Location: signin.php");
    exit();
  }
} else {
  header("Location: signin.php");
  exit();
}
?>
