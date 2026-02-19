<?php
include "database.php";
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

function is_strong_password($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Za-z]/', $password)) return false;
    if (!preg_match('/\d/', $password)) return false;
    if (!preg_match('/[^A-Za-z0-9]/', $password)) return false;
    return true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($db_connect_error)) {
        echo "<script>alert('Database is not reachable right now. Check hosting DB credentials/host.'); window.location='signup.php';</script>";
        exit();
    }

    $username = trim($_POST['name'] ?? '');
    $email = trim($_POST['signupEmail'] ?? '');
    $password = (string)($_POST['signupPassword'] ?? '');
    $confirmPassword = (string)($_POST['confirmPassword'] ?? '');

    if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
        echo "<script>alert('All fields are required'); window.location='signup.php';</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address'); window.location='signup.php';</script>";
        exit();
    }

    // Check password match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match'); window.location='signup.php';</script>";
        exit();
    }

    if (!is_strong_password($password)) {
        echo "<script>alert('Password must be at least 8 characters and include letters, numbers, and a special character'); window.location='signup.php';</script>";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$check) {
        echo "<script>alert('Server error. Please try again.'); window.location='signup.php';</script>";
        exit();
    }
    $check->bind_param("s", $email);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Email already registered'); window.location='signup.php';</script>";
        exit();
    }

    // INSERT USER SAFELY
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "<script>alert('Server error. Please try again.'); window.location='signup.php';</script>";
        exit();
    }
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful! Please login.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - Aura.stream</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"/>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/sidebar.css?v=6">
  <link rel="stylesheet" href="css/footer.css?v=6">
<style>
    :root {
      --primary: #8b5cf6;
      --primary-dark: #7c3aed;
      --secondary: #06b6d4;
      --accent: #f59e0b;
      --bg-dark: #09090b;
      --bg-card: rgba(24, 24, 27, 0.8);
      --bg-glass: rgba(255, 255, 255, 0.03);
      --border-color: rgba(255, 255, 255, 0.08);
      --text-primary: #fafafa;
      --text-secondary: #a1a1aa;
      --text-muted: #71717a;
      --gradient-1: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
      --gradient-2: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
      --gradient-3: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
      --shadow-glow: 0 0 60px rgba(139, 92, 246, 0.3);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: var(--bg-dark);
      font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-primary);
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background:
        radial-gradient(ellipse at 20% 20%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(245, 158, 11, 0.05) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }

    a {
      text-decoration: none;
      color: inherit;
    }
