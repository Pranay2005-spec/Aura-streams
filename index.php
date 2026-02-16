<?php
session_start();
$username = $_SESSION['username'] ?? null;
$displayName = $username ? $username : 'Guest';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Aura.stream</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="sidebar.css">
  <link rel="stylesheet" href="footer.css">

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

    /* Custom Scrollbar */
    body {
      background-color: var(--bg-dark);
      font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-primary);
      overflow-x: hidden;
    }

    /* Animated Background */
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


    .hero-welcome {
      position: absolute;
      top: 20px;
      right: 40px;
      z-index: 5;
      background: rgba(9, 9, 11, 0.6);
      border: 1px solid var(--border-color);
      padding: 10px 14px;
      border-radius: 12px;
      backdrop-filter: blur(10px);
    }

    .welcome-text {
      font-size: 1rem;
      color: var(--text-secondary);
    }

    .welcome-text span {
      color: var(--text-primary);
      font-weight: 600;
    }


    /* Main Content */
    .main-content {
    }

    @media (max-width: 768px) {
.main-content {
      }
    }

    /* Hero Carousel */
    #animeHeroCarousel .carousel-item {
      height: 85vh;
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .dark-gradient {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to right, rgba(9, 9, 11, 0.95) 0%, rgba(9, 9, 11, 0.7) 40%, transparent 70%),
        linear-gradient(to top, rgba(9, 9, 11, 1) 0%, rgba(9, 9, 11, 0.5) 30%, transparent 60%);
    }

    .carousel-overlay {
      position: absolute;
      bottom: 25%;
      left: 8%;
      max-width: 45%;
      z-index: 2;
      color: white;
    }

    .carousel-badge {
      display: inline-block;
      padding: 6px 16px;
      background: var(--gradient-1);
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 15px;
    }

    .carousel-title {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 15px;
      line-height: 1.1;
      background: linear-gradient(135deg, #fff 0%, #a1a1aa 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .carousel-desc {
      font-size: 1.1rem;
      color: var(--text-secondary);
      margin-bottom: 25px;
      line-height: 1.6;
    }

    .carousel-meta {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }

    .meta-badge {
      padding: 6px 14px;
      border-radius: 8px;
      background: var(--bg-glass);
      border: 1px solid var(--border-color);
      font-size: 0.85rem;
      color: var(--text-secondary);
    }

    .meta-badge.rating {
      background: rgba(245, 158, 11, 0.2);
      border-color: var(--accent);
      color: var(--accent);
    }

    .btn-watch {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 16px 32px;
      background: var(--gradient-1);
      border: none;
      border-radius: 14px;
      color: white;
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      margin-right: 15px;
    }

    .btn-watch:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(139, 92, 246, 0.4);
      color: white;
    }

    .btn-watch i {
      font-size: 1.3rem;
    }


    .carousel-control-prev,
    .carousel-control-next {
      width: 60px;
      height: 60px;
      background: var(--bg-glass);
      border: 1px solid var(--border-color);
      border-radius: 50%;
      top: 50%;
      transform: translateY(-50%);
      opacity: 0;
      transition: all 0.3s ease;
    }

    #animeHeroCarousel:hover .carousel-control-prev,
    #animeHeroCarousel:hover .carousel-control-next {
      opacity: 1;
    }

    .carousel-control-prev {
      left: 30px;
    }

    .carousel-control-next {
      right: 30px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
      background: var(--primary);
      border-color: var(--primary);
    }

    .carousel-indicators {
      bottom: 30px;
    }

    .carousel-indicators [data-bs-target] {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      border: none;
      margin: 0 6px;
      transition: all 0.3s ease;
    }

    .carousel-indicators .active {
      background: var(--primary);
      transform: scale(1.3);
    }

    @media (max-width: 768px) {
      .carousel-overlay {
        bottom: 15%;
        left: 5%;
        max-width: 90%;
      }

      .carousel-title {
        font-size: 2rem;
      }

      .carousel-desc {
        font-size: 0.95rem;
      }

      #animeHeroCarousel .carousel-item {
        height: 70vh;
      }
    }

    /* Section Styling */
    .section {
      padding: 60px 0;
    }

    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 35px;
    }

    .section-title {
      font-size: 1.8rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 15px;
      color: var(--text-primary);
    }

    .section-title::before {
      content: '';
      width: 5px;
      height: 35px;
      background: var(--gradient-1);
      border-radius: 5px;
    }

    .see-all-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      border-radius: 10px;
      background: var(--bg-glass);
      border: 1px solid var(--border-color);
      color: var(--text-secondary);
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .see-all-btn:hover {
      background: var(--primary);
      border-color: var(--primary);
      color: white;
    }

    /* Genre Cards */
    .genre-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 20px;
    }

    @media (max-width: 992px) {
      .genre-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 576px) {
      .genre-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .genre-card {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border: 1px solid var(--border-color);
    }

    .genre-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: var(--gradient-1);
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 1;
    }

    .genre-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 20px 40px rgba(139, 92, 246, 0.3);
      border-color: var(--primary);
    }

    .genre-card:hover::before {
      opacity: 0.3;
    }

    .genre-img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .genre-card:hover .genre-img {
      transform: scale(1.1);
    }

    .genre-label {
      position: absolute;
      bottom: 0;
      width: 100%;
      padding: 15px;
      background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
      color: #fff;
      font-weight: 600;
      font-size: 0.95rem;
      text-align: center;
      z-index: 2;
    }

    .genre-icon {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 35px;
      height: 35px;
      border-radius: 50%;
      background: var(--bg-glass);
      backdrop-filter: blur(10px);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      z-index: 2;
      opacity: 0;
      transform: scale(0);
      transition: all 0.3s ease;
    }

    .genre-card:hover .genre-icon {
      opacity: 1;
      transform: scale(1);
    }

    /* Featured Section */
    .featured-section {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      background-image: url('Movies/fightclub.jpg');
      background-size: cover;
      background-position: center;
      margin: 40px 0;
    }

    .featured-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(9, 9, 11, 0.95) 0%, rgba(9, 9, 11, 0.7) 50%, rgba(9, 9, 11, 0.4) 100%);
    }

    .featured-content {
      position: relative;
      z-index: 2;
      padding: 60px 50px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .featured-info {
      max-width: 600px;
    }

    .featured-badge {
      display: inline-block;
      padding: 8px 18px;
      background: var(--gradient-2);
      border-radius: 25px;
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 20px;
    }

    .featured-title {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 20px;
      background: linear-gradient(135deg, #fff 0%, #d4d4d8 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .featured-desc {
      font-size: 1.05rem;
      line-height: 1.8;
      color: var(--text-secondary);
      margin-bottom: 30px;
    }

    .featured-stats {
      display: flex;
      gap: 30px;
      margin-bottom: 30px;
    }

    .stat-item {
      text-align: center;
    }

    .stat-value {
      font-size: 1.8rem;
      font-weight: 700;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .stat-label {
      font-size: 0.8rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-play-featured {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      padding: 18px 35px;
      background: var(--gradient-3);
      border: none;
      border-radius: 50px;
      color: white;
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-play-featured:hover {
      transform: translateY(-3px);
      box-shadow: 0 20px 50px rgba(236, 72, 153, 0.4);
      color: white;
    }

    .featured-poster {
      position: relative;
    }

    .featured-poster img {
      display: none;
    }

    @media (max-width: 768px) {
      .featured-content {
        flex-direction: column;
        padding: 40px 25px;
        text-align: center;
      }

      .featured-title {
        font-size: 2rem;
      }

      .featured-stats {
        justify-content: center;
      }
    }

    /* Movie Cards */
    .movie-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 25px;
    }

    @media (max-width: 1200px) {
      .movie-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    @media (max-width: 992px) {
      .movie-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 576px) {
      .movie-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
      }
    }

    .movie-card {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
    }

    .movie-card::before {
      content: '';
      position: absolute;
      inset: -2px;
      background: var(--gradient-1);
      border-radius: 18px;
      opacity: 0;
      z-index: -1;
      transition: opacity 0.3s ease;
    }

    .movie-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 0 0 40px rgba(139, 92, 246, 0.2);
      border-color: transparent;
    }

    .movie-card:hover::before {
      opacity: 1;
    }

    .movie-poster {
      position: relative;
      overflow: hidden;
    }

    .movie-poster img {
      width: 100%;
      height: 280px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .movie-card:hover .movie-poster img {
      transform: scale(1.1);
    }

    .movie-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(transparent 50%, rgba(0, 0, 0, 0.9) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .movie-card:hover .movie-overlay {
      opacity: 1;
    }

    .play-btn {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: var(--gradient-1);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      transform: scale(0);
      transition: transform 0.3s ease;
    }

    .movie-card:hover .play-btn {
      transform: scale(1);
    }

    .movie-duration {
      position: absolute;
      bottom: 10px;
      right: 10px;
      padding: 5px 10px;
      background: rgba(0, 0, 0, 0.8);
      border-radius: 6px;
      font-size: 0.75rem;
      color: white;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .movie-card:hover .movie-duration {
      opacity: 1;
    }

    .movie-badge {
      position: absolute;
      top: 12px;
      left: 12px;
      padding: 5px 12px;
      background: var(--gradient-2);
      border-radius: 6px;
      font-size: 0.7rem;
      font-weight: 700;
      color: white;
      text-transform: uppercase;
    }

    .movie-info {
      padding: 18px;
    }

    .movie-title {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 10px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .movie-rating {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.9rem;
    }

    .movie-rating i {
      color: var(--accent);
    }

    .movie-rating span {
      color: var(--text-secondary);
    }

    /* Quick Action Buttons on Cards */
    .card-actions {
      position: absolute;
      top: 12px;
      right: 12px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      opacity: 0;
      transform: translateX(20px);
      transition: all 0.3s ease;
    }

    .movie-card:hover .card-actions {
      opacity: 1;
      transform: translateX(0);
    }

    .card-action-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .card-action-btn:hover {
      background: var(--primary);
      transform: scale(1.1);
    }
    /* Divider */
    .divider {
      height: 1px;
      background: linear-gradient(to right, transparent, var(--border-color), transparent);
      margin: 0;
      border: none;
    }

    /* Animation Classes */
    .fade-in {
      animation: fadeIn 0.6s ease forwards;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Hover Glow Effect */
    .glow-on-hover {
      position: relative;
    }

    .glow-on-hover::after {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      opacity: 0;
      transition: opacity 0.3s ease;
      box-shadow: 0 0 30px rgba(139, 92, 246, 0.5);
    }

    .glow-on-hover:hover::after {
      opacity: 1;
    }
  </style>
</head>

<body>

  <!-- Sidebar Navbar -->
  <nav class="sidebar app-sidebar">
    <div class="logo">
      <a href="login.php">
        <img src="Geometric Blue _A_ Logo Design.png" alt="Logo">
      </a>
    </div>
    <ul class="nav-links">
      <li><a href="index.php" class="active"><i class="bi bi-house-door-fill"></i> <span>Home</span></a></li>
      <li><a href="search.html"><i class="bi bi-search"></i> <span>Discover</span></a></li>
      <li><a href="genere.html?genre=top-rated"><i class="bi bi-trophy"></i> <span>Top Rated</span></a></li>
      <li><a href="movies.html"><i class="bi bi-film"></i> <span>Movies</span></a></li>
      <li><a href="webseries.html"><i class="bi bi-tv"></i> <span>Web Series</span></a></li>
      <li><a href="contact.html"><i class="bi bi-headset"></i> <span>Support</span></a></li>
      <?php if ($isLoggedIn): ?>
        <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a></li>
      <?php else: ?>
        <li><a href="login.php"><i class="bi bi-person-circle"></i> <span>Account</span></a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Hero Carousel -->
    <div id="animeHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000" data-bs-pause="false">
      <div class="hero-welcome">
        <div class="welcome-text">Welcome, <span><?php echo htmlspecialchars($displayName); ?></span></div>
      </div>
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#animeHeroCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#animeHeroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#animeHeroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#animeHeroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        <button type="button" data-bs-target="#animeHeroCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
      </div>

      <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active" style="background-image: url('Movies/memory.jpg');">
          <div class="dark-gradient"></div>
          <div class="carousel-overlay">
            <span class="carousel-badge"><i class="bi bi-fire"></i> Featured</span>
            <h2 class="carousel-title">Memory</h2>
            <p class="carousel-desc">A contract killer struggles with memory loss while completing his final mission.</p>
            <div class="carousel-meta">
              <span class="meta-badge rating"><i class="bi bi-star-fill"></i> 7.3</span>
              <span class="meta-badge">2022</span>
              <span class="meta-badge">1h 53min</span>
              <span class="meta-badge">Action</span>
            </div>
            <a href="player.html" class="btn-watch"><i class="bi bi-play-fill"></i> Watch Now</a>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item" style="background-image: url('Movies/fightclub.jpg');">
          <div class="dark-gradient"></div>
          <div class="carousel-overlay">
            <span class="carousel-badge"><i class="bi bi-trophy-fill"></i> Top Rated</span>
            <h2 class="carousel-title">Fight Club</h2>
            <p class="carousel-desc">Psychological thriller that digs into identity, anarchy, and the modern man.</p>
            <div class="carousel-meta">
              <span class="meta-badge rating"><i class="bi bi-star-fill"></i> 8.8</span>
              <span class="meta-badge">1999</span>
              <span class="meta-badge">2h 19min</span>
              <span class="meta-badge">Drama</span>
            </div>
            <a href="player.html" class="btn-watch"><i class="bi bi-play-fill"></i> Watch Now</a>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item" style="background-image: url('Movies/shawshank.jpg');">
          <div class="dark-gradient"></div>
          <div class="carousel-overlay">
            <span class="carousel-badge"><i class="bi bi-award-fill"></i> Classic</span>
            <h2 class="carousel-title">The Shawshank Redemption</h2>
            <p class="carousel-desc">A powerful story of hope, friendship, and redemption behind prison walls.</p>
            <div class="carousel-meta">
              <span class="meta-badge rating"><i class="bi bi-star-fill"></i> 9.3</span>
              <span class="meta-badge">1994</span>
              <span class="meta-badge">2h 22min</span>
              <span class="meta-badge">Drama</span>
            </div>
            <a href="player.html" class="btn-watch"><i class="bi bi-play-fill"></i> Watch Now</a>
          </div>
        </div>

        <!-- Slide 4 -->
        <div class="carousel-item" style="background-image: url('Movies/inception.jpg');">
          <div class="dark-gradient"></div>
          <div class="carousel-overlay">
            <span class="carousel-badge"><i class="bi bi-rocket-takeoff-fill"></i> Sci-Fi</span>
            <h2 class="carousel-title">Inception</h2>
            <p class="carousel-desc">A mind-bending heist through layers of dreams and reality.</p>
            <div class="carousel-meta">
              <span class="meta-badge rating"><i class="bi bi-star-fill"></i> 8.8</span>
              <span class="meta-badge">2010</span>
              <span class="meta-badge">2h 28min</span>
              <span class="meta-badge">Sci-Fi</span>
            </div>
            <a href="player.html" class="btn-watch"><i class="bi bi-play-fill"></i> Watch Now</a>
          </div>
        </div>

        <!-- Slide 5 -->
        <div class="carousel-item" style="background-image: url('Movies/dark knight.jpg');">
          <div class="dark-gradient"></div>
          <div class="carousel-overlay">
            <span class="carousel-badge"><i class="bi bi-lightning-fill"></i> Action</span>
            <h2 class="carousel-title">The Dark Knight</h2>
            <p class="carousel-desc">Batman faces the Joker in a gripping battle for Gotham's soul.</p>
            <div class="carousel-meta">
              <span class="meta-badge rating"><i class="bi bi-star-fill"></i> 9.0</span>
              <span class="meta-badge">2008</span>
              <span class="meta-badge">2h 32min</span>
              <span class="meta-badge">Action</span>
            </div>
            <a href="player.html" class="btn-watch"><i class="bi bi-play-fill"></i> Watch Now</a>
          </div>
        </div>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#animeHeroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#animeHeroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>

    <!-- Genre Section -->
    <section class="section container">
      <div class="section-header">
        <h2 class="section-title">Browse by Genre</h2>
      </div>

      <div class="genre-grid">
        <a href="genere.html?genre=action" class="genre-link">
          <div class="genre-card">
            <img src="Genere/action.jpg" alt="Action" class="genre-img">
            <div class="genre-icon"><i class="bi bi-lightning-fill"></i></div>
            <div class="genre-label">Action</div>
          </div>
        </a>

        <a href="genere.html?genre=adventure" class="genre-link">
          <div class="genre-card">
            <img src="Genere/adventure.webp" alt="Adventure" class="genre-img">
            <div class="genre-icon"><i class="bi bi-compass-fill"></i></div>
            <div class="genre-label">Adventure</div>
          </div>
        </a>

        <a href="genere.html?genre=comedy" class="genre-link">
          <div class="genre-card">
            <img src="Genere/comedy.jpg" alt="Comedy" class="genre-img">
            <div class="genre-icon"><i class="bi bi-emoji-laughing-fill"></i></div>
            <div class="genre-label">Comedy</div>
          </div>
        </a>

        <a href="genere.html?genre=sci-fi" class="genre-link">
          <div class="genre-card">
            <img src="Genere/sci-fi.jpg" alt="sci-fi" class="genre-img">
            <div class="genre-icon"><i class="bi bi-rocket-takeoff-fill"></i></div>
            <div class="genre-label">Sci-Fi</div>
          </div>
        </a>

        <a href="genere.html?genre=rom-com" class="genre-link">
          <div class="genre-card">
            <img src="Genere/romcom.webp" alt="Rom-com" class="genre-img">
            <div class="genre-icon"><i class="bi bi-heart-fill"></i></div>
            <div class="genre-label">Rom-Com</div>
          </div>
        </a>

        <a href="genere.html?genre=psychological" class="genre-link">
          <div class="genre-card">
            <img src="Genere/psycho.jpg" alt="Psychological" class="genre-img">
            <div class="genre-icon"><i class="bi bi-brain"></i></div>
            <div class="genre-label">Psychological</div>
          </div>
        </a>
      </div>
    </section>

    <hr class="divider">

    <!-- Featured Movie Section -->
    <section class="section container">
      <div class="section-header">
        <h2 class="section-title">Featured Movie</h2>
      </div>

      <div class="featured-section">
        <div class="featured-overlay"></div>
        <div class="featured-content">
          <div class="featured-info">
            <span class="featured-badge"><i class="bi bi-gem"></i> Editor's Choice</span>
            <h2 class="featured-title">Fight Club</h2>
            <p class="featured-desc">
              "An insomniac office worker, trapped in the monotony of corporate life,
              crosses paths with a mysterious soap salesman. Together, they form an underground fight club as a release from
              their frustrations, but what begins as raw, physical rebellion soon spirals into a dangerous game of identity, chaos, and the unraveling of reality itself."
            </p>
            <div class="featured-stats">
              <div class="stat-item">
                <div class="stat-value">8.8</div>
                <div class="stat-label">IMDB</div>
              </div>
              <div class="stat-item">
                <div class="stat-value">2h 19m</div>
                <div class="stat-label">Duration</div>
              </div>
              <div class="stat-item">
                <div class="stat-value">1999</div>
                <div class="stat-label">Year</div>
              </div>
            </div>
            <a href="player.html" class="btn-play-featured">
              <i class="bi bi-play-fill"></i> Play Now
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- New Movies -->
    <section class="section container">
      <div class="section-header">
        <h2 class="section-title">New Releases</h2>
        <a href="genere.html?genre=new-releases" class="see-all-btn">View All <i class="bi bi-chevron-right"></i></a>
      </div>

      <div class="movie-grid">
        <!-- Card 1 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/eternal.jfif" alt="Eternal Sunshine">
            <span class="movie-badge">New</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">1h 48min</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Eternal Sunshine of the Spotless Mind</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.7/5</span>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Genere/sci-fi.jpg" alt="Stranger Things">
            <span class="movie-badge">Series</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">4 Seasons</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Stranger Things</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.6/5</span>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/inception.jpg" alt="Inception">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">2h 28min</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Inception</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.8/5</span>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/500days.jpg" alt="500 Days of Summer">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">1h 35min</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">500 Days of Summer</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>3.9/5</span>
            </div>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/oldboy.png" alt="Oldboy">
            <span class="movie-badge">Korean</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">2h 0min</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Oldboy</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.4/5</span>
            </div>
          </div>
        </div>

        <!-- Card 6 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/isaw.jpg" alt="I Saw the Devil">
            <span class="movie-badge">Thriller</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">2h 21min</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">I Saw the Devil</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.5/5</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest Added -->
    <section class="section container" id="latestAddedSection">
      <div class="section-header">
        <h2 class="section-title">Trending Now</h2>
        <a href="search.html" class="see-all-btn">View All <i class="bi bi-chevron-right"></i></a>
      </div>
      <div class="movie-grid" id="latestAddedGrid"></div>
    </section>

    <!-- Trending Now -->
    <section class="section container">
      <div class="section-header">
        <h2 class="section-title">Web-Series</h2>
        <a href="genere.html?genre=trending" class="see-all-btn">View All <i class="bi bi-chevron-right"></i></a>
      </div>

      <div class="movie-grid">
        <!-- Card 1 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/final.jpg" alt="Final Destination">
            <span class="movie-badge">Hot</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">1h 38min</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Final Destination</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.2/5</span>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="WebSeries/got.jpg" alt="Game of Thrones">
            <span class="movie-badge">#1 Series</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">8 Seasons</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Game Of Thrones</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.9/5</span>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="WebSeries/sopranos.jpg" alt="The Sopranos">
            <span class="movie-badge">Legendary</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">6 Seasons</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">The Sopranos</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.6/5</span>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="WebSeries/breaking.jpg" alt="Breaking Bad">
            <span class="movie-badge">GOAT</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">5 Seasons</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Breaking Bad</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.8/5</span>
            </div>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="WebSeries/dexter.jpg" alt="Dexter">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">8 Seasons</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Dexter</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.3/5</span>
            </div>
          </div>
        </div>

        <!-- Card 6 -->
        <div class="movie-card">
          <div class="movie-poster">
            <img src="WebSeries/you.jpg" alt="You">
            <span class="movie-badge">Trending</span>
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">4 Seasons</div>
            <div class="card-actions">
              <button class="card-action-btn"><i class="bi bi-heart"></i></button>
              <button class="card-action-btn"><i class="bi bi-plus"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">You</h5>
            <div class="movie-rating">
              <i class="bi bi-star-fill"></i>
              <span>4.1/5</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <!-- Branding -->
          <div class="col-md-4 mb-4">
            <div class="footer-brand">
              <h5>Aura<span>.stream</span></h5>
              <p>Stream your favorite movies anytime, anywhere. The ultimate destination for entertainment.</p>
            </div>
          </div>

          <!-- Navigation Links -->
          <div class="col-md-4 mb-4">
            <h6 class="footer-title">Quick Links</h6>
            <ul class="footer-links">
              <li><a href="index.php"><i class="bi bi-chevron-right"></i> Home</a></li>
              <li><a href="search.html"><i class="bi bi-chevron-right"></i> Discover</a></li>
              <li><a href="contact.html"><i class="bi bi-chevron-right"></i> Support</a></li>
              <li><a href="login.php"><i class="bi bi-chevron-right"></i> Account</a></li>
            </ul>
          </div>

          <!-- Social Media / Contact -->
          <div class="col-md-4 mb-4">
            <h6 class="footer-title">Connect With Us</h6>
            <div class="social-links">
              <a href="https://www.facebook.com/"><i class="bi bi-facebook"></i> Facebook</a>
              <a href="https://x.com/?lang=en"><i class="bi bi-twitter-x"></i> Twitter</a>
              <a href="https://mail.google.com/mail/u/0/"><i class="bi bi-envelope-fill"></i> support@aura.stream</a>
            </div>
          </div>
        </div>

        <div class="footer-bottom">
          © 2025 <span>Aura.stream</span> — All rights reserved. Made with <i class="bi bi-heart-fill" style="color: #ef4444;"></i>
        </div>
      </div>
    </footer>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function buildPlayerUrl({
      title,
      tmdbId,
      mediaType,
      season,
      episode
    } = {}) {
      const cleanTmdb = (tmdbId || "").toString().trim();
      const type = (mediaType || "movie").toString().trim().toLowerCase();
      if (cleanTmdb) {
        const params = new URLSearchParams();
        params.set("tmdbId", cleanTmdb);
        params.set("type", type === "tv" ? "tv" : "movie");
        if (title) params.set("title", title);
        if (type === "tv") {
          if (season) params.set("season", season);
          if (episode) params.set("episode", episode);
          if (!params.has("episodeSelector")) params.set("episodeSelector", "true");
          if (!params.has("nextEpisode")) params.set("nextEpisode", "true");
        }
        return `player.html?${params.toString()}`;
      }
      if (title) {
        return `player.html?title=${encodeURIComponent(title)}`;
      }
      return "player.html";
    }

    function titleToPlayerUrl(title) {
      return buildPlayerUrl({
        title
      });
    }

    // Add animation on scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('fade-in');
        }
      });
    }, observerOptions);

    document.querySelectorAll('.movie-card, .genre-card').forEach(card => {
      observer.observe(card);
    });

    // Card like button functionality
    document.querySelectorAll('.card-action-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const icon = btn.querySelector('i');
        if (icon.classList.contains('bi-heart')) {
          icon.classList.remove('bi-heart');
          icon.classList.add('bi-heart-fill');
          icon.style.color = '#ef4444';
        } else if (icon.classList.contains('bi-heart-fill')) {
          icon.classList.remove('bi-heart-fill');
          icon.classList.add('bi-heart');
          icon.style.color = '';
        }
      });
    });

    // Wire carousel buttons
    async function fetchApiMoviesSafe() {
      try {
        const res = await fetch('api_movies.php?action=list');
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        return data.movies || [];
      } catch (err) {
        return [];
      }
    }

    function normalizeTitle(value) {
      return (value || "").trim().toLowerCase();
    }

    function buildPlayerUrlFromMovie(movie, fallbackTitle = "") {
      if (!movie) return titleToPlayerUrl(fallbackTitle);
      return buildPlayerUrl({
        title: movie.title || fallbackTitle,
        tmdbId: movie.tmdb_id || movie.tmdbId,
        mediaType: movie.media_type || movie.mediaType,
        season: movie.season,
        episode: movie.episode
      });
    }

    async function wireHeroAndFeaturedButtons() {
      const apiMovies = await fetchApiMoviesSafe();
      const movieMap = new Map(apiMovies.map(m => [normalizeTitle(m.title), m]));

      document.querySelectorAll('.carousel-item').forEach(item => {
        const titleEl = item.querySelector('.carousel-title');
        const btn = item.querySelector('.btn-watch');
        if (titleEl && btn) {
          const title = titleEl.textContent.trim();
          const match = movieMap.get(normalizeTitle(title));
          btn.setAttribute('href', buildPlayerUrlFromMovie(match, title));
        }
      });

      const featuredTitle = document.querySelector('.featured-title');
      const featuredBtn = document.querySelector('.btn-play-featured');
      if (featuredTitle && featuredBtn) {
        const title = featuredTitle.textContent.trim();
        const match = movieMap.get(normalizeTitle(title));
        featuredBtn.setAttribute('href', buildPlayerUrlFromMovie(match, title));
      }
    }

    function wireMovieCardClicks(scope = document) {
      scope.querySelectorAll('.movie-card').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', () => {
          const titleEl = card.querySelector('.movie-title');
          const tmdbId = card.dataset.tmdbId || '';
          const mediaType = card.dataset.mediaType || '';
          const season = card.dataset.season || '';
          const episode = card.dataset.episode || '';
          const title = titleEl ? titleEl.textContent.trim() : '';
          window.location.href = buildPlayerUrl({
            title,
            tmdbId,
            mediaType,
            season,
            episode
          });
        });
      });
    }

    wireMovieCardClicks();

    async function loadHomeSections() {
      try {
        const res = await fetch('api_movies.php?action=list');
        const data = await res.json();
        const movies = data.movies || [];
        if (!movies.length) return;

        const latest = [...movies]
          .sort((a, b) => (b.id || 0) - (a.id || 0))
          .slice(0, 6);

        const movieOnly = movies.filter(m => (m.media_type || m.mediaType || 'movie').toLowerCase() !== 'tv');
        const seriesOnly = movies.filter(m => (m.media_type || m.mediaType || '').toLowerCase() === 'tv');

        const newArrivalsTitles = [
          "breaking bad",
          "dexter",
          "final destination",
          "game of thrones",
          "i saw the devil"
        ];
        const webSeriesTitles = [
          "breaking bad",
          "sopranos",
          "game of thrones",
          "dexter",
          "you"
        ];

        function pickByTitles(list, titles) {
          const map = new Map(list.map(item => [normalizeTitle(item.title), item]));
          return titles.map(t => map.get(normalizeTitle(t))).filter(Boolean);
        }

        const newArrivals = pickByTitles(movies, newArrivalsTitles);
        const webSeries = pickByTitles(movies, webSeriesTitles).map(item => ({
          ...item,
          media_type: (item.media_type || item.mediaType || "tv").toString().toLowerCase() === "tv" ? "tv" : "tv"
        }));

        const newArrivalsGrid = document.getElementById('newArrivalsGrid');
        if (newArrivalsGrid) {
          renderMovieGrid('newArrivalsGrid', newArrivals.length ? newArrivals : latest, 'New', 'NewArrivals');
          wireMovieCardClicks(newArrivalsGrid);
        }

        const moviesGrid = document.getElementById('moviesGrid');
        if (moviesGrid) {
          renderMovieGrid('moviesGrid', movieOnly.slice(0, 6), '', 'Movies');
          wireMovieCardClicks(moviesGrid);
        }

        const seriesGrid = document.getElementById('seriesGrid');
        if (seriesGrid) {
          renderMovieGrid('seriesGrid', webSeries.length ? webSeries : seriesOnly.slice(0, 6), '', 'WebSeries');
          wireMovieCardClicks(seriesGrid);
        }

        const topRatedGrid = document.getElementById('topRatedGrid');
        if (topRatedGrid) {
          const sortedByRating = [...movieOnly]
            .sort((a, b) => {
              const aScore = parseFloat(a.rating_score || a.ratingScore || 0);
              const bScore = parseFloat(b.rating_score || b.ratingScore || 0);
              return bScore - aScore;
            })
            .slice(0, 6);
          renderMovieGrid('topRatedGrid', sortedByRating.length ? sortedByRating : latest, 'Top', 'Movies');
          wireMovieCardClicks(topRatedGrid);
        }

        const newReleasesGrid = document.getElementById('newReleasesGrid');
        if (newReleasesGrid) {
          renderMovieGrid('newReleasesGrid', latest, 'New', 'Movies');
          wireMovieCardClicks(newReleasesGrid);
        }

        const latestAddedGrid = document.getElementById('latestAddedGrid');
        if (latestAddedGrid) {
          renderMovieGrid('latestAddedGrid', latest, 'New', 'Movies');
          wireMovieCardClicks(latestAddedGrid);
        }
      } catch (err) {
        return;
      }
    }

    function buildPosterCandidates(title, folderPath, posterValue) {
      const candidates = [];
      const normalizedTitle = (title || "").toString().trim().toLowerCase();
      const baseVariants = new Set();
      if (normalizedTitle) {
        baseVariants.add(normalizedTitle);
        if (normalizedTitle.startsWith("the ")) {
          baseVariants.add(normalizedTitle.slice(4));
        }
        baseVariants.add(normalizedTitle.replace(/\s+/g, ""));
        baseVariants.add(normalizedTitle.replace(/\s+/g, "-"));
        baseVariants.add(normalizedTitle.replace(/\s+/g, "_"));
        if (normalizedTitle.startsWith("the ")) {
          const noThe = normalizedTitle.slice(4);
          baseVariants.add(noThe.replace(/\s+/g, ""));
          baseVariants.add(noThe.replace(/\s+/g, "-"));
          baseVariants.add(noThe.replace(/\s+/g, "_"));
        }
      }

      if (posterValue) {
        candidates.push(posterValue);
      }

      const extensions = [".jpg", ".jpeg", ".png", ".jfif", ".webp"];
      baseVariants.forEach(variant => {
        extensions.forEach(ext => {
          if (folderPath) {
            candidates.push(`${folderPath}/${variant}${ext}`);
          }
        });
      });

      return candidates.filter(Boolean);
    }

    function applyPosterFallback(img) {
      if (!img) return;
      const raw = img.getAttribute("data-fallbacks");
      if (!raw) return;
      const fallbacks = raw.split("|").filter(Boolean);
      if (!fallbacks.length) return;
      const next = fallbacks.shift();
      img.setAttribute("data-fallbacks", fallbacks.join("|"));
      img.src = next;
    }

    function renderMovieGrid(containerId, items, badgeLabel, folderPath) {
      const container = document.getElementById(containerId);
      if (!container) return;
      container.innerHTML = '';
      items.forEach(movie => {
        const badge = badgeLabel ? `<span class="movie-badge">${badgeLabel}</span>` : '';
        const rating = movie.rating_score || movie.ratingScore || '4.0';
        const duration = movie.duration || '';
        const title = movie.title || 'Unknown';
        const posterCandidates = buildPosterCandidates(title, folderPath, movie.poster || "");
        const poster = posterCandidates.length ? posterCandidates[0] : 'Movies/fightclub.jpg';
        const posterFallbacks = posterCandidates.slice(1).join("|");
        const tmdbId = movie.tmdb_id || movie.tmdbId || '';
        const mediaType = movie.media_type || movie.mediaType || 'movie';
        const season = movie.season || '';
        const episode = movie.episode || '';
        const card = document.createElement('div');
        card.className = 'movie-card';
        if (tmdbId) card.dataset.tmdbId = tmdbId;
        if (mediaType) card.dataset.mediaType = mediaType;
        if (season) card.dataset.season = season;
        if (episode) card.dataset.episode = episode;
        card.innerHTML = `
        <div class="movie-poster">
          <img src="${poster}" alt="${title}" data-fallbacks="${posterFallbacks}" onerror="applyPosterFallback(this)">
          ${badge}
          <div class="movie-overlay">
            <div class="play-btn"><i class="bi bi-play-fill"></i></div>
          </div>
          <div class="movie-duration">${duration}</div>
          <div class="card-actions">
            <button class="card-action-btn"><i class="bi bi-heart"></i></button>
            <button class="card-action-btn"><i class="bi bi-plus"></i></button>
          </div>
        </div>
        <div class="movie-info">
          <h5 class="movie-title">${title}</h5>
          <div class="movie-rating">
            <i class="bi bi-star-fill"></i>
            <span>${rating}/5</span>
          </div>
        </div>
      `;
        container.appendChild(card);
      });
    }

    loadHomeSections();
    wireHeroAndFeaturedButtons();
  </script>

</body>

</html>







