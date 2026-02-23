<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Watch Later - Aura.stream</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/sidebar.css?v=9">
  <link rel="stylesheet" href="css/footer.css?v=9">
  <style>
    :root {
      --primary: #8b5cf6;
      --secondary: #06b6d4;
      --accent: #f59e0b;
      --bg-dark: #09090b;
      --bg-card: rgba(24, 24, 27, 0.8);
      --bg-glass: rgba(255, 255, 255, 0.03);
      --border-color: rgba(255, 255, 255, 0.08);
      --text-primary: #fafafa;
      --text-secondary: #a1a1aa;
      --gradient-1: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
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
        radial-gradient(ellipse at 80% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }
    a { text-decoration: none; color: inherit; }
    .section-heading {
      font-size: 1.6rem;
      font-weight: 800;
      margin: 10px 0 25px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .section-heading::before {
      content: '';
      width: 5px;
      height: 32px;
      background: var(--gradient-1);
      border-radius: 5px;
    }
    .movie-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 25px;
    }
    @media (max-width: 1200px) { .movie-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 992px) { .movie-grid { grid-template-columns: repeat(3, 1fr); } }

    @media (max-width: 768px) {
      .movie-grid {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 10px;
        padding: 0 2px 8px;
        scroll-snap-type: x mandatory;
      }

      .movie-grid .movie-card {
        flex: 0 0 clamp(130px, 38vw, 165px);
        max-width: clamp(130px, 38vw, 165px);
        scroll-snap-align: start;
        border-radius: 14px;
      }

      .movie-poster {
        aspect-ratio: 2 / 3;
      }

      .movie-poster img {
        height: 100%;
      }

      .movie-info {
        padding: 8px 8px 7px;
      }

      .movie-duration {
        display: none;
      }

      .movie-title {
        font-size: 0.82rem;
      }
    }

    @media (max-width: 480px) {
      .movie-poster img {
        height: 100%;
      }

      .play-btn {
        width: 40px;
        height: 40px;
        font-size: 1rem;
      }

      .movie-title {
        font-size: 0.76rem;
      }

      .movie-rating {
        font-size: 0.74rem;
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

    .movie-poster { position: relative; overflow: hidden; }
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

    .card-actions {
      position: absolute;
      top: 12px;
      right: 12px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      z-index: 3;
    }
    .card-action-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(0, 0, 0, 0.7);
      border: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      color: white;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .card-action-btn:hover { background: #ef4444; border-color: #ef4444; }

    .movie-info {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      padding: 14px 14px 12px;
      background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(8, 8, 10, 0.88) 45%, rgba(8, 8, 10, 0.98) 100%);
      transform: translateY(100%);
      opacity: 0;
      transition: transform 0.32s ease, opacity 0.32s ease;
      backdrop-filter: blur(4px);
    }

    .movie-card:hover .movie-info {
      transform: translateY(0);
      opacity: 1;
    }

    @media (hover: none) {
      .movie-info {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .movie-title {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 6px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .movie-rating {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.86rem;
    }
    .movie-rating i { color: var(--accent); }
    .movie-rating span { color: var(--text-secondary); }
    .state-box {
      border: 1px solid var(--border-color);
      background: var(--bg-glass);
      border-radius: 14px;
      padding: 20px;
      color: var(--text-secondary);
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
      <li><a href="watchlater.php" class="active"><i class="bi bi-bookmark-heart"></i> <span>Watch Later</span></a></li>
      <li><a href="account.php"><i class="bi bi-person-circle"></i> <span>Login</span></a></li>
    </ul>
  </nav>

  <main class="main-content">
    <h3 class="section-heading">Watch Later</h3>
    <div id="statusBox" class="state-box mb-4">Loading your watch list...</div>
    <div id="watchLaterGrid" class="movie-grid"></div>

    <footer>
  <div class="container">
    <div class="footer-compact">
      <div class="footer-brand">
        <h5>Aura<span>.stream</span></h5>
        <p>Stream your favorite movies anytime, anywhere. The ultimate destination for entertainment.</p>
      </div>
      <div class="social-links" aria-label="Social links">
        <a href="https://www.facebook.com/"><i class="bi bi-facebook"></i> Facebook</a>
        <a href="https://x.com/?lang=en"><i class="bi bi-twitter-x"></i> Twitter</a>
        <a href="https://mail.google.com/mail/u/0/"><i class="bi bi-envelope-fill"></i> support@aura.stream</a>
      </div>
    </div>
  </div>
</footer>
  </main>

  <script>
    const API_URL = "api_movies.php";

    function buildPlayerUrl({ title, tmdbId, mediaType, season, episode } = {}) {
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
          params.set("episodeSelector", "true");
          params.set("nextEpisode", "true");
        }
        return `player.php?${params.toString()}`;
      }
      return title ? `player.php?title=${encodeURIComponent(title)}` : "player.php";
    }

    async function removeWatchLater(movie) {
      const res = await fetch(`${API_URL}?action=watch_later_remove`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          title: movie.title,
          tmdb_id: movie.tmdb_id || "",
          media_type: movie.media_type || "movie",
          season: movie.season || 0,
          episode: movie.episode || 0
        })
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.error || `HTTP ${res.status}`);
      }
      return res.json();
    }

    function renderGrid(items) {
      const grid = document.getElementById("watchLaterGrid");
      const statusBox = document.getElementById("statusBox");
      grid.innerHTML = "";
      if (!items.length) {
        statusBox.textContent = "No saved titles yet. Tap the heart icon on any movie or series to add it here.";
        return;
      }
      statusBox.style.display = "none";

      items.forEach(movie => {
        const poster = movie.poster || "Movies/fightclub.jpg";
        const rating = movie.rating_score || "4.0";
        const duration = movie.duration || "";
        const href = buildPlayerUrl({
          title: movie.title,
          tmdbId: movie.tmdb_id,
          mediaType: movie.media_type,
          season: movie.season,
          episode: movie.episode
        });

        const card = document.createElement("div");
        card.className = "movie-card";
        card.innerHTML = `
          <div class="movie-poster">
            <img src="${poster}" alt="${movie.title}" onerror="this.onerror=null;this.src='Movies/fightclub.jpg';">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">${duration}</div>
            <div class="card-actions">
              <button class="card-action-btn" title="Remove from Watch Later"><i class="bi bi-trash"></i></button>
            </div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">${movie.title}</h5>
            <div class="movie-rating"><i class="bi bi-star-fill"></i><span>${rating}/5</span></div>
          </div>
        `;

        card.addEventListener("click", () => { window.location.href = href; });

        const removeBtn = card.querySelector(".card-action-btn");
        removeBtn.addEventListener("click", async (e) => {
          e.preventDefault();
          e.stopPropagation();
          try {
            await removeWatchLater(movie);
            card.remove();
            if (!document.querySelector("#watchLaterGrid .movie-card")) {
              const status = document.getElementById("statusBox");
              status.style.display = "block";
              status.textContent = "No saved titles yet. Tap the heart icon on any movie or series to add it here.";
            }
          } catch (err) {
            alert(err.message || "Failed to remove");
          }
        });

        grid.appendChild(card);
      });
    }

    async function loadWatchLater() {
      const statusBox = document.getElementById("statusBox");
      try {
        const res = await fetch(`${API_URL}?action=watch_later_list`);
        if (res.status === 401) {
          statusBox.innerHTML = `Please login to see your Watch Later list. <a href="login.php" style="color:#8b5cf6;">Login</a>`;
          return;
        }
        const data = await res.json();
        renderGrid(Array.isArray(data.movies) ? data.movies : []);
      } catch (err) {
        statusBox.textContent = "Unable to load watch list right now.";
      }
    }

    loadWatchLater();
  </script>
  

<nav class="mobile-bottom-nav" aria-label="Mobile navigation">
  <a href="index.php"><i class="bi bi-house-door-fill"></i><span>Home</span></a>
  <a href="search.php"><i class="bi bi-search"></i><span>Search</span></a>
  <a href="account.php"><i class="bi bi-person-circle"></i><span>Profile</span></a>
</nav>
</body>
</html>














