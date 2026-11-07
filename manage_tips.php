<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php');
  exit();
}

// Handle deletion
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $stmt = $pdo->prepare("DELETE FROM tips WHERE id = ?");
  $stmt->execute([$id]);
  header("Location: manage_tips.php");
  exit();
}

// Fetch all tips
$stmt = $pdo->query("SELECT * FROM tips ORDER BY id DESC");
$tips = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Tips - MindBalance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="bg-light border-bottom mb-4">
  <nav class="navbar navbar-expand-lg navbar-light container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="admin.php">
      <img src="assets/images/logo.png" alt="Logo" width="28" class="me-2"> Admin Dashboard
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_tips.php">Manage Tips</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_logs.php">Manage Logs</a></li>
        <li class="nav-item"><a class="nav-link" href="admin.php">Manage Contacts</a></li>
        <li class="nav-item"><a class="btn btn-outline-danger" href="logout.php">Logout</a></li>
      </ul>
      <button class="dark-toggle" onclick="toggleDarkMode()" aria-label="Toggle dark mode">🌙</button>
    </div>
  </nav>
</header>

<main class="container my-5">
  <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif;">Manage Wellness Tips</h2>
  <div class="text-end mb-3">
    <a href="add_tip.php" class="btn btn-success">+ Add New Tip</a>
  </div>

  <?php if (count($tips) > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Text</th>
            <th>Category</th>
            <th>Icon</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tips as $tip): ?>
            <tr>
              <td><?= htmlspecialchars($tip['title']) ?></td>
              <td><?= htmlspecialchars($tip['text']) ?></td>
              <td><?= htmlspecialchars($tip['category']) ?></td>
              <td><?= htmlspecialchars($tip['icon']) ?></td>
              <td>
                <a href="edit_tip.php?id=<?= $tip['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?delete=<?= $tip['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this tip?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-center text-muted">No tips found.</p>
  <?php endif; ?>
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
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
  }
</script>
</body>
</html>
