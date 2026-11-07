<?php
session_start();

// List of conditions and their info
$conditions = [
    'autism' => [
        'title' => 'Autism Spectrum Disorder',
        'overview' => 'ASD is a developmental condition that affects how a person communicates, interacts, and experiences the world. It\'s called a spectrum because symptoms and severity vary widely among individuals.',
        'details' => 'Symptoms include difficulty with social interactions, repetitive behaviors, and unique strengths. Early support and therapies improve outcomes.<br><br><strong>Common Signs:</strong><ul>
          <li>Difficulty understanding social cues</li>
          <li>Preference for routines and predictability</li>
          <li>Repetitive behaviors or speech</li>
          <li>Sensitivity to sensory input (e.g., sounds, lights)</li>
          <li>Intense focus on specific interests</li>
        </ul><br><strong>Learn More:</strong><br><a href="https://www.cdc.gov/autism/signs-symptoms/index.html" target="_blank">CDC – Signs and Symptoms of Autism Spectrum Disorder</a>'
    ],      

    'depression' => [
        'title'=>'Depression',
        'overview'=>'Depression is a mood disorder characterized by persistent feelings of sadness, hopelessness, and a lack of interest or pleasure in activities. It can affect ones thoughts, behavior, and overall well-being.​',
        'details'=>'Can affect how you feel, think, and handle daily activities. Professional support and lifestyle changes can help.<br><br><strong>Common Signs:</strong><ul>
          <li>Persistent low mood or sadness</li>
          <li>Loss of interest in previously enjoyed activities</li>
          <li>Changes in appetite or weight</li>
          <li>Feelings of worthlessness or excessive guilt</li>
          <li>Difficulty concentrating</li>
          <li>Thoughts of death or suicide</li>
        </ul><br><strong>Learn More:</strong><br><a href="https://www.mayoclinic.org/diseases-conditions/depression/symptoms-causes/syc-20356007" target="_blank">Mayo Clinic – Depression Symptoms and Causes</a>'
    ],

    'anxiety-disorder' => [
        'title'=>'Anxiety Disorder',
        'overview'=>'Anxiety disorders involve excessive fear or worry that is difficult to control and affects daily functioning. There are various types, including generalized anxiety disorder, panic disorder, and social anxiety disorder.',
        'details'=>'Includes generalized anxiety, panic attacks, and phobias. Therapy and relaxation techniques can reduce symptoms.<br><br><strong>Common Signs:</strong><ul>
        <li>Restlessness or feeling on edge</li>
        <li>Irritability</li>
        <li>Muscle tension</li>
        <li>Sleep disturbances</li>
        <li>Difficulty concentrating</li>
        <li>Avoidance of anxiety-provoking situations</li>
       </ul><br><strong>Learn More:</strong><br><a href="https://www.nhs.uk/mental-health/feelings-symptoms-behaviours/feelings-and-symptoms/anxiety-disorder-signs/" target="_blank">NHS – Signs of an Anxiety Disorder</a>'
    ],

    'bipolar-disorder' => [
        'title'=>'Bipolar Disorder',
        'overview'=>'Bipolar disorder is a mental health condition characterized by extreme mood swings, including emotional highs (mania or hypomania) and lows (depression).',
        'details'=>'<strong>Common Signs:</strong><br><strong>Manic/Hypomanic Episode:</strong><ul>
              <li>Elevated or irritable mood</li>
              <li>Increased activity or energy</li>
              <li>Reduced need for sleep</li>
              <li>Impulsive or risky behavior</li>
            </ul><br><strong>Depressive Episode:</strong><ul>
              <li>Low mood or sadness</li>
              <li>Loss of interest in activities</li>
              <li>Fatigue or low energy</li>
              <li>Feelings of worthlessness</li>
            </ul><br><strong>Learn More:</strong><br><a href="https://www.nhs.uk/mental-health/conditions/bipolar-disorder/" target="_blank">NHS – Bipolar Disorder Overview</a>'
    ],
        
    'ocd' => [
        'title'=>'Obsessive-Compulsive Disorder (OCD)',
        'overview'=>'OCD is a mental health disorder characterized by unwanted, intrusive thoughts (obsessions) and repetitive behaviors (compulsions) performed to alleviate anxiety caused by these thoughts.',
        'details'=>'Common symptoms include fear of contamination, excessive cleaning, repeated checking, and need for orderliness.<br><br><strong>Common Signs:</strong><ul>
              <li>Fear of contamination or germs</li>
              <li>Excessive cleaning or handwashing</li>
              <li>Repeated checking (e.g., locks, appliances)</li>
              <li>Counting or repeating actions</li>
              <li>Need for symmetry or orderliness</li>
            </ul><br><strong>Learn More:</strong><br><a href="https://www.mayoclinic.org/diseases-conditions/obsessive-compulsive-disorder/symptoms-causes/syc-20354432" target="_blank">Mayo Clinic – OCD Symptoms and Causes</a>'
    ],

    'ptsd' => [
        'title'=>'Post-Traumatic Stress Disorder (PTSD)',
        'overview'=>'PTSD is a mental health condition triggered by experiencing or witnessing a traumatic event. It can cause intense, disturbing thoughts and feelings related to the experience that last long after the event has ended.',
        'details'=>'<strong>Common Signs:</strong><ul>
          <li>Flashbacks or nightmares</li>
          <li>Avoidance of reminders of the trauma</li>
          <li>Negative changes in thoughts and mood</li>
          <li>Hyperarousal (e.g., being easily startled)</li>
          <li>Irritability or aggressive behavior</li>
        </ul><br><strong>Learn More:</strong><br><a href="https://www.mayoclinic.org/diseases-conditions/post-traumatic-stress-disorder/symptoms-causes/syc-20355967" target="_blank">Mayo Clinic – PTSD Symptoms and Causes</a>'
    ],

    'adhd' => [
        'title'=>'Attention-Deficit/Hyperactivity Disorder (ADHD)',
        'overview'=>'ADHD is a neurodevelopmental disorder characterized by patterns of inattention, hyperactivity, and impulsivity that interfere with functioning or development.',
        'details'=>'<strong>Common Signs:</strong><ul>
          <li>Difficulty sustaining attention</li>
          <li>Forgetfulness in daily activities</li>
          <li>Fidgeting or inability to stay seated</li>
          <li>Interrupting or intruding on others</li>
          <li>Difficulty organizing tasks</li>
        </ul><br><strong>Learn More:</strong><br><a href="https://www.cdc.gov/adhd/signs-symptoms/index.html" target="_blank">CDC – Symptoms of ADHD</a>'
    ],

    'mental-health-disorder'=> [
        'title'=>'Mental Health Disorder',
        'overview'=>'Mental health disorders encompass a wide range of conditions that affect mood, thinking, and behavior. These disorders can impact daily functioning and quality of life.',
        'details'=>'<strong>Common Signs:</strong><ul>
          <li>Persistent sadness or irritability</li>
          <li>Excessive fears or worries</li>
          <li>Extreme mood changes</li>
          <li>Withdrawal from social activities</li>
        </ul><br><strong>Learn More:</strong><br><a href="https://www.who.int/news-room/fact-sheets/detail/mental-disorders" target="_blank">WHO – Mental Disorders</a>'
    ]
];

