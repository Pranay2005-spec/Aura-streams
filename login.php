<?php
include "database.php";
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!empty($db_connect_error)) {
    echo "<script>alert('Database is not reachable right now. Check hosting DB credentials/host.'); window.location='login.php';</script>";
    exit();
  }

  $email = $_POST['email'];
  $password = $_POST['password'];

  // Fetch the user by email
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if user exists
  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {

      // Store user info in session
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      echo "<script>alert('Login Successful!'); window.location='index.php';</script>";
    } else {
      echo "<script>alert('Incorrect Password'); window.location='login.php';</script>";
    }
  } else {
    echo "<script>alert('Email not found'); window.location='login.php';</script>";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Aura.stream</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/sidebar.css?v=9">
  <link rel="stylesheet" href="css/footer.css?v=9">

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

    .login-container {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 0 0 30px rgba(139, 92, 246, 0.2);
      width: 100%;
      max-width: 340px;
    }

    .login-heading {
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
      .login-container {
        padding: 30px 20px;
      }

      .login-heading {
        font-size: 1.6rem;
      }
    }
  </style>
  <link rel="stylesheet" href="css/mobile.css?v=6">
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
    <li><a href="account.php" class="active"><i class="bi bi-person-circle"></i> <span>Login</span></a></li>
  </ul>
</nav>


  <main class="main-content">
  <div class="login-container">
    <h2 class="login-heading">Login</h2>
    <?php if (!empty($db_connect_error)): ?>
      <div class="alert alert-danger py-2" role="alert">Database connection failed. Recheck InfinityFree DB host, username, password, and database name.</div>
    <?php endif; ?>
    <form action="login.php" method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
      </div>

      <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="login-options">
      <p><a href="#">Forgot Password?</a></p>
      <p>Don’t have an account? <a href="signup.php">Sign Up</a></p>
      <p>Admin <a href="admin.html">Sign Up</a></p>
    </div>
  </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('.login-container form');
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');

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

      form.addEventListener('submit', function(e) {

        let valid = true;
        clearError(emailInput);
        clearError(passwordInput);

        if (emailInput.value.trim() === '') {
          showError(emailInput, 'Email is required.');
          valid = false;
        } else if (!isValidEmail(emailInput.value.trim())) {
          showError(emailInput, 'Enter a valid email address.');
          valid = false;
        }

        if (passwordInput.value.trim() === '') {
          showError(passwordInput, 'Password is required.');
          valid = false;
        }


        if (valid) {
          const email = emailInput.value.trim();
          const password = passwordInput.value.trim();





        }
      });
    });
  </script>
  <?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error ?></p>
  <?php endif; ?>

<nav class="mobile-bottom-nav" aria-label="Mobile navigation">
  <a href="index.php"><i class="bi bi-house-door-fill"></i><span>Home</span></a>
  <a href="search.html"><i class="bi bi-search"></i><span>Search</span></a>
  <a href="account.php" class="active"><i class="bi bi-person-circle"></i><span>Profile</span></a>
</nav>
</body>

</html>





















