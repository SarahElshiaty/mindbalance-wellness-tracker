<?php
session_start();
require 'db_connect.php';
require 'csrf.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php');
  exit();
}

// Promote/Demote/Delete Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_csrf();
  if (isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$_POST['delete_id']]);
  } elseif (isset($_POST['promote_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE user_id = ?");
    $stmt->execute([$_POST['promote_id']]);
  } elseif (isset($_POST['demote_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET role = 'user' WHERE user_id = ?");
    $stmt->execute([$_POST['demote_id']]);
  }
}

$stmt = $pdo->query("SELECT user_id, name, email, role FROM users ORDER BY user_id ASC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="bg-light border-bottom mb-4">
  <nav class="navbar navbar-expand-lg navbar-light container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="admin.php">
      <img src="assets/images/logo.png" alt="Logo" width="28" class="me-2">Admin Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="manage_users.php">Manage Users</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_tips.php">Manage Tips</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_logs.php">Manage Logs</a></li>
        <li class="nav-item"><a class="nav-link" href="admin.php">Manage Contacts</a></li>
        <li class="nav-item"><a class="btn btn-outline-danger" href="logout.php">Logout</a></li>
      </ul>
      <button class="dark-toggle" onclick="toggleDarkMode()" aria-label="Toggle dark mode">🌙</button>
    </div>
  </nav>
</header>

<main class="container my-5">
  <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif;">Manage Users</h2>

  <?php if (count($users) > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['user_id']) ?></td>
              <td><?= htmlspecialchars($user['name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= htmlspecialchars($user['role']) ?></td>
              <td>
                <form method="POST" class="d-inline">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES) ?>">
                  <input type="hidden" name="delete_id" value="<?= $user['user_id'] ?>">
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
                <?php if ($user['role'] === 'user'): ?>
                  <form method="POST" class="d-inline">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES) ?>">
                    <input type="hidden" name="promote_id" value="<?= $user['user_id'] ?>">
                    <button type="submit" class="btn btn-sm btn-success">Promote</button>
                  </form>
                <?php elseif ($user['role'] === 'admin' && $_SESSION['user_id'] !== $user['user_id']): ?>
                  <form method="POST" class="d-inline">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES) ?>">
                    <input type="hidden" name="demote_id" value="<?= $user['user_id'] ?>">
                    <button type="submit" class="btn btn-sm btn-warning">Demote</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-center">No users found.</p>
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