// Determine selected
$selected = $_GET['condition'] ?? '';
if (!isset($conditions[$selected])) {
  $selected = ''; // default to none
}

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Learn About Conditions – MindBalance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">  <!-- Include your custom styles.css here -->
  <style>
    body { scroll-behavior: smooth; }
    .condition-card { 
      cursor: pointer; 
      border: 2px solid transparent;
      transition: border-color .3s, transform .2s;
      padding: 20px;
      text-align: center;
      border-radius: 5px;
      font-family: 'Playfair Display', serif;
      background-color: #f4f4f4;
    }
    .condition-card:hover { 
      transform: scale(1.03); 
      background-color: #e9ecef;
    }
    .condition-card.selected { 
      border-color: #588157 !important;
      background-color: #e3f2e1;
    }
    .detail-section { 
      padding-top: 1rem; 
      margin-top: -1rem;
      background-color: #f8f9fa;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .detail-header { 
      background-color: #588157; 
      color: white; 
      padding: .75rem; 
      border-radius: .25rem .25rem 0 0;
    }
    .detail-body { 
      border: 1px solid #ccc; 
      border-top: none; 
      padding: 1rem; 
      border-radius: 0 0 .25rem .25rem;
    }
    .dark-mode body {
      background-color: #1e1e1e;
      color: #f0f0f0;
    }
    .dark-mode .navbar, .dark-mode footer, .dark-mode .condition-card {
      background-color: #2b2b2b;
      color: #f0f0f0;
    }
    .dark-mode .nav-link, .dark-mode .navbar-brand, .dark-mode footer a, .dark-mode .text-muted {
      color: #f0f0f0 !important;
    }
    .dark-mode .condition-card {
      background-color: #333;
      border-color: #444;
    }
    .dark-mode .detail-header {
      background-color: #2b2b2b;
    }
    .dark-mode .detail-body {
      background-color: #2c2c2c; 
      color: #f1f1f1; 
      border-color: #444;
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

  <main class="container my-5">
    <h2 class="text-center fw-bold mb-3" style="font-family: 'Playfair Display', serif;">
      Learn About Mental Health Conditions
    </h2>

    <!-- Cards for conditions -->
    <div class="row g-4 mb-5">
      <?php foreach($conditions as $slug => $info): ?>
        <div class="col-6 col-md-3">
          <a href="?condition=<?=$slug?>" class="text-decoration-none">
            <div class="condition-card <?=($slug===$selected?'selected':'')?>">
              <?=$info['title']?>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if($selected): ?>
      <!-- Detail Section for Selected Condition -->
      <div id="<?=$selected?>" class="detail-section">
        <div class="detail-header">
          <h3 class="mb-0"><?=$conditions[$selected]['title']?></h3>
        </div>
        <div class="detail-body">
          <p><strong>Overview:</strong> <?=$conditions[$selected]['overview']?></p>
          <p><?=$conditions[$selected]['details']?></p>
          <p class="text-muted fst-italic">
            Note: This information is for education only and not a diagnosis. Always consult a professional.
          </p>
        </div>
      </div>

      <script>
        // Scroll into view the selected detail
        document.getElementById('<?=$selected?>').scrollIntoView({behavior:'smooth'});
      </script>
    <?php endif; ?>
  </main>

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
