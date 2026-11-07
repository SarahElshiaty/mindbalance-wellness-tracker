<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php');
  exit();
}

// Handle deletion
if (isset($_GET['delete'])) {
  $logId = (int) $_GET['delete'];
  $stmt = $pdo->prepare("DELETE FROM mood_logs WHERE log_id = ?");
  $stmt->execute([$logId]);
  header("Location: manage_logs.php");
  exit();
}

// Fetch logs with user names
$stmt = $pdo->query("SELECT m.log_id, u.name AS username, m.mood, m.note, m.log_date 
                     FROM mood_logs m 
                     JOIN users u ON m.user_id = u.user_id 
                     ORDER BY m.log_date DESC");
$logs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Mood Logs - MindBalance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <li class="nav-item"><a class="nav-link" href="manage_tips.php">Manage Tips</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_logs.php">Manage Logs</a></li>
        <li class="nav-item"><a class="nav-link" href="admin.php">Manage Contacts</a></li>
        <li class="nav-item"><a class="btn btn-outline-danger" href="logout.php">Logout</a></li>
      </ul>
      <button class="dark-toggle" onclick="toggleDarkMode()" aria-label="Toggle dark mode">🌙</button>
    </div>
  </nav>
</header>

<main class="container my-5">
  <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif;">Manage Mood Logs</h2>

  <?php if (count($logs) > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>User</th>
            <th>Mood</th>
            <th>Note</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($logs as $log): ?>
            <tr>
              <td><?= htmlspecialchars($log['username']) ?></td>
              <td><?= htmlspecialchars($log['mood']) ?></td>
              <td><?= htmlspecialchars($log['note']) ?></td>
              <td><?= htmlspecialchars($log['log_date']) ?></td>
              <td>
                <a href="?delete=<?= $log['log_id'] ?>" onclick="return confirm('Are you sure you want to delete this log?');" class="btn btn-danger btn-sm">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-center text-muted">No mood logs found.</p>
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
    if (document.body.classList.contains('dark-mode')) {
      localStorage.setItem('darkMode', 'enabled');
    } else {
      localStorage.setItem('darkMode', 'disabled');
    }
  }
</script>
</body>
</html>
