<?php

require_once 'includes/data_getter.inc.php';
$events = $_SESSION['new_events'];
if (!isset($_SESSION["user_id"])) {
    header("Location: pages/login.php");
}

if (isset($_SESSION["user_id"]) && isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "admin") {
    header("Location: pages/dashboard.php");
}

//  echo "<pre>";
//  print_r($_SESSION);
//  echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/home_page.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container d-flex align-items-center">
            <a class="navbar-brand" href="#"><img src="assets/logo.png" alt="Logo"></a>
            <input class="form-control search-bar" type="search" placeholder="Search an event..." aria-label="Search">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="#">HOME</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="pages/events_page.php">EVENTS</a></li>
                    <li class="nav-item">
                        <a href="pages/account_page.php"><img
                                src="<?= htmlspecialchars('uploads/' . $_SESSION['user_img']) ?>" class="nav-profile"
                                alt="Profile"></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <img src="assets/banner.png" class="img-fluid" alt="Banner">
    </div>

    <div class="container">
        <h2 class="section-title">WHAT'S NEW?</h2>
    </div>



    <div class="carousel slide" id="carouselEvent" data-bs-wrap="true" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($events as $index => $event): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="10000"
                    data-event-id="<?= $event['event_id'] ?>">
                    <img src="<?= 'uploads/events/' . $event['image_1'] ?>" class="d-block mx-auto">
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselEvent" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#carouselEvent" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

        <div class="carousel-indicators">
            <?php foreach ($events as $index => $event): ?>
                <button type="button" class="<?= $index === 0 ? 'active' : '' ?>" data-bs-target="#carouselEvent"
                    data-bs-slide-to="<?= $index ?>">
                    <img src="<?= 'uploads/events/' . $event['image_1'] ?>">
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Event Details -->
    <div class="containerEvent mt-5">
        <div id="eventDetails" class="card p-3 shadow-sm">
            <div class="row g-3 align-items-center">
                <div class="col-md-4 text-center">
                    <img id="eventImage" src="<?= 'uploads/events/' . $events[0]['image_1'] ?>"
                        class="img-fluid rounded" alt="Event Image">
                </div>
                <div class="col-md-8">
                    <h2 id="eventTitle" class="fw-bold"><?= $events[0]['title'] ?></h2>
                    <p id="eventDescription"><?= $events[0]['description'] ?></p>
                    <p><em id="eventDate">Date: <?= date("F j, Y h:iA", strtotime($events[0]['start_datetime'])) ?></em>
                        <br>

                        <strong>Price: <span
                                id="eventPrice">₱<?= number_format($events[0]['price'], 2) ?></span></strong>
                    </p>
                    <a href="#" class="btn btn-warning text-white fw-bold">See More</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Convert PHP session data to a JavaScript object
            const eventsData = <?php echo json_encode($events); ?>;

            const carousel = document.querySelector("#carouselEvent");
            const eventTitle = document.querySelector("#eventTitle");
            const eventDescription = document.querySelector("#eventDescription");
            const eventDate = document.querySelector("#eventDate");
            const eventPrice = document.querySelector("#eventPrice");
            const eventImage = document.querySelector("#eventImage");

            // Function to format the date as "January 20, 2024 12:30AM"
            function formatDateTime(dateString) {
                const date = new Date(dateString.replace(" ", "T")); // Ensure correct parsing
                return date.toLocaleString("en-US", {
                    month: "long",
                    day: "numeric",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                    hour12: true,
                }).replace(" ", " ").replace(":00", ""); // Removes space before AM/PM and ":00" if exact hour
            }

            // Update event details when the carousel slides
            carousel.addEventListener("slid.bs.carousel", function () {
                const activeItem = carousel.querySelector(".carousel-item.active");
                const eventId = parseInt(activeItem.getAttribute("data-event-id"), 10); // Ensure integer

                // Find the corresponding event data
                const event = eventsData.find(e => parseInt(e.event_id, 10) === eventId);

                if (event) {
                    eventImage.src = "uploads/events/" + event.image_1; // Ensure correct path
                    eventTitle.textContent = event.title;
                    eventDescription.textContent = event.description;
                    eventDate.textContent = "Date: " + formatDateTime(event.start_datetime);
                    eventPrice.textContent = "₱" + parseFloat(event.price).toFixed(2);
                }
            });
        });
    </script>






    <!-- About Section -->
    <div class="container mt-5 text-center">
        <h2 class="fw-bold text-uppercase text-teal">About</h2>
        <p class="fst-italic">
            easyTickIT is a website dedicated to eliminating paper usage in school events.
            It streamlines the process of attending an event: from buying a ticket to entering the event premises.
        </p>
    </div>

    <!-- Footer -->
    <div class="text-center py-3 mt-4 bg-dark text-light">
        <small>Copyright © easyTickIT 2025. All rights reserved. Designed by Back(log) Burners.</small>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Event data stored in a JavaScript object
            const eventsData = {
                1: {
                    title: "PLV Valentines 2025",
                    description: "PLV celebrates love and friendship this Valentine’s Day on campus! Join us for a fun-filled event with music, games, and sweet treats.",
                    date: "February 14, 2025",
                    price: "$80.00",
                    image: "assets/event1.png"
                },
                2: {
                    title: "PLV Sports Fest 2025",
                    description: "Experience the thrill of sports and competition! Join us in our annual PLV Sports Fest with exciting games and team challenges.",
                    date: "March 10, 2025",
                    price: "Free",
                    image: "assets/event2.png"
                },
                3: {
                    title: "PLV Tech Expo 2025",
                    description: "Discover the latest innovations in technology at PLV Tech Expo! Featuring student projects, guest speakers, and interactive exhibits.",
                    date: "April 20, 2025",
                    price: "$50.00",
                    image: "assets/event3.png"
                }
            };

            const carousel = document.querySelector("#carouselEvent");
            const eventTitle = document.querySelector("#eventTitle");
            const eventDescription = document.querySelector("#eventDescription");
            const eventDate = document.querySelector("#eventDate");
            const eventPrice = document.querySelector("#eventPrice");
            const eventImage = document.querySelector("#eventImage");

            carousel.addEventListener("slid.bs.carousel", function () {
                const activeItem = carousel.querySelector(".carousel-item.active");
                const eventID = activeItem.getAttribute("data-event-id");
                const eventData = eventsData[eventID];

                if (eventData) {
                    eventTitle.textContent = eventData.title;
                    eventDescription.textContent = eventData.description;
                    eventDate.textContent = "Date: " + eventData.date;
                    eventPrice.textContent = eventData.price;
                    eventImage.src = eventData.image;
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const eventImage = document.getElementById("eventImage");
            const eventDetails = document.getElementById("eventDetails");

            eventImage.addEventListener("load", function () {
                const colorThief = new ColorThief();

                try {
                    const dominantColor = colorThief.getColor(eventImage);

                    // Function to mix color with white (percentage determines lightness)
                    function blendWithWhite([r, g, b], percentage) {
                        return [
                            Math.round(r + (255 - r) * percentage),
                            Math.round(g + (255 - g) * percentage),
                            Math.round(b + (255 - b) * percentage)
                        ];
                    }

                    // Create a light pastel version (70% blended with white)
                    const lightColor = blendWithWhite(dominantColor, 0.7);

                    // Apply the reversed gradient (strong color at top, white at bottom)
                    const gradient = `linear-gradient(to bottom, rgb(${dominantColor.join(",")}), rgb(255,255,255))`;

                    eventDetails.style.background = gradient;
                    eventDetails.style.color = "black"; // Ensures readability

                } catch (error) {
                    console.error("Error extracting color:", error);
                }
            });

            // If image is already cached, manually trigger load event
            if (eventImage.complete) {
                eventImage.dispatchEvent(new Event("load"));
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>