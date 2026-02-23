<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us - Aura.stream</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"/>
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

    .contact-container {
      max-width: 900px;
      margin: 80px auto;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 0 0 30px rgba(139, 92, 246, 0.2);
      backdrop-filter: blur(10px);
    }

    .contact-heading {
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

    .btn-submit {
      background: var(--gradient-1);
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 30px;
      transition: 0.3s;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(139, 92, 246, 0.35);
    }

    .contact-info {
      margin-top: 30px;
    }

    .contact-info i {
      font-size: 1.2rem;
      margin-right: 8px;
      color: var(--accent);
    }

    /* Modal */
    .modal-glass {
      background: rgba(18, 18, 18, 0.95);
      border-radius: 16px;
      box-shadow: 0 0 25px rgba(229, 9, 20, 0.5);
      backdrop-filter: blur(12px);
    }

    .modal-icon-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .checkmark {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: block;
      stroke-width: 2;
      stroke: #e50914;
      stroke-miterlimit: 10;
      box-shadow: 0 0 15px #e50914a8;
      animation: checkmark-scale 0.5s ease-in-out forwards;
    }

    .checkmark-circle {
      stroke-dasharray: 166;
      stroke-dashoffset: 166;
      stroke: #e50914;
      stroke-width: 2;
      fill: none;
      animation: circle-animate 0.6s ease-in-out forwards;
    }

    .checkmark-check {
      transform-origin: 50% 50%;
      stroke-dasharray: 48;
      stroke-dashoffset: 48;
      stroke: #e50914;
      stroke-width: 4;
      animation: check-animate 0.4s 0.6s ease-in-out forwards;
    }

    @keyframes checkmark-scale {
      0% {
        transform: scale(0.8);
        opacity: 0;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    @keyframes circle-animate {
      to {
        stroke-dashoffset: 0;
      }
    }

    @keyframes check-animate {
      to {
        stroke-dashoffset: 0;
      }
    }
  </style>
  <link rel="stylesheet" href="css/mobile.css?v=6">
</head>
<body>

  <!-- Sidebar -->
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
    <li><a href="account.php"><i class="bi bi-person-circle"></i> <span>Login</span></a></li>
  </ul>
</nav>

  <main class="main-content">

  <!-- Contact Form -->
  <div class="container contact-container">
    <h2 class="contact-heading">Contact Us</h2>
    <p class="text-center mb-4">Have questions, suggestions, or need support? Fill out the form below or reach out to us directly!</p>

    <form>
      <div class="mb-3">
        <label for="name" class="form-label">Your Name</label>
        <input type="text" class="form-control" id="name" placeholder="Enter your name">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Your Email</label>
        <input type="email" class="form-control" id="email" placeholder="Enter your email">
      </div>

      <div class="mb-3">
        <label for="message" class="form-label">Your Message</label>
        <textarea class="form-control" id="message" rows="5" placeholder="Write your message here..."></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="btn-submit">Send Message</button>
      </div>
    </form>

    <!-- Contact Info -->
    <div class="contact-info text-center mt-5">
      <p><i class="bi bi-envelope"></i> Aurastream@gmail.com</p>
      <p><i class="bi bi-telephone"></i> +91 980000000</p>
      <p><i class="bi bi-geo-alt"></i> Maharashtra, India</p>
    </div>
  </div>

  <!-- Thank You Modal -->
  <div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-glass text-white text-center p-4 border-0">
        <div class="modal-icon-wrapper mb-3">
          <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark-check" fill="none" d="M14 27l7 7 17-17"/>
          </svg>
        </div>
        <h4 class="fw-bold mb-2">Thank you for your message!</h4>
        <p class="mb-3">We appreciate your feedback and will get back to you soon.</p>
        <button type="button" class="btn btn-outline-light px-4 rounded-pill" data-bs-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>

  </main>

  <!-- JS: Validation and Modal Control -->
  <script>
    document.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault();

      const name = document.getElementById('name');
      const email = document.getElementById('email');
      const message = document.getElementById('message');
      let isValid = true;

      // Reset styles
      [name, email, message].forEach(field => {
        field.style.borderColor = '#333';
      });

      if (name.value.trim() === '') {
        name.style.borderColor = 'red';
        isValid = false;
      }

      if (email.value.trim() === '' || !validateEmail(email.value)) {
        email.style.borderColor = 'red';
        isValid = false;
      }

      if (message.value.trim() === '') {
        message.style.borderColor = 'red';
        isValid = false;
      }

      if (isValid) {
        const modal = new bootstrap.Modal(document.getElementById('thankYouModal'));
        modal.show();
        this.reset();
      } else {
        alert("Please fill out all the fields correctly.");
      }
    });

    function validateEmail(email) {
      const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return pattern.test(email.toLowerCase());
    }
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<nav class="mobile-bottom-nav" aria-label="Mobile navigation">
  <a href="index.php"><i class="bi bi-house-door-fill"></i><span>Home</span></a>
  <a href="search.php"><i class="bi bi-search"></i><span>Search</span></a>
  <a href="account.php"><i class="bi bi-person-circle"></i><span>Profile</span></a>
</nav>
</body>
</html>
























