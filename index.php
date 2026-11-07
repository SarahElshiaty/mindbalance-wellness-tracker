<?php  
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MindBalance - Home</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    .carousel-inner img {
      max-height: 450px;
      object-fit: cover;
    }
    body.dark-mode {
      background-color: #1e1e1e;
      color: #f0f0f0;
    }
    body.dark-mode .navbar,
    body.dark-mode footer,
    body.dark-mode .carousel-caption {
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
  </style>
</head>
<body>
  <!-- Header -->
  <header class="bg-light border-bottom mb-4 fixed-navbar">
    <nav class="navbar navbar-expand-lg navbar-light container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
        <img src="assets/images/logo.png" alt="MindBalance Logo" width="30" class="me-2">
        MindBalance
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="tracker.php">Tracker</a></li>
          <li class="nav-item"><a class="nav-link" href="history.php">History</a></li>
          <li class="nav-item"><a class="nav-link" href="tips.php">Wellness Tips</a></li>
          <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="favorites.php">Favorites</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link active" href="conditions.php">Conditions</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item me-2 d-flex align-items-center text-success">
              Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!
            </li>
            <li class="nav-item">
              <a class="btn btn-outline-danger" href="logout.php">Logout</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="btn btn-primary ms-3" href="signin.php?redirect=index.php">Sign In</a>
            </li>
          <?php endif; ?>
        </ul>
        <button class="dark-toggle" onclick="toggleDarkMode()" aria-label="Toggle dark mode">🌙</button>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="container py-5">
    <div class="text-center mb-5" style="font-family: 'Playfair Display', serif;">
      <h1 class="display-5 fw-bold mb-3">Track Your Mind. Improve Your Life.</h1>
      <p class="lead mb-4" style="font-family: 'Open Sans', sans-serif;">
        MindBalance helps you log your moods, sleep, and wellbeing over time, making self-care easier and smarter.
      </p>
    </div>

    <div id="mindCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
      <div class="carousel-inner rounded shadow-sm">
        <div class="carousel-item active" data-bs-interval="4000">
          <img src="assets/images/health.jpg" class="d-block w-100" alt="Mental Health">
          <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
            <h2 class="h3 fw-bold text-white">MindBalance</h2>
            <p>Your companion for tracking moods &amp; nurturing mental wellness.</p>
          </div>
        </div>
        <div class="carousel-item" data-bs-interval="4000">
          <img src="assets/images/mentalhealth.jpg" class="d-block w-100" alt="Wellness">
          <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
            <h2 class="h4 fw-bold text-white">Daily Reflections</h2>
            <p>Log feelings, monitor patterns, and celebrate progress.</p>
          </div>
        </div>
        <div class="carousel-item" data-bs-interval="4000">
          <img src="assets/images/green.jpg" class="d-block w-100" alt="Mindfulness">
          <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
            <h2 class="h4 fw-bold text-white">Science-Backed Tips</h2>
            <p>Discover expert strategies to keep your mind in balance.</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#mindCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#mindCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <div class="row align-items-center gy-4">
      <div class="col-lg-6 text-center text-lg-start">
        <h1 class="display-5 mb-3" style="font-family: 'Playfair Display', serif;">
          Track Your Mood &amp; Wellness
        </h1>
        <p class="lead" style="font-family: 'Open Sans', sans-serif;">
          MindBalance helps you log daily moods, gain insights, and cultivate healthy habits—anytime, anywhere.
        </p>
        <a href="signin.php?redirect=index.php" class="btn btn-primary btn-lg mt-2">Get Started</a>
      </div>
      <div class="col-lg-6">
        <img src="assets/images/calm.jpg" class="img-fluid rounded shadow" alt="Journaling">
      </div>
    </div>
  </section>

  <!-- Condition Cards -->
  <section class="container my-5">
    <h2 class="text-center fw-bold mb-3" style="font-family: 'Playfair Display', serif;">
      Mental Health Tracker
    </h2>
    <p class="text-center text-muted mb-4" style="font-family: 'Open Sans', sans-serif;">
      Can be especially helpful for people managing:
    </p>
    <div class="row g-4">
      <?php
        $conditions = [
          "Autism",
          "Mental health disorder",
          "Depression",
          "Bipolar disorder",
          "OCD",
          "PTSD",
          "Anxiety disorder",
          "ADHD"
        ];
        foreach ($conditions as $condition):
          $slug = urlencode($condition);
      ?>
        <div class="col-6 col-md-3">
          <a href="conditions.php?condition=<?= $slug ?>" class="text-decoration-none">
            <div class="condition-card d-flex align-items-center justify-content-center text-white text-center rounded p-3 h-100" style="font-family: 'Playfair Display', serif;">
              <span><?= htmlspecialchars($condition) ?></span>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-light pt-4 pb-3 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6 mb-3">
          <h5 class="text-uppercase">MindBalance</h5>
          <p style="font-size: 0.95rem;">
            A wellness platform built to help you better understand and manage your mental health journey.
          </p>
        </div>
        <div class="col-md-3">
          <h6>Quick Links</h6>
          <ul class="list-unstyled">
            <li><a href="index.php" class="text-light">Home</a></li>
            <li><a href="tracker.php" class="text-light">Tracker</a></li>
            <li><a href="history.php" class="text-light">Mood History</a></li>
            <li><a href="tips.php" class="text-light">Wellness Tips</a></li>
            <li><a href="conditions.php" class="text-light">Conditions</a></li>
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