.main-content {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    @media (max-width: 768px) {
.main-content {
      }
    }

    .signup-container {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 0 0 30px rgba(139, 92, 246, 0.2);
      width: 100%;
      max-width: 340px;
    }

    .signup-heading {
      text-align: center;
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 20px;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .form-control {
      background: var(--bg-glass);
      color: var(--text-primary);
      border: 1px solid var(--border-color);
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.06);
      color: var(--text-primary);
      border-color: var(--primary);
      box-shadow: none;
    }

    .btn-login {
      background: var(--gradient-1);
      color: white;
      border: none;
      padding: 10px;
      border-radius: 30px;
      transition: 0.3s;
      width: 100%;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(139, 92, 246, 0.35);
    }

    .login-options {
      margin-top: 15px;
      font-size: 0.9rem;
      text-align: center;
      color: var(--text-secondary);
    }

    .login-options a {
      color: var(--primary);
      text-decoration: none;
    }

    .login-options a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .signup-container {
        padding: 30px 20px;
      }
      .signup-heading {
        font-size: 1.6rem;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar Navbar -->
<nav class="sidebar">
  <div class="logo">
    <a href="login.php">
      <img src="assets/logo.png" alt="Logo">
    </a>
  </div>
  <ul class="nav-links">
    <li><a href="index.php"><i class="bi bi-house-door-fill"></i> <span>Home</span></a></li>
    <li><a href="search.html"><i class="bi bi-search"></i> <span>Discover</span></a></li>
    <li><a href="genere.html?genre=top-rated"><i class="bi bi-trophy"></i> <span>Top Rated</span></a></li>
    <li><a href="movies.html"><i class="bi bi-film"></i> <span>Movies</span></a></li>
    <li><a href="webseries.html"><i class="bi bi-tv"></i> <span>Web Series</span></a></li>
    <li><a href="watchlater.html"><i class="bi bi-bookmark-heart"></i> <span>Watch Later</span></a></li>
    <?php if ($isLoggedIn): ?>
      <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
    <?php else: ?>
      <li><a href="login.php" class="active"><i class="bi bi-person-circle"></i> <span>Account</span></a></li>
    <?php endif; ?>
  </ul>
</nav>

  <main class="main-content">

  <!-- Signup Form -->
  <div class="signup-container">
    <h2 class="signup-heading">Sign Up</h2>
    <?php if (!empty($db_connect_error)): ?>
      <div class="alert alert-danger py-2" role="alert">Database connection failed. Recheck InfinityFree DB host, username, password, and database name.</div>
    <?php endif; ?>
    <form action="signup.php" method="post">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name">
      </div>

      <div class="mb-3">
        <label for="signupEmail" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="signupEmail" name="signupEmail" placeholder="Enter your email">
      </div>

      <div class="mb-3">
        <label for="signupPassword" class="form-label">Password</label>
        <input type="password" class="form-control" id="signupPassword" name="signupPassword" placeholder="Create a password" minlength="8" autocomplete="new-password">
        <small class="text-secondary">Use at least 8 characters with letters, numbers, and one special character.</small>
      </div>

      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Repeat your password" minlength="8" autocomplete="new-password">
      </div>

      <button type="submit" class="btn-login">Sign Up</button>
    </form>

    <div class="login-options">
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.signup-container form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('signupEmail');
    const passwordInput = document.getElementById('signupPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    function showError(input, message) {
      clearError(input);
      const error = document.createElement('div');
      error.className = 'text-danger mt-1 small';
      error.textContent = message;
      input.classList.add('is-invalid');
      input.parentNode.appendChild(error);
    }

    function clearError(input) {
      input.classList.remove('is-invalid');
      const existingErrors = input.parentNode.querySelectorAll('.text-danger');
      existingErrors.forEach(err => err.remove());
    }

    function isValidEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email.toLowerCase());
    }

    function isStrongPassword(password) {
      if (password.length < 8) return false;
      if (!/[A-Za-z]/.test(password)) return false;
      if (!/\d/.test(password)) return false;
      if (!/[^A-Za-z0-9]/.test(password)) return false;
      return true;
    }

    form.addEventListener('submit', function (e) {
      let valid = true;

      // Clear previous errors
      [nameInput, emailInput, passwordInput, confirmPasswordInput].forEach(clearError);

      // Full Name
      if (nameInput.value.trim() === '') {
        showError(nameInput, 'Full name is required.');
        valid = false;
      }

      // Email
      if (emailInput.value.trim() === '') {
        showError(emailInput, 'Email is required.');
        valid = false;
      } else if (!isValidEmail(emailInput.value.trim())) {
        showError(emailInput, 'Enter a valid email address.');
        valid = false;
      }

      // Password
      if (!isStrongPassword(passwordInput.value)) {
        showError(passwordInput, 'Min 8 chars with letters, numbers, and a special character.');
        valid = false;
      }

      // Confirm Password
      if (confirmPasswordInput.value !== passwordInput.value) {
        showError(confirmPasswordInput, 'Passwords do not match.');
        valid = false;
      }

      if (!valid) {
        e.preventDefault(); // Stop form submission
      }
    });
  });
</script>
  </main>

<nav class="mobile-bottom-nav" aria-label="Mobile navigation">
  <a href="index.php"><i class="bi bi-house-door-fill"></i><span>Home</span></a>
  <a href="search.html"><i class="bi bi-search"></i><span>Search</span></a>
  <a href="movies.html"><i class="bi bi-film"></i><span>Movies</span></a>
  <a href="webseries.html"><i class="bi bi-tv"></i><span>Web Series</span></a>
</nav>
</body>
</html>





















