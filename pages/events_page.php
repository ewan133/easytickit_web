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
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container d-flex align-items-center">
            <a class="navbar-brand" href="#"><img src="../assets/logo.png" alt="Logo"></a>
            <input class="form-control search-bar" type="search" placeholder="Search an event..." aria-label="Search">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="../index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link text-white active" href="#">EVENTS</a></li>
                    <li class="nav-item">
                        <a href="account_page.php">
                            <img src="<?= htmlspecialchars('../uploads/' . ($_SESSION['user_img'] ?? 'default-profile.jpg')) ?>"
                                class="nav-profile" alt="Profile">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="section-title mb-4">UPCOMING EVENTS</h2>
        <div class="row" id="events-container">
            <?php if (!empty($_SESSION['active_events'])): ?>
                <?php foreach ($_SESSION['active_events'] as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card event-card" data-id="<?= $event['event_id'] ?>">
                            <img src="<?= htmlspecialchars('../uploads/events/' . $event['image_1']) ?>"
                                class="card-img-top img-fluid" alt="<?= htmlspecialchars($event['title']) ?>">
                            <div class="card-body p-2">
                                <h5 class="card-title text-start fw-bold"><?= htmlspecialchars($event['title']) ?></h5>

                                <p class="text-start"><strong>Date:</strong>
                                    <?= date("F d, Y", strtotime($event['start_datetime'])) ?></p>
                                <p class="text-start"><strong>Price:</strong> ₱<?= number_format($event['price'], 2) ?></p>
                                <a href="detailed_events.php?event_id=<?= $event['event_id'] ?>"
                                    class="btn btn-warning text-white">See More</a>
                            </div>

                        </div>
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

            // Search Functionality
            searchInput.addEventListener("input", function () {
                const searchText = searchInput.value.toLowerCase();

                eventCards.forEach(card => {
                    const title = card.querySelector(".card-title").textContent.toLowerCase();
                    const description = card.querySelector(".card-text").textContent.toLowerCase();

                    if (title.includes(searchText) || description.includes(searchText)) {
                        card.style.display = "block"; // Show matching event
                    } else {
                        card.style.display = "none"; // Hide non-matching event
                    }
                });
            });
        });
    </script>

</body>

</html>