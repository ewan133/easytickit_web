<?php
require_once '../includes/data_getter.inc.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home_page.css">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm" style="background-color: #004E51;">
        <div class="container" style="max-width: 1200px;">

            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="../assets/logo.png" alt="Logo" width="55" height="55">
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
                    <div class="d-none d-lg-block flex-grow-1 mx-3">
                        <input class="form-control search-bar" type="search" placeholder="Search an event..."
                            aria-label="Search">
                    </div>
                </div>

                <!-- Navigation Links -->
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white" href="../index.php">HOME</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white" href="events_page.php">EVENTS</a>
                    </li>
                    <!-- Profile Section -->
                    <li class="nav-item ms-3 d-flex align-items-center">
                        <a href="account_page.php" class="d-flex align-items-center text-decoration-none text-white">
                            <img src="<?= htmlspecialchars('../uploads/' . $_SESSION['user_img']) ?>"
                                class="d-none d-lg-block rounded-circle border border-white shadow-sm me-2"
                                alt="Profile" width="45" height="45">
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

    <div class="container mt-4" style="min-height: 100vh;">

        <h2 class="section-title mb-4">UPCOMING EVENTS</h2>
        <div class="row" id="events-container">
            <?php if (!empty($_SESSION['active_events'])): ?>
                <?php foreach ($_SESSION['active_events'] as $event): ?>
                    <div class="col-md-4 mb-4">
                        <!-- Wrap the entire card inside a link to make it clickable -->
                        <a href="detailed_events.php?event_id=<?= $event['event_id'] ?>" class="card-link"
                            style="text-decoration: none;">
                            <div class="card event-card" data-id="<?= $event['event_id'] ?>">
                                <img src="<?= htmlspecialchars('../uploads/events/' . $event['image_1']) ?>"
                                    class="card-img-top img-fluid" alt="<?= htmlspecialchars($event['title']) ?>">
                                <div class="card-body p-2 text-center">
                                    <!-- Center the title -->
                                    <h5 class="card-title fw-bold" style="font-size: 1.5rem;">
                                        <?= htmlspecialchars($event['title']) ?>
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No active events available.</p>
            <?php endif; ?>
        </div>
    </div>



    <div class="footer text-center py-3 mt-4 bg-dark text-light">
        <small>Copyright © easyTickIT 2025. All rights reserved. Designed by Back(log) Burners.</small>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.querySelector(".search-bar");
            const eventsContainer = document.getElementById("events-container");
            const eventCards = eventsContainer.querySelectorAll(".col-md-4");

            // Get the 'search' query parameter from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search'); // Get 'search' parameter value

            // If 'search' query exists in URL, set the search input value to that
            if (searchQuery) {
                searchInput.value = searchQuery;
            }

            // Search Functionality (on input)
            function performSearch(searchText) {
                const lowercasedSearchText = searchText.toLowerCase();

                // Update the URL to reflect the search term
                if (lowercasedSearchText) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', lowercasedSearchText);
                    window.history.pushState({}, '', url); // Update the URL without reloading
                } else {
                    // Remove 'search' parameter if input is cleared
                    const url = new URL(window.location.href);
                    url.searchParams.delete('search');
                    window.history.pushState({}, '', url);
                }

                // Loop through each card and hide/show based on search
                eventCards.forEach(card => {
                    const title = card.querySelector(".card-title").textContent.toLowerCase();
                    const description = card.querySelector(".card-text") ? card.querySelector(".card-text").textContent.toLowerCase() : "";

                    if (title.includes(lowercasedSearchText) || description.includes(lowercasedSearchText)) {
                        card.style.display = "block"; // Show matching event
                    } else {
                        card.style.display = "none"; // Hide non-matching event
                    }
                });
            }

            // If search query exists, perform the search immediately
            if (searchQuery) {
                performSearch(searchQuery);
            }

            // Event listener for input (search bar)
            searchInput.addEventListener("input", function () {
                const searchText = searchInput.value;
                performSearch(searchText);
            });
        });



    </script>

</body>

</html>