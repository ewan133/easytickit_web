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
                    <input class="form-control" type="search" placeholder="Search an event..." aria-label="Search">
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

    <div class="container">
        <div class="container mt-5 mb-4"> <!-- Reduced top margin -->
            <div class="row align-items-start g-4 justify-content-center" style="max-width: 1200px; margin: auto;">

                <!-- Left: Event Image -->
                <div class="col-lg-4 col-md-6 text-center"> <!-- Adjusts for different screen sizes -->
                    <img src="<?= htmlspecialchars('../uploads/events/' . $selected_event['image_1']) ?>"
                        alt="<?= htmlspecialchars($selected_event['title']) ?>" class="img-fluid rounded shadow-lg"
                        style="width: 100%; height: auto; max-height: 420px; object-fit: cover;">
                </div>

                <!-- Right: Event Details -->
                <div class="col-lg-8 col-md-12 text-dark d-flex flex-column justify-content-center">
                    <h1 class="fw-bold text-lg-start text-center mb-3" style="font-size: 42px; color: #017479;">
                        <?= htmlspecialchars($selected_event['title']) ?>
                    </h1>

                    <p class="fs-5 text-dark text-lg-start text-center mb-4" style="width: 100%; line-height: 1.6;">
                        <?= htmlspecialchars($selected_event['description']) ?>
                    </p>

                    <!-- Event Info Section -->
                    <div class="row text-md-start text-center">
                        <div class="col-md-6 mb-2">
                            <p class="fs-5 text-dark">
                                <strong>Date:</strong>
                                <?= date("F d, Y", strtotime($selected_event['start_datetime'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end text-center">
                            <p class="fs-5 text-dark">
                                <strong>Price:</strong> ₱<?= number_format($selected_event['price'], 2) ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!-- OTHER PHOTOS SECTION -->
        <div class="other-photos-section text-center">
            <?php
            $hasValidImages = false; // Flag to check if any valid image exists
            
            // Check if there are any valid images
            for ($i = 2; $i <= 4; $i++) {
                $imageFilename = $selected_event["image_$i"];

                if (
                    !empty($imageFilename) &&
                    strpos($imageFilename, "default.jpg") === false &&
                    strpos($imageFilename, "default_image.jpg") === false
                ) {
                    $hasValidImages = true;
                    break; // Exit loop early if a valid image is found
                }
            }

            // Only show section if there are valid images
            if ($hasValidImages): ?>
                <h2 class="other-photos-title">OTHER PHOTOS</h2>
                <div class="other-photos-container">
                    <?php for ($i = 2; $i <= 4; $i++): ?>
                        <?php
                        $imageFilename = $selected_event["image_$i"];
                        $imagePath = htmlspecialchars('../uploads/events/' . $imageFilename);

                        if (
                            !empty($imageFilename) &&
                            strpos($imageFilename, "default.jpg") === false &&
                            strpos($imageFilename, "default_image.jpg") === false
                        ):
                            ?>
                            <img src="<?= $imagePath ?>" class="other-photo">
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>





            <!-- Reservation Button (Moved Below Images) -->
            <div class="mt-3     d-flex flex-column justify-content-center align-items-center">

                <?php if ($isReserved): ?>

                    <!-- Reserved Text -->
                    <div class="d-flex justify-content-center align-items-center shadow-lg"
                        style="width: 250px; height: 50px; border: 1px solid #000; border-radius: 10px;">
                        <span class="fw-bold text-dark" style="font-size: 18px;">RESERVED</span>
                    </div>

                    <!-- Cancel Reservation Button -->
                    <button class="btn btn-danger mt-3 shadow-lg" data-bs-toggle="modal" data-bs-target="#unreserveModal"
                        style="width: 267px; height: 44px; border-radius: 10px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 18px;">
                        Cancel Reservation
                    </button>

                <?php else: ?>
                    <!-- Reserve Now Button -->
                    <button class="btn btn-warning mt-3 shadow-lg" data-bs-toggle="modal" data-bs-target="#reserveModal"
                        style="width: 267px; height: 44px; background: #EF7125; border-radius: 10px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 18px; color: #FFFFFF;">
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
        <div class="modal-dialog modal-dialog-centered" style="max-width: 750px;">
            <div class="modal-content p-4 text-center"
                style="border-radius: 20px; box-shadow: 0px 4px 25px rgba(0, 0, 0, 0.25); background: #FFFFFF;">

                <!-- Modal Header -->
                <div class="modal-header border-0 position-relative">
                    <h3 class="fw-bold text-primary mx-auto" id="reserveModalLabel" style="color: #017479;">
                        Confirm Reservation
                    </h3>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <h5 class="fw-bold text-dark mb-3" style="color: #3A3A3A;">PLV Valentines 2025</h5>

                    <div class="d-flex justify-content-between text-muted mb-3" style="font-size: 16px;">
                        <span>DATE: <strong>February 14, 2025</strong></span>
                        <span>PRICE: <strong>₱80.00</strong></span>
                    </div>

                    <!-- User Details (Dynamically Loaded from Session) -->
                    <div class="text-start">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="fw-bold text-secondary mb-1">Full Name</p>
                                <div class="p-2 border rounded bg-light">
                                    <?= isset($_SESSION["user_name"]) ? htmlspecialchars($_SESSION["user_name"]) : 'N/A' ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="fw-bold text-secondary mb-1">Student Number</p>
                                <div class="p-2 border rounded bg-light">
                                    <?= isset($_SESSION["user_student_number"]) ? htmlspecialchars($_SESSION["user_student_number"]) : 'N/A' ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="fw-bold text-secondary mb-1">Phone Number</p>
                            <div class="p-2 border rounded bg-light">
                                <?= isset($_SESSION["user_phone_number"]) ? htmlspecialchars($_SESSION["user_phone_number"]) : 'N/A' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="fw-bold text-secondary mb-1">Department</p>
                            <div class="p-2 border rounded bg-light">
                                <?= isset($_SESSION["user_department"]) ? htmlspecialchars($_SESSION["user_department"]) : 'N/A' ?>
                            </div>
                        </div>

                        <!-- Mode of Payment (Dropdown) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Method of Payment</label>
                            <div class="dropdown">
                                <button
                                    class="btn btn-outline-secondary dropdown-toggle w-100 text-start p-2 d-flex justify-content-between align-items-center"
                                    type="button" id="paymentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="selectedPayment">Select Payment Method</span>
                                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="paymentDropdown">
                                    <li><a class="dropdown-item payment-option" href="#" data-value="Cash">Cash</a></li>
                                    <li><a class="dropdown-item payment-option" href="#"
                                            data-value="E-payment">E-payment</a></li>
                                </ul>
                            </div>
                            <small id="paymentError" class="text-danger d-none">Please select a payment method.</small>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer (Buttons) -->
                <div class="modal-footer border-0 d-flex justify-content-end gap-3">
                    <button type="button" class="btn fw-bold text-danger" data-bs-dismiss="modal"
                        style="font-size: 16px; text-decoration: underline;">
                        Back
                    </button>
                    <a href="#" id="confirmButton" class="btn fw-bold px-4"
                        style="background: #EF7125; color: #FFFFFF; font-size: 16px; border-radius: 8px;">
                        Confirm
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Validating Dropdown Selection -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".payment-option").forEach(item => {
                item.addEventListener("click", function (event) {
                    event.preventDefault();
                    document.getElementById("selectedPayment").innerText = this.getAttribute("data-value");
                    document.getElementById("paymentError").classList.add("d-none"); // Hide error message
                });
            });

            document.getElementById("confirmButton").addEventListener("click", function (event) {
                let selectedPayment = document.getElementById("selectedPayment").innerText;
                if (selectedPayment === "Select Payment Method") {
                    event.preventDefault(); // Prevent navigation if no selection
                    document.getElementById("paymentError").classList.remove("d-none"); // Show error message
                } else {
                    // Proceed to reservation with selected payment method
                    window.location.href = `../includes/reserve_event.inc.php?event_id=<?= $event_id ?>&payment_method=${selectedPayment}`;
                }
            });
        });
    </script>

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