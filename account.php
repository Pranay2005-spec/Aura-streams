<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0;

if (!$isLoggedIn) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account - Aura.stream</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    :root {
      --bg-deep: #090f1d;
      --bg-mid: #0f1b34;
      --surface: rgba(13, 23, 46, 0.82);
      --surface-border: rgba(255, 255, 255, 0.14);
      --text-main: #f5f7ff;
      --text-muted: #a8b5d3;
      --accent: #1fd0a8;
      --accent-dark: #07231c;
      --danger: #ff5a6d;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      display: grid;
      place-items: center;
      font-family: 'Outfit', sans-serif;
      background:
        radial-gradient(circle at 12% 18%, rgba(57, 94, 179, 0.35), transparent 44%),
        radial-gradient(circle at 88% 82%, rgba(31, 208, 168, 0.24), transparent 38%),
        linear-gradient(150deg, var(--bg-deep), var(--bg-mid) 58%, #121f39);
      color: var(--text-main);
      padding: 24px;
      overflow: hidden;
    }

    .account-card {
      position: relative;
      width: min(540px, 100%);
      padding: 28px;
      border-radius: 22px;
      background: var(--surface);
      border: 1px solid var(--surface-border);
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
      background: radial-gradient(circle, rgba(31, 208, 168, 0.25), transparent 72%);
      pointer-events: none;
    }

    h1 {
      margin: 0;
      font-size: clamp(1.6rem, 2.8vw, 2.1rem);
      font-weight: 700;
      letter-spacing: 0.2px;
    }

    .subtitle {
      margin: 8px 0 22px;
      color: var(--text-muted);
      font-size: 0.96rem;
      line-height: 1.45;
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
      background: linear-gradient(135deg, #1fd0a8, #17b88f);
      color: var(--accent-dark);
    }

    .logout {
      background: linear-gradient(135deg, #ff6175, #ea4559);
      color: #fff;
    }

    .option i {
      font-size: 1.05rem;
    }

    .arrow {
      opacity: 0.86;
      font-size: 0.95rem;
    }

    @media (max-width: 520px) {
      body {
        padding: 16px;
      }

      .account-card {
        padding: 20px;
        border-radius: 18px;
      }
    }
  </style>
  <link rel="stylesheet" href="css/mobile.css?v=6">
</head>
<body>
  <div class="account-card">
    <h1>My Account</h1>
    <p class="subtitle">Manage your saved titles and sign out securely.</p>
    <div class="option-list">
      <a class="option watch-later" href="watchlater.html">
        <span class="left"><i class="bi bi-bookmark-heart-fill"></i> Watch Later</span>
        <i class="bi bi-arrow-up-right arrow"></i>
      </a>
      <a class="option logout" href="logout.php">
        <span class="left"><i class="bi bi-box-arrow-right"></i> Log Out</span>
        <i class="bi bi-arrow-up-right arrow"></i>
      </a>
    </div>
  </div>
</body>
</html>





