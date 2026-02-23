<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0;

if (!$isLoggedIn) {
    header('Location: login.php');
    exit;
}

$username = isset($_SESSION['username']) ? trim((string)$_SESSION['username']) : 'User';
if ($username === '') {
    $username = 'User';
}
$safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

$greeting = 'Welcome';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account - Aura.stream</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/sidebar.css?v=9">
  <style>
    :root {
      --bg-dark: #09090b;
      --bg-card: rgba(24, 24, 27, 0.8);
      --bg-glass: rgba(255, 255, 255, 0.03);
      --border-color: rgba(255, 255, 255, 0.08);
      --text-main: #fafafa;
      --text-muted: #a1a1aa;
      --primary: #8b5cf6;
      --primary-dark: #7c3aed;
      --secondary: #06b6d4;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Outfit', sans-serif;
      background-color: var(--bg-dark);
      color: var(--text-main);
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
        radial-gradient(ellipse at 80% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }

    .account-card {
      position: relative;
      width: min(540px, 100%);
      padding: 28px;
      border-radius: 22px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      backdrop-filter: blur(14px);
      box-shadow:
        0 20px 45px rgba(0, 0, 0, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.14);
    }

    .account-card::before {
      content: "";
      position: absolute;
      top: -80px;
      right: -80px;
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(139, 92, 246, 0.26), transparent 72%);
      pointer-events: none;
    }

    h1 {
      margin: 0;
      font-size: clamp(1.2rem, 2.2vw, 1.8rem);
      font-weight: 700;
      letter-spacing: 0.2px;
      line-height: 1.2;
      white-space: normal;
      overflow-wrap: break-word;
      word-break: normal;
      max-width: 100%;
    }

    .subtitle {
      margin: 8px 0 22px;
      color: var(--text-muted);
      font-size: 0.96rem;
      line-height: 1.45;
    }

    .title-accent {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .option-list {
      display: grid;
      gap: 12px;
    }

    .option {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      width: 100%;
      padding: 14px 16px;
      border-radius: 14px;
      text-decoration: none;
      font-weight: 600;
      transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .option .left {
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .option:hover {
      transform: translateY(-2px);
      filter: brightness(1.02);
      box-shadow: 0 10px 22px rgba(0, 0, 0, 0.28);
    }

    .watch-later {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #ffffff;
    }

    .logout {
      background: linear-gradient(135deg, #1b2848, #233760);
      color: #fff;
      border: 1px solid rgba(139, 92, 246, 0.35);
    }

    .option i {
      font-size: 1.05rem;
    }

    .arrow {
      opacity: 0.86;
      font-size: 0.95rem;
    }

    .main-content {
      margin-left: 72px;
      width: calc(100% - 72px);
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 30px 28px;
    }

    @media (max-width: 520px) {
      .account-card {
        padding: 20px;
        border-radius: 18px;
      }
    }

    @media (max-width: 768px) {
      html,
      body {
        min-height: 100%;
      }

      body {
        padding-bottom: 74px;
      }

      .main-content {
        margin-left: 0;
        width: 100%;
        padding: 18px 14px;
      }

      .mobile-bottom-nav {
        position: fixed !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100% !important;
        height: 64px !important;
        display: grid !important;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        background: rgba(6, 8, 12, 0.96);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        transform: translateZ(0);
        z-index: 9999 !important;
      }
    }
  </style>
  <link rel="stylesheet" href="css/mobile.css?v=6">
</head>
<body>
  <nav class="sidebar">
    <div class="logo">
      <a href="login.php">
        <img src="assets/logo.png" alt="Logo">
      </a>
    </div>
    <ul class="nav-links">
      <li><a href="index.php"><i class="bi bi-house-door-fill"></i> <span>Home</span></a></li>
      <li><a href="search.php"><i class="bi bi-search"></i> <span>Discover</span></a></li>
      <li><a href="genere.php?genre=top-rated"><i class="bi bi-trophy"></i> <span>Top Rated</span></a></li>
      <li><a href="movies.php"><i class="bi bi-film"></i> <span>Movies</span></a></li>
      <li><a href="webseries.php"><i class="bi bi-tv"></i> <span>Web Series</span></a></li>
      <li><a href="watchlater.php"><i class="bi bi-bookmark-heart"></i> <span>Watch Later</span></a></li>
      <li><a href="account.php" class="active"><i class="bi bi-person-circle"></i> <span>Profile</span></a></li>
    </ul>
  </nav>

  <main class="main-content">
    <div class="account-card">
      <h1><span class="title-accent"><?php echo $greeting; ?></span>, <?php echo $safeUsername; ?></h1>
      <p class="subtitle">Manage your saved titles and sign out securely.</p>
      <div class="option-list">
        <a class="option watch-later" href="watchlater.php">
          <span class="left"><i class="bi bi-bookmark-heart-fill"></i> Watch Later</span>
          <i class="bi bi-arrow-up-right arrow"></i>
        </a>
        <a class="option logout" href="logout.php">
          <span class="left"><i class="bi bi-box-arrow-right"></i> Log Out</span>
          <i class="bi bi-arrow-up-right arrow"></i>
        </a>
      </div>
    </div>
  </main>

  <nav class="mobile-bottom-nav" aria-label="Mobile navigation">
    <a href="index.php"><i class="bi bi-house-door-fill"></i><span>Home</span></a>
    <a href="search.php"><i class="bi bi-search"></i><span>Search</span></a>
    <a href="account.php" class="active"><i class="bi bi-person-circle"></i><span>Profile</span></a>
  </nav>
</body>
</html>






