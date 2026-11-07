<?php
session_start();
require 'db_connect.php';
require 'csrf.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php');
  exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_csrf();
  $title = trim($_POST['title']);
  $text = trim($_POST['text']);
  $category = trim($_POST['category']);
  $icon = trim($_POST['icon']);

  if ($title && $text && $category && $icon) {
    $stmt = $pdo->prepare("INSERT INTO tips (title, text, category, icon) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $text, $category, $icon]);
    header("Location: manage_tips.php");
    exit();
  } else {
    $error = "Please fill in all fields.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Tip - MindBalance</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="bg-light border-bottom mb-4">
  <nav class="navbar navbar-expand-lg navbar-light container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="admin.php">
      <img src="assets/images/logo.png" alt="MindBalance Logo" width="28" class="me-2"> Admin Dashboard
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_tips.php">Manage Tips</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_logs.php">Manage Logs</a></li>
        <li class="nav-item"><a class="nav-link" href="admin.php">Manage Contacts</a></li>
        <li class="nav-item"><a class="btn btn-outline-danger" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </nav>
</header>

<main class="container my-5">
  <h2 class="text-center mb-4">Add New Wellness Tip</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"> <?= htmlspecialchars($error) ?> </div>
  <?php endif; ?>

  <form method="POST" class="mx-auto" style="max-width: 600px;">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES) ?>">
    <div class="mb-3">
      <label for="title" class="form-label">Tip Title</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
      <label for="text" class="form-label">Tip Text</label>
      <textarea class="form-control" id="text" name="text" rows="3" required></textarea>
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Category (e.g., depression, ocd)</label>
      <input type="text" class="form-control" id="category" name="category" required>
    </div>
    <div class="mb-3">
      <label for="icon" class="form-label">Material Icon Name (e.g., self_improvement)</label>
      <input type="text" class="form-control" id="icon" name="icon" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Tip</button>
    <a href="manage_tips.php" class="btn btn-secondary ms-2">Cancel</a>
  </form>
</main>

<footer class="bg-dark text-light py-4 mt-5">
  <div class="container text-center">
    <p class="mb-0">&copy; 2025 MindBalance Admin. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
    }
  });

  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    if (document.body.classList.contains('dark-mode')) {
      localStorage.setItem('darkMode', 'enabled');
    } else {
      localStorage.setItem('darkMode', 'disabled');
    }
  }
</script>
</body>
</html>