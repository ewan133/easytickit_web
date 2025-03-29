<?php
require_once 'includes/data_getter.inc.php';

if (!isset($_SESSION["user_id"])) {
  header("Location: pages/login.php");
  exit();
}

if (isset($_SESSION["user_id"]) && isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "admin") {
  header("Location: pages/dashboard.php");
  exit();
}

$events = $_SESSION['new_events'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Homepage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/home_page.css" />
</head>

<body>
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg shadow-sm" style="background-color: #004E51;">
    <div class="container" style="max-width: 1200px;">

      <!-- Logo -->
      <a class="navbar-brand" href="#">
        <img src="assets/logo.png" alt="Logo" width="55" height="55">
      </a>

      <!-- Toggle Button for Mobile -->
      <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navigation Menu -->
      <div class="collapse navbar-collapse justify-content-between" id="navbarNav">

        <!-- Search Bar (Hidden on Small Screens) -->
        <div class="d-none d-lg-block flex-grow-1 mx-3">
          <form action="pages/events_page.php" method="get">
            <input class="form-control" type="search" name="search" placeholder="Search an event..."
              aria-label="Search">
          </form>
        </div>


        <!-- Navigation Links -->
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item mx-2">
            <a class="nav-link text-white" href="#">HOME</a>
          </li>
          <li class="nav-item mx-2">
            <a class="nav-link text-white" href="pages/events_page.php">EVENTS</a>
          </li>
          <!-- Profile Section -->
          <li class="nav-item ms-3 d-flex align-items-center">
            <a href="pages/account_page.php" class="d-flex align-items-center text-decoration-none text-white">
              <img src="<?= htmlspecialchars('uploads/' . $_SESSION['user_img']) ?>"
                class="d-none d-lg-block rounded-circle border border-white shadow-sm me-2" alt="Profile" width="45"
                height="45">
              <span class="d-block d-lg-none fw-normal">PROFILE</span> <!-- Visible only on mobile -->
            </a>
          </li>
        </ul>

        <!-- Search Bar (Visible in Mobile View) -->
        <div class="d-lg-none mt-3">
          <input class="form-control" type="search" placeholder="Search an event..." aria-label="Search">
        </div>
      </div>
    </div>
  </nav>

  <!-- Fix Mobile Navbar Close Issue -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var navLinks = document.querySelectorAll(".navbar-nav a");
      var navBar = document.querySelector(".navbar-collapse");

      navLinks.forEach(function (link) {
        link.addEventListener("click", function () {
          if (navBar.classList.contains("show")) {
            new bootstrap.Collapse(navBar).toggle();
          }
        });
      });
    });
  </script>






  <!-- BANNER -->
  <div class="container-fluid p-0">
    <img src="assets/banner.png" class="img-fluid" alt="Banner" />
  </div>

  <!-- SECTION TITLE -->
  <div class="container">
    <h2 class="section-title">WHAT'S NEW?</h2>
  </div>

  <!-- CAROUSEL -->
  <div id="carouselEvent" class="carousel slide containerEvent mt-5" data-bs-ride="carousel" data-bs-interval="10000">
    <div class="carousel-indicators">
      <?php foreach ($events as $index => $event): ?>
        <button type="button" data-bs-target="#carouselEvent" data-bs-slide-to="<?= $index ?>"
          class="<?= $index === 0 ? 'active' : '' ?>"></button>
      <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
      <?php foreach ($events as $index => $event): ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
          <div class="card p-3 shadow-sm">
            <div class="row g-3 align-items-center">
              <div class="col-md-4 text-center">
                <img src="uploads/events/<?= htmlspecialchars($event['image_1']) ?>" class="img-fluid rounded eventImage"
                  alt="<?= htmlspecialchars($event['title']) ?>" crossorigin="anonymous">
              </div>
              <div class="col-md-8">
                <h2 class="fw-bold"><?= htmlspecialchars($event['title']) ?></h2>
                <p><?= htmlspecialchars($event['description']) ?></p>
                <p><em>Date: <?= htmlspecialchars($event['start_datetime']) ?></em><br><strong>Price:
                    ₱<?= htmlspecialchars($event['price']) ?></strong></p>
                <a href="pages/detailed_events.php?event_id=<?= $event['event_id'] ?>"
                  class="btn btn-warning text-white fw-bold">See More</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselEvent" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselEvent" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- ABOUT -->
  <div class="container mt-5 text-center">
    <h2 class="fw-bold text-uppercase text-teal">About</h2>
    <p class="fst-italic">
      easyTickIT is a website dedicated to eliminating paper usage in school events. It streamlines the process of
      attending an event: from buying a ticket to entering the event premises.
    </p>
  </div>

  <!-- FOOTER -->
  <div class="text-center py-3 mt-4 bg-dark text-light">
    <small>Copyright © easyTickIT 2025. All rights reserved. Designed by Back(log) Burners.</small>
  </div>

  <!-- SCRIPTS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // ✅ Color Thief gradient logic
      const colorThief = new ColorThief();
      const carousel = document.getElementById("carouselEvent");

      function applyGradient(image, container) {
        if (image.complete) {
          try {
            const dominantColor = colorThief.getColor(image);

            function blendWithWhite([r, g, b], percentage) {
              return [
                Math.round(r + (255 - r) * percentage),
                Math.round(g + (255 - g) * percentage),
                Math.round(b + (255 - b) * percentage)
              ];
            }

            const lightColor = blendWithWhite(dominantColor, 0.7);
            const gradient = `linear-gradient(to bottom, rgb(${dominantColor.join(",")}), rgb(255,255,255))`;

            container.style.background = gradient;
            container.style.color = "black";
          } catch (error) {
            console.error("Color extraction failed:", error);
          }
        } else {
          image.addEventListener('load', function () {
            applyGradient(image, container);
          });
        }
      }

      // Apply initial gradient to first item
      setTimeout(() => {
        const firstItem = document.querySelector(".carousel-item.active");
        if (firstItem) {
          const firstImage = firstItem.querySelector(".eventImage");
          const firstContainer = firstItem.querySelector(".card");
          applyGradient(firstImage, firstContainer);
        }
      }, 500);

      // Update gradient when slide changes
      carousel.addEventListener("slid.bs.carousel", function () {
        const activeItem = carousel.querySelector(".carousel-item.active");
        const activeImage = activeItem.querySelector(".eventImage");
        const activeContainer = activeItem.querySelector(".card");

        applyGradient(activeImage, activeContainer);
      });
    });
  </script>
</body>

</html>