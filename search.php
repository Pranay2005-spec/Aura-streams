<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>sukuna.stream</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons CDN -->
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
    }

    @media (max-width: 768px) {
.main-content {
      }
    }

    .section-heading {
      font-size: 1.8rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 15px;
      color: var(--text-primary);
      margin-bottom: 20px;
    }

    .section-heading::before {
      content: '';
      width: 5px;
      height: 35px;
      background: var(--gradient-1);
      border-radius: 5px;
    }

    .search-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 30vh;
      padding: 20px;
    }

    .search-box {
      display: flex;
      align-items: center;
      background: var(--bg-glass);
      border-radius: 50px;
      padding: 15px 25px;
      width: 100%;
      max-width: 700px;
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
      backdrop-filter: blur(12px);
    }

    .search-box:hover,
    .search-box:focus-within {
      border-color: var(--primary);
      background: rgba(255, 255, 255, 0.05);
    }

    .search-box i {
      font-size: 1.5rem;
      color: var(--text-secondary);
      margin-right: 15px;
    }

    .search-box input {
      flex: 1;
      background: none;
      border: none;
      outline: none;
      font-size: 1.1rem;
      color: var(--text-primary);
    }

    .search-box input::placeholder {
      color: var(--text-muted);
    }

    .movie-card {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      height: 100%;
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

    .movie-rating i {
      color: var(--accent);
    }

    .movie-rating span {
      color: var(--text-secondary);
    }

    @media (max-width: 768px) {
      .movie-poster {
        height: auto;
      }
    }

    #searchResults .movie-card {
      transition: all 0.3s ease-in-out;
    }

    #searchResults {
      transition: opacity 0.2s ease;
    }

    .is-hidden {
      display: none !important;
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
    <li><a href="search.php" class="active"><i class="bi bi-search"></i> <span>Discover</span></a></li>
    <li><a href="genere.php?genre=top-rated"><i class="bi bi-trophy"></i> <span>Top Rated</span></a></li>
    <li><a href="movies.php"><i class="bi bi-film"></i> <span>Movies</span></a></li>
    <li><a href="webseries.php"><i class="bi bi-tv"></i> <span>Web Series</span></a></li>
    <li><a href="watchlater.php"><i class="bi bi-bookmark-heart"></i> <span>Watch Later</span></a></li>
    <li><a href="account.php"><i class="bi bi-person-circle"></i> <span>Login</span></a></li>
  </ul>
</nav>

<main class="main-content">
<!--search bar -->
<div class="search-container">
    <div class="search-box">
      <i class="bi bi-search"></i>
      <input type="text" id="mainSearchInput" placeholder="Search ">
    </div>
  </div>

  <!-- anime cards section-->
 <section id="mightLikeSection">
  
  <div class="container my-5">
  <div class="row g-4 justify-content-center">
    <h3 class="section-heading">Might like</h3>
    <!-- Card 1 -->
    <div class="col-6 col-md-2">
      <a href="player.php?title=Eternal%20Sunshine%20of%20the%20Spotless%20Mind" class="text-decoration-none">
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/eternal.jfif" alt="Eternal Sunshine of the Spotless Mind">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">2h 30min</div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Eternal Sunshine of the Spotless Mind</h5>
            <div class="movie-rating"><i class="bi bi-star-fill"></i><span>4.0/5</span></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Card 2 -->
    <div class="col-6 col-md-2">
      <a href="player.php?title=Stranger%20Things" class="text-decoration-none">
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Genere/sci-fi.jpg" alt="Stranger Things">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">2h 30min</div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Stranger Things</h5>
            <div class="movie-rating"><i class="bi bi-star-fill"></i><span>4.0/5</span></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Card 3 -->
    <div class="col-6 col-md-2">
      <a href="player.php?title=Inception" class="text-decoration-none">
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/inception.jpg" alt="Inception">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">2h 12min</div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Inception</h5>
            <div class="movie-rating"><i class="bi bi-star-fill"></i><span>4.0/5</span></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Card 4 -->
    <div class="col-6 col-md-2">
      <a href="player.php?title=500%20Days%20of%20Summer" class="text-decoration-none">
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/500days.jpg" alt="500 Days of Summer">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">2h 32min</div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">500 Days of Summer</h5>
            <div class="movie-rating"><i class="bi bi-star-fill"></i><span>4.0/5</span></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Card 5 -->
    <div class="col-6 col-md-2">
      <a href="player.php?title=Oldboy" class="text-decoration-none">
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/oldboy.png" alt="Oldboy">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">2h 1min</div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">Oldboy</h5>
            <div class="movie-rating"><i class="bi bi-star-fill"></i><span>4.0/5</span></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Card 6 -->
    <div class="col-6 col-md-2">
      <a href="player.php?title=I%20Saw%20the%20Devil" class="text-decoration-none">
        <div class="movie-card">
          <div class="movie-poster">
            <img src="Movies/isaw.jpg" alt="I Saw the Devil">
            <div class="movie-overlay">
              <div class="play-btn"><i class="bi bi-play-fill"></i></div>
            </div>
            <div class="movie-duration">1h 45min</div>
          </div>
          <div class="movie-info">
            <h5 class="movie-title">I Saw the Devil</h5>
            <div class="movie-rating"><i class="bi bi-star-fill"></i><span>4.0/5</span></div>
          </div>
        </div>
      </a>
    </div>

  </div>
</div>
</section>

  
<!-- Footer -->
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
 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const baseMovies = [
  { title: "Memories of Murder", image: "Movies/memory.jpg", duration: "2h 12min" },
  { title: "Fight Club", image: "Movies/fightclub.jpg", duration: "2h 19min" },
  { title: "The Shawshank Redemption", image: "Movies/shawshank.jpg", duration: "2h 22min" },
  { title: "Inception", image: "Movies/inception.jpg", duration: "2h 28min" },
  { title: "The Dark Knight", image: "Movies/dark knight.jpg", duration: "2h 32min" },
  { title: "The Godfather", image: "Movies/the godfather.png", duration: "2h 55min" },
  { title: "The Lord Of The Rings", image: "WebSeries/lord of rings.jpg", duration: "3h 21min" },
  { title: "Se7en", image: "Movies/seven.jpg", duration: "2h 7min" },
  { title: "Whiplash", image: "Movies/whiplash.jpg", duration: "1h 45min" },
  { title: "Eternal Sunshine of the Spotless Mind", image: "Movies/eternal.jfif", duration: "1h 48min" },
  { title: "Stranger Things", image: "Genere/sci-fi.jpg", duration: "4 Seasons" },
  { title: "500 Days of Summer", image: "Movies/500days.jpg", duration: "1h 35min" },
  { title: "Oldboy", image: "Movies/oldboy.png", duration: "2h 0min" },
  { title: "I Saw the Devil", image: "Movies/isaw.jpg", duration: "2h 21min" },
  { title: "Final Destination", image: "Movies/final.jpg", duration: "1h 38min" },
  { title: "Game Of Thrones", image: "WebSeries/got.jpg", duration: "8 Seasons" },
  { title: "Sopranos", image: "WebSeries/sopranos.jpg", duration: "6 Seasons" },
  { title: "Breaking Bad", image: "WebSeries/breaking.jpg", duration: "5 Seasons" },
  { title: "Dexter", image: "WebSeries/dexter.jpg", duration: "8 Seasons" },
  { title: "You", image: "WebSeries/you.jpg", duration: "4 Seasons" },
  { title: "Memory", image: "Movies/memory.jpg", duration: "1h 53min" },
  { title: "American Psycho", image: "Genere/psycho.jpg", duration: "1h 42min" },
  { title: "Rom-Com Picks", image: "Genere/romcom.webp", duration: "1h 50min" }
  ];

  const API_URL = "api_movies.php";
  const DEFAULT_POSTER = "Movies/fightclub.jpg";
  const WEB_SERIES_POSTERS = new Set(["breaking.jpg", "dexter.jpg", "got.jpg", "lord of rings.jpg", "sopranos.jpg", "you.jpg"]);
  const GENERE_POSTERS = new Set(["action.jpg", "adventure.webp", "comedy.jpg", "psycho.jpg", "romcom.webp", "sci-fi.jpg"]);

  function normalizeTitle(value) {
    return (value || "").trim().toLowerCase();
  }

  function resolvePosterPath(posterPath) {
    const raw = (posterPath || "").toString().trim().replace(/\\/g, "/");
    if (!raw) return DEFAULT_POSTER;
    const lower = raw.toLowerCase();
    if (lower.startsWith("http://") || lower.startsWith("https://") || lower.startsWith("data:")) {
      return raw;
    }
    if (!lower.startsWith("thumbnails/")) {
      return raw;
    }
    const filename = raw.split("/").pop();
    const fileKey = (filename || "").toLowerCase();
    if (!filename) return DEFAULT_POSTER;
    if (WEB_SERIES_POSTERS.has(fileKey)) return `WebSeries/${filename}`;
    if (GENERE_POSTERS.has(fileKey)) return `Genere/${filename}`;
    return `Movies/${filename}`;
  }

  let movies = [...baseMovies];

  function mergeMovie(base, api) {
    if (!api) return base;
    const merged = { ...base };
    Object.keys(api).forEach(key => {
      const value = api[key];
      if (value === null || value === undefined) return;
      if (typeof value === "string" && value.trim() === "") return;
      merged[key] = value;
    });
    return merged;
  }

  async function loadMovies() {
    try {
      const res = await fetch(`${API_URL}?action=list`);
      const data = await res.json();
      const baseMap = new Map();
      baseMovies.forEach(movie => baseMap.set(normalizeTitle(movie.title), movie));
      const apiMovies = (data.movies || []).map(item => {
        const key = normalizeTitle(item.title);
        const base = baseMap.get(key) || {};
        const merged = mergeMovie(base, {
          title: item.title,
          image: item.poster,
          duration: item.duration
        });
        return {
          title: merged.title || item.title,
          image: resolvePosterPath(merged.image || base.image || DEFAULT_POSTER),
          duration: merged.duration || base.duration || "2h 0m"
        };
      });
      if (apiMovies.length) {
        const merged = new Map();
        baseMovies.forEach(movie => merged.set(normalizeTitle(movie.title), movie));
        apiMovies.forEach(movie => merged.set(normalizeTitle(movie.title), movie));
        movies = Array.from(merged.values());
      }
    } catch (err) {
      movies = [...baseMovies];
    }
  }

  loadMovies();

  const searchInput = document.getElementById("mainSearchInput");
  const mightLikeSection = document.getElementById("mightLikeSection");
  const isMobileSearch = window.matchMedia("(max-width: 768px)").matches;

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
        if (!params.has("episodeSelector")) params.set("episodeSelector", "true");
        if (!params.has("nextEpisode")) params.set("nextEpisode", "true");
      }
      return `player.php?${params.toString()}`;
    }
    if (title) {
      return `player.php?title=${encodeURIComponent(title)}`;
    }
    return "player.php";
  }

  // Create result container element
  const resultContainer = document.createElement("div");
  resultContainer.className = "container my-4";
  resultContainer.id = "searchResults";
  searchInput.closest(".search-container").after(resultContainer);
  resultContainer.classList.add("is-hidden");

  function setSearchState({ showResults, showMightLike }) {
    resultContainer.classList.toggle("is-hidden", !showResults);
    if (mightLikeSection) {
      mightLikeSection.classList.toggle("is-hidden", !showMightLike);
    }
  }

  function renderSearchResults(query) {
    const cleanQuery = (query || "").trim().toLowerCase();
    resultContainer.innerHTML = "";

    if (!cleanQuery) {
      setSearchState({ showResults: false, showMightLike: !isMobileSearch });
      return;
    }

    const filtered = movies.filter(movie =>
      (movie.title || "").toLowerCase().includes(cleanQuery)
    );

    if (filtered.length === 0) {
      resultContainer.innerHTML = `
        <h4 class="text-light text-center">No results found for "<span style="color:#e50914;">${cleanQuery}</span>"</h4>
      `;
      setSearchState({ showResults: true, showMightLike: true });
      return;
    }

    const MAX_SEARCH_RESULTS = 12;
    const visibleResults = filtered.slice(0, MAX_SEARCH_RESULTS);

    let output = `
      <h3 class="section-heading">Search Results</h3>
      <div class="row g-4 justify-content-center">
    `;

    visibleResults.forEach(movie => {
      const tmdbId = movie.tmdb_id || movie.tmdbId || '';
      const mediaType = movie.media_type || movie.mediaType || 'movie';
      const season = movie.season || '';
      const episode = movie.episode || '';
      const poster = resolvePosterPath(movie.poster || movie.image || DEFAULT_POSTER);
      const href = buildPlayerUrl({
        title: movie.title,
        tmdbId,
        mediaType,
        season,
        episode
      });
      output += `
        <div class="col-6 col-md-2">
          <a href="${href}" class="text-decoration-none" data-tmdb-id="${tmdbId}" data-media-type="${mediaType}" data-season="${season}" data-episode="${episode}">
            <div class="movie-card">
              <div class="movie-poster">
                <img src="${poster}" alt="${movie.title}" onerror="this.onerror=null;this.src='Movies/fightclub.jpg';">
                <div class="movie-overlay">
                  <div class="play-btn"><i class="bi bi-play-fill"></i></div>
                </div>
                <div class="movie-duration">${movie.duration || ""}</div>
              </div>
              <div class="movie-info">
                <h5 class="movie-title">${movie.title}</h5>
                <div class="movie-rating">
                  <i class="bi bi-star-fill"></i>
                  <span>${movie.rating_score || movie.ratingScore || "4.0"}/5</span>
                </div>
              </div>
            </div>
          </a>
        </div>
      `;
    });

    output += `</div>`;
    if (filtered.length > MAX_SEARCH_RESULTS) {
      output += `
        <p class="text-center text-secondary mt-3">Showing first ${MAX_SEARCH_RESULTS} results</p>
      `;
    }
    resultContainer.innerHTML = output;
    setSearchState({ showResults: true, showMightLike: false });
  }

  let searchDebounceTimer = null;
  searchInput.addEventListener("input", function () {
    clearTimeout(searchDebounceTimer);
    const value = searchInput.value;
    searchDebounceTimer = setTimeout(() => renderSearchResults(value), 120);
  });

  renderSearchResults(searchInput.value || "");

  // Make static cards clickable
  document.querySelectorAll('.movie-card').forEach(card => {
    card.style.cursor = 'pointer';
    card.addEventListener('click', () => {
      const titleEl = card.querySelector('.movie-title');
      const link = card.closest('a');
      const tmdbId = link ? link.dataset.tmdbId : '';
      const mediaType = link ? link.dataset.mediaType : '';
      const season = link ? link.dataset.season : '';
      const episode = link ? link.dataset.episode : '';
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
</script>

<nav class="mobile-bottom-nav" aria-label="Mobile navigation">
  <a href="index.php"><i class="bi bi-house-door-fill"></i><span>Home</span></a>
  <a href="search.php" class="active"><i class="bi bi-search"></i><span>Search</span></a>
  <a href="account.php"><i class="bi bi-person-circle"></i><span>Profile</span></a>
</nav>
</body>
</html> 
































