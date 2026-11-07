<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  $loggedIn = false;
} else {
  $loggedIn = true;
  $user_id = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM mood_logs WHERE user_id = ? ORDER BY log_date DESC");
  $stmt->execute([$user_id]);
  $logs = $stmt->fetchAll();
}

// Check if delete form was submitted
if (isset($_POST['delete_log']) && isset($_SESSION['user_id'])) {
  $log_id = $_POST['log_id'];

  // Delete the log from the database
  $stmt = $pdo->prepare("DELETE FROM mood_logs WHERE log_id = ? AND user_id = ?");
  $stmt->execute([$log_id, $_SESSION['user_id']]);

  // Set a session message to indicate success
  $_SESSION['message'] = 'Log successfully deleted!';
  $_SESSION['message_type'] = 'danger'; // Set the message type to danger (for warning)

  // Redirect to refresh the page and display the updated logs
  header("Location: history.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>History - MindBalance</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="<?= (isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] == 'enabled') ? 'dark-mode' : '' ?>"> <!-- Add dark-mode class if enabled -->

  <header class="bg-light border-bottom mb-4 fixed-navbar">
  <nav class="navbar navbar-expand-lg navbar-light container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
      <img src="assets/images/logo.png" alt="MindBalance Logo" width="28" class="me-2" />MindBalance
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="tracker.php">Tracker</a></li>
        <li class="nav-item"><a class="nav-link active" href="history.php">History</a></li>
        <li class="nav-item"><a class="nav-link active" href="tips.php">Wellness Tips</a></li>
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
      <h1 class="display-5 fw-bold mb-3">Your Mood History</h1>
      <p class="lead" style="font-family: 'Open Sans', sans-serif;">Review your past logs to reflect on your journey and track your progress.</p>
    </div>

    <!-- Show Success/Failure Message -->
    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-<?= $_SESSION['message_type'] ?> text-center" role="alert">
        <?= $_SESSION['message'] ?>
      </div>
      <?php unset($_SESSION['message']); // Clear the message after showing ?>
    <?php endif; ?>

    <?php if (!$loggedIn): ?>
      <div class="alert alert-warning text-center" role="alert">
        You need to <a href="signin.php?redirect=history.php" class="alert-link">sign in</a> to view your mood history.
      </div>
    <?php else: ?>
      <?php if (count($logs) > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Mood</th>
                <th>Note</th>
                <th>Actions</th> <!-- Column header for the action -->
              </tr>
            </thead>
            <tbody>
              <?php foreach ($logs as $log): ?>
                <tr>
                  <td><?= htmlspecialchars($log['log_date']) ?></td>
                  <td><?= htmlspecialchars($log['mood']) ?></td>
                  <td><?= htmlspecialchars($log['note']) ?></td>
                  <td>
                    <!-- Edit button -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $log['log_id'] ?>">Edit</button>
                    
                    <!-- Delete button with confirmation -->
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $log['log_id'] ?>">Delete</button>
                  </td>
                </tr>

                <!-- Modal for editing the log -->
                <div class="modal fade" id="editModal<?= $log['log_id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Mood Log</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="history.php" method="POST">
                          <input type="hidden" name="log_id" value="<?= $log['log_id'] ?>">

                          <div class="mb-3">
                            <label for="mood" class="form-label">Mood</label>
                            <select class="form-select" id="mood" name="mood" required>
                              <option value="Happy" <?= ($log['mood'] == 'Happy') ? 'selected' : '' ?>>😊 Happy</option>
                              <option value="Neutral" <?= ($log['mood'] == 'Neutral') ? 'selected' : '' ?>>😐 Neutral</option>
                              <option value="Sad" <?= ($log['mood'] == 'Sad') ? 'selected' : '' ?>>😢 Sad</option>
                              <option value="Angry" <?= ($log['mood'] == 'Angry') ? 'selected' : '' ?>>😠 Angry</option>
                              <option value="Anxious" <?= ($log['mood'] == 'Anxious') ? 'selected' : '' ?>>😰 Anxious</option>
                              <option value="Grateful" <?= ($log['mood'] == 'Grateful') ? 'selected' : '' ?>>🙏 Grateful</option>
                            </select>
                          </div>

                          <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3"><?= htmlspecialchars($log['note']) ?></textarea>
                          </div>

                          <div class="mb-3">
                            <label for="log_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="log_date" name="log_date" value="<?= $log['log_date'] ?>" required>
                          </div>

                          <div class="d-grid">
                            <button type="submit" name="update_log" class="btn btn-success">Update Log</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal for deleting the log -->
                <div class="modal fade" id="deleteModal<?= $log['log_id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Mood Log</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p>Are you sure you want to delete this log?</p>
                        <form action="history.php" method="POST">
                          <input type="hidden" name="log_id" value="<?= $log['log_id'] ?>">
                          <div class="d-grid">
                            <button type="submit" name="delete_log" class="btn btn-danger">Delete</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-center">No mood logs found yet. Start tracking to see your wellness journey.</p>
      <?php endif; ?>
    <?php endif; ?>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-light py-4 mt-5">
    <div class="container text-center">
      <p class="mb-1">Let's talk: <a href="mailto:support@mindbalance.com" class="text-light">support@mindbalance.com</a></p>
      <p class="small">Your data is private. We’re committed to protecting your wellness journey.</p>
      <p class="mb-0 small">&copy; <?= date('Y') ?> MindBalance. All rights reserved.</p>
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
