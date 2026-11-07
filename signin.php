<?php  
session_start();
if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
  echo "<script>alert('Successfully signed in! Redirecting to homepage...'); window.location.href = 'index.php';</script>";
  unset($_SESSION['login_success']);
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In - MindBalance</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Header -->
  <header class="bg-light border-bottom mb-4 fixed-navbar">
    <nav class="navbar navbar-expand-lg navbar-light container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
        <img src="assets/images/logo.png" alt="MindBalance Logo" width="28" height="28" class="me-2">
        MindBalance
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="history.php">History</a></li>
          <li class="nav-item"><a class="nav-link" href="tips.php">Wellness Tips</a></li>
          <li class="nav-item"><a class="nav-link active" href="conditions.php">Conditions</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Sign In Form -->
<main class="container my-5" style="max-width: 500px;">
  <h2 class="text-center mb-4">Sign In</h2>

  <?php
  if (isset($_SESSION['error_login'])) {
    echo '<div class="alert alert-danger text-center">❌ Invalid email or password.</div>';
    unset($_SESSION['error_login']);
  }
  ?>

  <form action="submit_login.php" method="POST">
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Sign In</button>
    </div>
  </form>

  <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a></p>
</main>


  <!-- Footer -->
  <footer class="bg-dark text-light py-4 mt-5">
    <div class="container text-center">
      <p>Contact us: <a href="mailto:support@mindbalance.com" class="text-light">support@mindbalance.com</a></p>
      <p>&copy; 2025 MindBalance. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
