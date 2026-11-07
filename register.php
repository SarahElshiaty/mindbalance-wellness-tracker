<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']    ?? '');
    $email    = trim($_POST['email']   ?? '');
    $password = $_POST['password']     ?? '';
    $confirm  = $_POST['confirm']      ?? '';

    // Validate
    if (!$name || !$email || !$password || !$confirm) {
        $_SESSION['flash_message']      = 'Please fill in all fields.';
        $_SESSION['flash_message_type'] = 'danger';
        header('Location: register.php');
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_message']      = 'Please enter a valid email address.';
        $_SESSION['flash_message_type'] = 'danger';
        header('Location: register.php');
        exit;
    }
    if ($password !== $confirm) {
        $_SESSION['flash_message']      = 'Passwords do not match.';
        $_SESSION['flash_message_type'] = 'danger';
        header('Location: register.php');
        exit;
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['flash_message']      = 'Email already registered. Please use another.';
        $_SESSION['flash_message_type'] = 'danger';
        header('Location: register.php');
        exit;
    }

    // Insert new user
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $insert = $pdo->prepare("
      INSERT INTO users (name, email, password, role, created_at)
      VALUES (?, ?, ?, 'user', NOW())
    ");
    $insert->execute([$name, $email, $hashed]);

    // Success flash
    $_SESSION['flash_message']      = '🎉 Registration successful! You may now sign in.';
    $_SESSION['flash_message_type'] = 'success';
    header('Location: register.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - MindBalance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <style>
    body.dark-mode { background: #1e1e1e; color: #f0f0f0; }
    h2 { font-family: 'Playfair Display', serif; }
  </style>
</head>
<body class="<?php echo (isset($_COOKIE['darkMode']) && $_COOKIE['darkMode']==='on') ? 'dark-mode' : '' ?>">
<header class="bg-light border-bottom mb-4">
  <nav class="navbar navbar-expand-lg navbar-light container">
    <a class="navbar-brand fw-bold" href="index.php">
      <img src="assets/images/logo.png" width="30" class="me-2">MindBalance
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="tracker.php">Tracker</a></li>
        <li class="nav-item"><a class="nav-link" href="tips.php">Tips</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="signin.php">Sign In</a></li>
      </ul>
      <button class="dark-toggle btn btn-link" onclick="toggleDarkMode()">🌙</button>
    </div>
  </nav>
</header>

<main class="container my-5" style="max-width:500px;">
  <h2 class="text-center mb-4">Create Your Account</h2>

  <!-- FLASH MESSAGE -->
  <?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_message_type']) ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['flash_message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_message_type']); ?>
  <?php endif; ?>

  <form method="POST" action="register.php">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" minlength="6" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Confirm Password</label>
      <input type="password" name="confirm" class="form-control" required>
    </div>
    <div class="d-grid">
      <button class="btn btn-success">Register</button>
    </div>
    <p class="mt-3 text-center">
      Already have an account? <a href="signin.php">Sign in here</a>
    </p>
  </form>
</main>

<footer class="bg-dark text-light py-4 mt-5">
  <div class="container text-center">
    <p>Questions? Email <a href="mailto:support@mindbalance.com" class="text-light">support@mindbalance.com</a></p>
    <p class="mb-0">&copy; 2025 MindBalance. All rights reserved.</p>
  </div>
</footer>

<script>
function toggleDarkMode(){
  document.body.classList.toggle('dark-mode');
  document.cookie = 'darkMode=' + (document.body.classList.contains('dark-mode')?'on':'off') + ';path=/';
}
if (document.cookie.includes('darkMode=on')) {
  document.body.classList.add('dark-mode');
}
</script>
</body>
</html>
