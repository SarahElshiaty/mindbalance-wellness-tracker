<?php  
session_start(); 
$_SESSION['redirect_back'] = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact - MindBalance</title>
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

    .info-box {
      background-color: #f8f9fa;
      border-left: 4px solid #588157;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 10px;
    }
    .info-box:hover {
      transform: scale(1.02);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .info-box i {
      font-size: 2rem;
      color: #588157;
      vertical-align: middle;
      margin-right: 10px;
      transition: transform 0.3s ease;
    }
    .info-box:hover i {
      transform: rotate(5deg);
    }
    .info-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.2rem;
      font-weight: 600;
      color: #2e2e2e;
    }
    .info-text {
      font-family: 'Open Sans', sans-serif;
      font-size: 0.95rem;
      color: #2e2e2e;
    }
    input::placeholder,
    textarea::placeholder {
      opacity: 0.6;
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
    input:focus::placeholder,
    textarea:focus::placeholder {
      opacity: 0;
      transform: translateX(5px);
    }
    input:focus,
    textarea:focus,
    select:focus {
      border-color: #588157;
      box-shadow: 0 0 0 0.2rem rgba(88, 129, 87, 0.25);
    }
    .btn-primary {
      background-color: #588157;
      border-color: #588157;
      transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #476f49;
      border-color: #476f49;
    }
    .card {
      transition: all 0.3s ease-in-out;
      border-radius: 10px;
    }
    .card:hover {
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
      transform: translateY(-4px);
    }
    label {
      transition: color 0.2s ease-in-out;
    }
    input:focus + label,
    textarea:focus + label {
      color: #588157;
    }

    body.dark-mode .info-box {
      background-color: #3a3a3a;
    }
    body.dark-mode .info-title,
    body.dark-mode .info-text,
    body.dark-mode .info-box i {
      color: #f0f0f0 !important;
    }
    body.dark-mode .form-select,
    body.dark-mode input,
    body.dark-mode textarea {
      background-color: #333;
      color: #f0f0f0;
      border-color: #666;
    }
    body.dark-mode .form-select option {
      background-color: #333;
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
      <img src="assets/images/logo.png" alt="MindBalance Logo" width="28" height="28" class="me-2">
      MindBalance
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
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="favorites.php">Favorites</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link active" href="conditions.php">Conditions</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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
  <h2 class="text-center mb-4">Contact Us</h2>

  <!-- FLASHED BOOTSTRAP ALERT -->
  <?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_message_type']) ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['flash_message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_message_type']); ?>
  <?php endif; ?>
  
  <!-- Info Section (2x2 Grid) -->
  <div class="row g-4 mb-5">
    <div class="col-md-6">
      <div class="info-box">
        <i class="material-icons">help_outline</i>
        <div class="info-title">Why contact us?</div>
        <div class="info-text">Need guidance on using the tracker, suggestions, or mental wellness tips? We're here for every question or concern.</div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info-box">
        <i class="material-icons">place</i>
        <div class="info-title">Where are we?</div>
        <div class="info-text">We’re a digital platform — available wherever you are. Reach us anytime online through email or this form.</div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info-box">
        <i class="material-icons">access_time</i>
        <div class="info-title">When can you reach us?</div>
        <div class="info-text">Our support team replies within 24 hours, 7 days a week, to ensure you feel supported and heard.</div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info-box">
        <i class="material-icons">email</i>
        <div class="info-title">How to contact us?</div>
        <div class="info-text">Use the form below or email us directly at <a href="mailto:support@mindbalance.com">support@mindbalance.com</a>.</div>
      </div>
    </div>
  </div>

  <!-- Doctor Recommendations -->
  <section class="mt-5">
    <h3 class="text-center mb-4" style="font-family: 'Playfair Display', serif;">Need to Talk to a Specialist?</h3>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="assets/images/lee.jpg" class="card-img-top" alt="Dr. Sarah Lee">
          <div class="card-body">
            <h5 class="card-title">Dr. Sarah Lee</h5>
            <p class="card-text">Psychologist – Riyadh Mental Wellness Center</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="assets/images/omar.jpg" class="card-img-top" alt="Dr. Omar Al-Fahad">
          <div class="card-body">
            <h5 class="card-title">Dr. Omar Al-Fahad</h5>
            <p class="card-text">Psychiatrist – Jeddah Emotional Health Clinic</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="assets/images/Amina.jpg" class="card-img-top" alt="Dr. Amina Yusuf">
          <div class="card-body">
            <h5 class="card-title">Dr. Amina Yusuf</h5>
            <p class="card-text">Therapist – Dammam Mind Care Hospital</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="assets/images/faisal.jpg" class="card-img-top" alt="Dr. Faisal Khan">
          <div class="card-body">
            <h5 class="card-title">Dr. Faisal Khan</h5>
            <p class="card-text">Neuropsychiatrist – Mecca Mental Health Center</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="noura.jpg" class="card-img-top" alt="Dr. Noura Al-Mutairi">
          <div class="card-body">
            <h5 class="card-title">Dr. Noura Al-Mutairi</h5>
            <p class="card-text">Clinical Psychologist – Eastern Province</p>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="assets/images/hassan.jpg" class="card-img-top" alt="Dr. Hassan Suleiman">
          <div class="card-body">
            <h5 class="card-title">Dr. Hassan Suleiman</h5>
            <p class="card-text">Behavioral Therapist – Taif Serenity Center</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Form -->
  <div class="row mt-5 mb-5">
    <div class="col-md-8 mx-auto">
      <form id="contactForm" action="submit_contact.php" method="POST">
        <div class="mb-3">
          <label for="name" class="form-label">Your Name</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="doctor" class="form-label">Preferred Doctor (Optional)</label>
          <select class="form-select" id="doctor" name="doctor">
            <option value="">No Preference</option>
            <option>Dr. Sarah Lee</option>
            <option>Dr. Omar Al-Fahad</option>
            <option>Dr. Amina Yusuf</option>
            <option>Dr. Faisal Khan</option>
            <option>Dr. Noura Al-Mutairi</option>
            <option>Dr. Hassan Suleiman</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Send Message</button>
        </div>
      </form>
      <p class="mt-3 text-center" id="tipText">Hover over this text for a tip!</p>
    </div>
  </div>

  <!-- Ending Quote -->
  <div class="text-center mt-4">
    <blockquote class="blockquote">
      <p class="mb-0">"Helping one person might not change the whole world, but it could change the world for one person."</p>
    </blockquote>
  </div>
</main>

<footer class="bg-dark text-light py-4 mt-5">
  <div class="container text-center">
    <p>Have questions or feedback? Reach out to us anytime via <a href="mailto:support@mindbalance.com" class="text-light">support@mindbalance.com</a></p>
    <p class="mb-0">&copy; 2025 MindBalance — Empowering your emotional journey every step of the way.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
  }

  const form = document.getElementById('contactForm');
  form.addEventListener('submit', function(e) {
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const message = form.message.value.trim();
    if (!name || !email || !message) {
      alert("Please fill out all fields.");
      e.preventDefault();
    } else if (!email.includes('@')) {
      alert("Please enter a valid email address.");
      e.preventDefault();
    }
  });

  const tipText = document.getElementById('tipText');
  tipText.addEventListener('mouseover', function() {
    tipText.textContent = "Tip: Expressing emotions is a strength, not a weakness.";
  });
  tipText.addEventListener('mouseout', function() {
    tipText.textContent = "Hover over this text for a tip!";
  });
</script>
</body>
</html>