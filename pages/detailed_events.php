<?php
require_once '../includes/data_getter.inc.php';

// Check if event_id is set in the URL
if (!isset($_GET['event_id'])) {
    header("Location: events_page.php");
    exit();
}

// Fetch all active events
$_SESSION['active_events'] = getAllActiveEvents($pdo);

// Find the selected event based on event_id
$event_id = $_GET['event_id'];
$selected_event = null;

foreach ($_SESSION['active_events'] as $event) {
    if ($event['event_id'] == $event_id) {
        $selected_event = $event;
        break;
    }
}

// Redirect if event not found
if (!$selected_event) {
    header("Location: events_page.php");
    exit();
}

// Check if user has already reserved this event
$isReserved = false;

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM Reservations WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$_SESSION['user_id'], $event_id]);

    if ($stmt->rowCount() > 0) {
        $isReserved = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
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
                    <li class="nav-item"><a class="nav-link text-white active"
                            href="../pages/events_page.php">EVENTS</a></li>
                    <li class="nav-item">
                        <a href="../pages/account_page.php">
                            <img src="<?= htmlspecialchars('../uploads/' . ($_SESSION['user_img'] ?? 'default-profile.jpg')) ?>"
                                class="nav-profile" alt="Profile">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container event-details-container">
        <div class="row g-4">
            <!-- Left: Image -->
            <div class="col-md-5">
                <img src="<?= htmlspecialchars('../uploads/events/' . $selected_event['image_1']) ?>"
                    alt="<?= htmlspecialchars($selected_event['title']) ?>" class="event-img">
            </div>

            <!-- Right: Details -->
            <div class="col-md-7 text-dark">
                <h1 class="event-title text-dark"><?= htmlspecialchars($selected_event['title']) ?></h1>
                <p class="event-description text-dark"><?= htmlspecialchars($selected_event['description']) ?></p>
                <p class="event-date-price text-dark">
                    <strong>Date:</strong> <?= date("F d, Y", strtotime($selected_event['start_datetime'])) ?>
                </p>
                <p class="event-date-price text-dark">
                    <strong>Price:</strong> ₱<?= number_format($selected_event['price'], 2) ?>
                </p>
            </div>
        </div>

        <!-- OTHER PHOTOS SECTION -->
        <div class="other-photos-section text-center">
            <h2 class="other-photos-title">OTHER PHOTOS</h2>
            <div class="other-photos-container">
                <?php for ($i = 2; $i <= 4; $i++): ?>
                    <?php if (!empty($selected_event["image_$i"])): ?>
                        <img src="<?= htmlspecialchars('../uploads/events/' . $selected_event["image_$i"]) ?>"
                            class="other-photo">
                    <?php endif; ?>
                <?php endfor; ?>
            </div>

            <!-- Reservation Button (Moved Below Images) -->
            <div class="mt-4">
                <?php if ($isReserved): ?>
                    <button class="btn btn-danger reserve-btn mt-3" data-bs-toggle="modal" data-bs-target="#unreserveModal">
                        Unreserve
                    </button>
                <?php else: ?>
                    <button class="btn btn-warning reserve-btn mt-3" data-bs-toggle="modal" data-bs-target="#reserveModal">
                        Reserve Now
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer text-center py-3 mt-4 bg-dark text-light">
        <small>&copy; easyTickIT 2025. All rights reserved. Designed by Back(log) Burners.</small>
    </div>

    <!-- Reserve Confirmation Modal -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 text-center">
                <div class="modal-body">
                    <h2 class="fw-bold text-primary">Confirm Reservation</h2>
                    <p>Are you sure you want to reserve this event?</p>
                    <div class="d-flex justify-content-center gap-4 mt-4">
                        <a href="../includes/reserve_event.inc.php?event_id=<?= $event_id ?>"
                            class="btn btn-success btn-lg px-4">
                            Yes
                        </a>
                        <button type="button" class="btn btn-danger btn-lg px-4" data-bs-dismiss="modal">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unreserve Confirmation Modal -->
    <div class="modal fade" id="unreserveModal" tabindex="-1" aria-labelledby="unreserveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 text-center">
                <div class="modal-body">
                    <h2 class="fw-bold text-danger">Cancel Reservation</h2>
                    <p>Are you sure you want to unreserve this event?</p>
                    <form action="../includes/unreserve_event.inc.php" method="POST">
                        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">
                        <div class="d-flex justify-content-center gap-4 mt-4">
                            <!-- Submit Button for Unreserve -->
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                Yes
                            </button>
                            <!-- Close Modal Button -->
                            <button type="button" class="btn btn-danger btn-lg px-4" data-bs-dismiss="modal">
                                No
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>