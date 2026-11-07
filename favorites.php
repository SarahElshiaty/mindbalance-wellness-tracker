<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$successMsg = '';

// Handle removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
  $remove_id = (int) $_POST['remove_id'];
  $stmt = $pdo->prepare("DELETE FROM favorites WHERE id = ? AND user_id = ?");
  if ($stmt->execute([$remove_id, $user_id])) {
    $successMsg = "Tip removed from favorites successfully.";
  }
}

// Fetch favorites
$stmt = $pdo->prepare("SELECT id, tip_title AS title, tip_text AS text, icon FROM favorites WHERE user_id = ?");
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your Favorite Tips - MindBalance</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
  <style>
    body.dark-mode {
      background-color: #1e1e1e;
      color: #f0f0f0;
    }
    body.dark-mode .navbar,
    body.dark-mode footer {
      background-color: #2b2b2b !important;
    }
    body.dark-mode .nav-link,
    body.dark-mode .navbar-brand,
    body.dark-mode footer,
    body.dark-mode footer a,
    body.dark-mode h1,
    body.dark-mode h2,
    body.dark-mode p {
      color: #f0f0f0 !important;
    }
    .dark-toggle {
      cursor: pointer;
      font-size: 1.3rem;
      background: none;
      border: none;
      color: inherit;
      padding-left: 10px;
      transition: color 0.3s;
    }
    .dark-toggle:hover {
      color: #a3b18a;
    }
    .tip-card {
      border-radius: 12px;
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      position: relative;
    }
    .remove-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background: none;
      border: none;
      color: #dc3545;
      font-size: 1.2rem;
    }
    .remove-btn:hover {
      color: #a71d2a;
    }
    h2 {
      font-family: 'Playfair Display', serif;
    }
    body.dark-mode .tip-card {
      background: linear-gradient(135deg, #2e2f30, #3a3b3c);
      color: #f0f0f0;
    }
    body.dark-mode .tip-card h5,
    body.dark-mode .tip-card p {
      color: #f0f0f0;
    }
  </style>
</head>
<body>
<script>
  if (localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
  }
</script>

<header class="bg-light border-bottom mb-4 fixed-navbar">
  <nav class="navbar navbar-expand-lg navbar-light container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
      <img src="assets/images/logo.png" alt="MindBalance Logo" width="28" class="me-2">MindBalance
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="tracker.php">Tracker</a></li>
        <li class="nav-item"><a class="nav-link" href="history.php">History</a></li>
        <li class="nav-item"><a class="nav-link" href="tips.php">Wellness Tips</a></li>
        <li class="nav-item"><a class="nav-link active" href="favorites.php">Favorites</a></li>
        <li class="nav-item"><a class="nav-link active" href="conditions.php">Conditions</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item me-2 d-flex align-items-center text-success">Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</li>
          <li class="nav-item"><a class="btn btn-outline-danger" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-primary ms-3" href="signin.php">Sign In</a></li>
        <?php endif; ?>
      </ul>
      <button class="dark-toggle" onclick="toggleDarkMode()" aria-label="Toggle dark mode">🌙</button>
    </div>
  </nav>
</header>

<main class="container my-5">
  <h2 class="text-center mb-4">Your Favorite Wellness Tips</h2>
  <?php if (!empty($successMsg)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($successMsg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (count($favorites) > 0): ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($favorites as $fav): ?>
        <div class="col">
          <div class="tip-card h-100">
            <h5><span class="material-icons"><?= htmlspecialchars($fav['icon']) ?></span> <?= htmlspecialchars($fav['title']) ?></h5>
            <p><?= htmlspecialchars($fav['text']) ?></p>
            <form method="POST" class="mt-2">
              <input type="hidden" name="remove_id" value="<?= $fav['id'] ?>">
              <button type="submit" class="remove-btn" title="Remove from Favorites">&#10006;</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-center text-muted">You haven't added any tips to your favorites yet.</p>
  <?php endif; ?>
</main>

<footer class="bg-dark text-light pt-4 pb-3 mt-5">
  <div class="container">
    <div class="row">
      <div class="col-md-6 mb-3">
        <h5 class="text-uppercase">MindBalance</h5>
        <p style="font-size: 0.95rem;">A wellness platform built to help you better understand and manage your mental health journey through tracking, tips, and insights.</p>
      </div>
      <div class="col-md-3">
        <h6>Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-light">Home</a></li>
          <li><a href="tracker.php" class="text-light">Tracker</a></li>
          <li><a href="tips.php" class="text-light">Wellness Tips</a></li>
          <li><a href="history.php" class="text-light">Mood History</a></li>
          <li><a href="contact.php" class="text-light">Contact Us</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h6>Contact</h6>
        <p>Email: <a href="mailto:support@mindbalance.com" class="text-light">support@mindbalance.com</a></p>
      </div>
    </div>
    <hr class="bg-light">
    <p class="text-center mb-0">&copy; 2025 MindBalance. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
  }

  document.addEventListener('DOMContentLoaded', function () {
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
    }
  });
</script>
</body>
</html>
