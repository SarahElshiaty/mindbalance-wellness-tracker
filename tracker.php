<?php  
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  $loggedIn = false;
} else {
  $loggedIn = true;
  $user_id = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT mood, note, log_date FROM mood_logs WHERE user_id = ? ORDER BY log_date DESC");
  $stmt->execute([$user_id]);
  $logs = $stmt->fetchAll();
}

$moodSaved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) { header('Location: signin.php'); exit; }
    $mood = $_POST['mood'];
    $note = $_POST['note'];
    $log_date = $_POST['log_date'];
    
    $stmt = $pdo->prepare("INSERT INTO mood_logs (user_id, mood, note, log_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $mood, $note, $log_date]);
    
    $moodSaved = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tracker - MindBalance</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet" />
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
    body.dark-mode .navbar-brand {
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

    /* Dark Mode Styling for Table */
    .dark-mode .table,
    .dark-mode .table th,
    .dark-mode .table td {
      background-color: #2b2b2b; /* Dark table background */
      color: #f0f0f0; /* Light text */
    }

    .dark-mode .table-light {
      background-color: #3a3a3a; /* Darker row for light table rows */
      color: #f0f0f0; /* Light text */
    }
  </style>
</head>
<body>
  <script>
    // Enable dark mode if it's stored in localStorage
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
    }

    // Toggle dark mode
    function toggleDarkMode() {
      document.body.classList.toggle('dark-mode');
      localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
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
          <li class="nav-item"><a class="nav-link active" href="tracker.php">Tracker</a></li>
          <li class="nav-item"><a class="nav-link" href="history.php">History</a></li>
          <li class="nav-item"><a class="nav-link" href="tips.php">Wellness Tips</a></li>
          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item"><a class="nav-link" href="favorites.php">Favorites</a></li>
          <?php endif; ?>
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
    <div class="text-center mb-5" style="font-family: 'Playfair Display', serif;">
      <h1 class="display-5 fw-bold mb-3">What's on your mind?</h1>
      <p class="lead" style="font-family: 'Open Sans', sans-serif;">By tracking your moods and thoughts regularly, you can gain valuable insights into your emotional well-being and identify patterns that may help you make more informed decisions about your mental health.</p>
    </div>

    <?php if ($moodSaved): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your mood has been saved.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Alert for non-signed-in users -->
    <?php if (!$loggedIn): ?>
      <div class="alert alert-warning text-center" role="alert">
        You need to <a href="signin.php?redirect=tracker.php" class="alert-link">sign in</a> to view the tracker and log your mood.
      </div>
    <?php else: ?>
      <h3 class="text-center mb-4" style="font-family: 'Playfair Display', serif;">Log Your Mood</h3>
      <form action="tracker.php" method="POST">
        <div class="mb-3">
          <label for="mood" class="form-label">How do you feel today?</label>
          <select class="form-select" id="mood" name="mood" required>
            <option value="">Select Mood</option>
            <option value="Happy">😊 Happy</option>
            <option value="Neutral">😐 Neutral</option>
            <option value="Sad">😢 Sad</option>
            <option value="Angry">😠 Angry</option>
            <option value="Anxious">😰 Anxious</option>
            <option value="Grateful">🙏 Grateful</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="note" class="form-label">Write a note (optional)</label>
          <textarea class="form-control" id="note" name="note" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label for="log_date" class="form-label">Date</label>
          <input type="date" class="form-control" id="log_date" name="log_date" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-success">Save Log</button>
        </div>
      </form>
    <?php endif; ?>
  </main>

  <footer class="bg-dark text-light py-4 mt-5">
    <div class="container text-center">
      <p class="mb-1">Let's talk: <a href="mailto:support@mindbalance.com" class="text-light">support@mindbalance.com</a></p>
      <p class="small">Your data is private. We’re committed to protecting your wellness journey.</p>
      <p class="mb-0 small">&copy; <?= date('Y') ?> MindBalance. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
