<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php');
  exit();
}

if (!isset($_GET['id'])) {
  header('Location: manage_tips.php');
  exit();
}

$tip_id = (int) $_GET['id'];

// Fetch the current tip data
$stmt = $pdo->prepare("SELECT * FROM tips WHERE id = ?");
$stmt->execute([$tip_id]);
$tip = $stmt->fetch();

if (!$tip) {
  echo "<script>alert('Tip not found.'); window.location.href='manage_tips.php';</script>";
  exit();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'] ?? '';
  $text = $_POST['text'] ?? '';
  $category = $_POST['category'] ?? '';
  $icon = $_POST['icon'] ?? '';

  if ($title && $text && $category && $icon) {
    $stmt = $pdo->prepare("UPDATE tips SET title = ?, text = ?, category = ?, icon = ? WHERE id = ?");
    $stmt->execute([$title, $text, $category, $icon, $tip_id]);
    echo "<script>alert('Tip updated successfully.'); window.location.href='manage_tips.php';</script>";
    exit();
  } else {
    $error = "All fields are required.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Tip - MindBalance</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="bg-light border-bottom mb-4">
  <nav class="navbar navbar-expand-lg navbar-light container">
    <a class="navbar-brand fw-bold" href="admin.php">
      <img src="assets/images/logo.png" alt="Logo" width="28" class="me-2">Admin Panel
    </a>
  </nav>
</header>

<main class="container my-5" style="max-width: 600px;">
  <h2 class="text-center mb-4">Edit Wellness Tip</h2>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($tip['title']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="text" class="form-label">Text</label>
      <textarea class="form-control" id="text" name="text" rows="3" required><?= htmlspecialchars($tip['text']) ?></textarea>
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($tip['category']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="icon" class="form-label">Material Icon</label>
      <input type="text" class="form-control" id="icon" name="icon" value="<?= htmlspecialchars($tip['icon']) ?>" required>
    </div>
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Update Tip</button>
    </div>
  </form>
</main>

<footer class="bg-dark text-light py-4 mt-5">
  <div class="container text-center">
    <p class="mb-0">&copy; 2025 MindBalance Admin. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>