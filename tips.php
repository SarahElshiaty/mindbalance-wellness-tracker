<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Wellness Tips - MindBalance</title>
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
    .tip-card {
      border-radius: 12px;
      background: linear-gradient(135deg, #e9ecef, #f3f3f3);
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      transition: transform 0.3s;
      position: relative;
    }
    .tip-card:hover {
      transform: scale(1.02);
    }
    .tips-section h2 {
      font-family: 'Playfair Display', serif;
    }
    .tips-section p,
    .tip-card p {
      font-family: 'Open Sans', sans-serif;
    }
    .filter-btn.active {
      background-color: #588157 !important;
      color: white !important;
    }
    .favorite-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background: none;
      border: none;
      color: #888;
      font-size: 1.2rem;
    }
    .favorite-btn:hover {
      color: #e63946;
    }
    body.dark-mode .tip-card {
      background: linear-gradient(135deg, #2e2f30, #3a3b3c);
      color: #f0f0f0;
    }
    body.dark-mode .tip-card h5,
    body.dark-mode .tip-card p {
      color: #f0f0f0;
    }
    body.dark-mode .final-message {
      background-color: #2b2b2b !important;
      color: #f0f0f0;
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
<script>
  if (localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
  }
</script>

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
        <li class="nav-item"><a class="nav-link" href="history.php">History</a></li>
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

<main class="container tips-section my-5">
  <div class="text-center mb-5">
    <h2 class="display-6 fw-bold mb-3">Wellness Tips by Category</h2>
  </div>

  <div class="text-center mb-4">
    <div class="btn-group" role="group">
      <button class="btn btn-outline-success filter-btn active" data-filter="all">All</button>
      <button class="btn btn-outline-success filter-btn" data-filter="adhd">ADHD</button>
      <button class="btn btn-outline-success filter-btn" data-filter="anxiety">Anxiety</button>
      <button class="btn btn-outline-success filter-btn" data-filter="sleep">Sleep</button>
      <button class="btn btn-outline-success filter-btn" data-filter="ptsd">PTSD</button>
      <button class="btn btn-outline-success filter-btn" data-filter="ocd">OCD</button>
      <button class="btn btn-outline-success filter-btn" data-filter="depression">Depression</button>
    </div>
  </div>

  <div class="row g-4 mb-5" id="tipsContainer">
    <?php
      $tips = [
        ['category'=>'adhd','icon'=>'schedule','title'=>'Time Management','text'=>'Use timers to keep focused.'],
        ['category'=>'adhd','icon'=>'check_circle','title'=>'Task Lists','text'=>'Use checklists to organize your day.'],
        ['category'=>'adhd','icon'=>'headphones','title'=>'Focus Tools','text'=>'Use white noise to help concentration.'],
        ['category'=>'anxiety','icon'=>'self_improvement','title'=>'Grounding','text'=>'Use 5-4-3-2-1 sensory technique.'],
        ['category'=>'anxiety','icon'=>'spa','title'=>'Breathing','text'=>'Practice box breathing.'],
        ['category'=>'anxiety','icon'=>'menu_book','title'=>'Journaling','text'=>'Write down your anxious thoughts.'],
        ['category'=>'sleep','icon'=>'bedtime','title'=>'Regular Sleep','text'=>'Stick to a consistent bedtime.'],
        ['category'=>'sleep','icon'=>'alarm','title'=>'Wake Routine','text'=>'Wake at the same time daily.'],
        ['category'=>'sleep','icon'=>'nightlight_round','title'=>'Sleep Setup','text'=>'Dark, cool rooms help sleep.'],
        ['category'=>'ptsd','icon'=>'security','title'=>'Safe Space','text'=>'Create a trigger-free zone.'],
        ['category'=>'ptsd','icon'=>'record_voice_over','title'=>'Talk It Out','text'=>'Speak with a therapist or friend.'],
        ['category'=>'ptsd','icon'=>'visibility_off','title'=>'Limit Media','text'=>'Reduce exposure to triggers.'],
        ['category'=>'ocd','icon'=>'check','title'=>'Response Prevention','text'=>'Resist compulsions gradually.'],
        ['category'=>'ocd','icon'=>'loop','title'=>'Reframe Thoughts','text'=>'Label intrusive thoughts.'],
        ['category'=>'ocd','icon'=>'self_improvement','title'=>'Mindfulness','text'=>'Practice present awareness.'],
        ['category'=>'depression','icon'=>'light_mode','title'=>'Small Wins','text'=>'Do one small positive action.'],
        ['category'=>'depression','icon'=>'emoji_people','title'=>'Social Boost','text'=>'Connect with someone.'],
        ['category'=>'depression','icon'=>'sports_handball','title'=>'Gentle Exercise','text'=>'Move your body lightly.']
      ];
      shuffle($tips);
      foreach ($tips as $tip): ?>
        <div class="col-md-6 col-lg-4 filter-item <?= $tip['category'] ?>">
          <div class="tip-card h-100">
            <h5><span class="material-icons"><?= $tip['icon'] ?></span> <?= htmlspecialchars($tip['title']) ?></h5>
            <p><?= htmlspecialchars($tip['text']) ?></p>
            <?php if (isset($_SESSION['user'])): ?>
              <button class="favorite-btn"
                data-title="<?= htmlspecialchars($tip['title']) ?>"
                data-text="<?= htmlspecialchars($tip['text']) ?>"
                data-category="<?= htmlspecialchars($tip['category']) ?>"
                data-icon="<?= htmlspecialchars($tip['icon']) ?>"
                title="Save this tip">❤️</button>
            <?php endif; ?>
          </div>
        </div>
    <?php endforeach; ?>
  </div>

  <div class="final-message mt-5 p-4 rounded bg-light shadow-sm row align-items-center">
    <div class="col-md-8">
      <h4 style="font-family: 'Playfair Display', serif;">Why should you track your mental health?</h4>
      <p style="font-family: 'Open Sans', sans-serif;">Tracking your mental health helps you recognize what affects your mood, manage conditions like anxiety or depression, and improve self-awareness. It empowers you to take control of your mental wellbeing and work proactively toward balance and growth.</p>
    </div>
    <div class="col-md-4 text-center">
      <img src="assets/images/explain.jpg" alt="Mental Health Awareness" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
    </div>
  </div>
</main>

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
  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
  }

  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const filter = btn.getAttribute('data-filter');
      document.querySelectorAll('.filter-item').forEach(card => {
        card.style.display = (filter === 'all' || card.classList.contains(filter)) ? 'block' : 'none';
      });
    });
  });

  document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const data = {
        title: btn.dataset.title,
        text: btn.dataset.text,
        category: btn.dataset.category,
        icon: btn.dataset.icon
      };

      fetch('add_favorite.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(response => {
        if (response.success) {
          btn.textContent = '❤️ Saved';
          btn.disabled = true;
          btn.style.color = 'red';
        } else {
          alert('Could not save tip.');
        }
      });
    });
  });
</script>
</body>
</html>
