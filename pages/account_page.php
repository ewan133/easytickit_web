<?php
require_once '../includes/data_getter.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/accountpage.css">
</head>

<body>

    <div class="wrapper d-flex flex-column min-vh-100">
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
                        <form action="events_page.php" method="get">
                            <input class="form-control" type="search" name="search" placeholder="Search an event..."
                                aria-label="Search">
                        </form>
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
                            <a href="account_page.php"
                                class="d-flex align-items-center text-decoration-none text-white">
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

        <!-- Account Section -->
        <div class="container mt-4 flex-grow-1">
            <h2 class="text-center text-uppercase fw-bold">Account</h2>
            <div class="card p-4 shadow-sm mx-auto account-container">
                <div class="row g-3 align-items-center">
                    <!-- Profile Picture -->
                    <div class="col-md-3 text-center">
                        <img src="../uploads/<?= !empty($_SESSION['user_img']) ? $_SESSION['user_img'] : 'assets/images/profile.jpg' ?>"
                            class="profile-img" alt="User Profile">
                    </div>

                    <!-- User Details -->
                    <div class="col-md-9">
                        <h4 class="fw-bold"><?= htmlspecialchars($_SESSION["user_name"]) ?></h4>
                        <p class="mb-1"><?= htmlspecialchars($_SESSION["user_email"]) ?></p>
                        <p class="mb-1"><?= htmlspecialchars($_SESSION["user_phone_number"]) ?></p>
                        <p class="mb-1"><?= htmlspecialchars($_SESSION["user_department"]) ?></p>
                        <p class="mb-1">
                            <?= htmlspecialchars($_SESSION["user_course"]) . ' ' . htmlspecialchars($_SESSION["user_year_section"]) ?>
                        </p>
                        <p class="mb-1">
                            Password: ************
                            <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">✎</a>
                        </p>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="account_edit.php" class="btn btn-outline-dark">Edit Details</a>

                            <!-- Button to Trigger Logout Modal -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#logoutModal">
                                Log Out
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Logout Confirmation Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4 text-center">
                    <div class="modal-body">
                        <h2 class="fw-bold text-danger">Are you sure you want to log out?</h2>
                        <div class="d-flex justify-content-center gap-4 mt-5">
                            <!-- Yes Button (Logs Out) -->
                            <a href="../includes/logout.inc.php" class="btn btn-success btn-lg px-4">
                                Yes
                            </a>
                            <!-- No Button (Closes Modal) -->
                            <button type="button" class="btn btn-danger btn-lg px-4" data-bs-dismiss="modal">
                                No
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container event-container">
            <!-- Event Selection Tags -->
            <div class="d-flex justify-content-center gap-3">
                <span id="reservedTag" class="event-tag active-tag">Reserved Events</span>
                <span id="attendedTag" class="event-tag">Attended Events</span>
            </div>

            <!-- Reserved Events Section (Default Visible) -->
            <div id="reservedEvents" class="event-list">
                <?php if (isset($_SESSION['reserved_events']) && count($_SESSION['reserved_events']) > 0): ?>
                    <?php foreach ($_SESSION['reserved_events'] as $event): ?>
                        <div class="event-item" data-bs-toggle="modal" data-bs-target="#eventQRModal<?= $event['event_id'] ?>"
                            style="cursor: pointer;">
                            <img src="<?= htmlspecialchars('../uploads/events/' . $event['image_1']) ?>" class="event-img"
                                alt="<?= htmlspecialchars($event['title']) ?>">
                            <p><?= htmlspecialchars($event['title']) ?></p>
                        </div>

                        <!-- Event QR Code Modal -->
                        <div class="modal fade" id="eventQRModal<?= $event['event_id'] ?>" tabindex="-1"
                            aria-labelledby="eventQRModalLabel<?= $event['event_id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered"> <!-- Adjusted modal size to small -->
                                <div class="modal-content"
                                    style="background: #FFFFFF; box-shadow: 0px 4px 25.3px 23px rgba(0, 0, 0, 0.25); border-radius: 42px;">


                                    <!-- Modal Body -->
                                    <div class="modal-body text-center">
                                        <h3 class="fw-bold text-primary mx-auto mb-0"
                                            style="color: #017479; font-size: 2rem; padding-top: 10px; padding-bottom: 10px;">
                                            Event QR
                                        </h3>

                                        <img src="<?= htmlspecialchars($event['qr_code']) ?>" alt="QR Code"
                                            style="width:200px; height: 200px; border-radius: 10px; margin-bottom: 0px;">
                                        <!-- Reduced image size -->
                                        <h5 class="fw-bold" style="color: #3A3A3A; font-size: 1.50rem;">
                                            <!-- Reduced font size -->
                                            <?= htmlspecialchars($event['title']) ?>
                                        </h5>
                                        <p style="font-size: 1rem; color: #3A3A3A;"> <!-- Reduced font size -->
                                            <?= date("F d, Y", strtotime($event['start_datetime'])) ?>
                                        </p>
                                        <div class="mt-3 mb-2"> <!-- Adjusted margin for button -->
                                            <button class="btn btn-warning px-4"
                                                style="background: #EF7125; color: #FFFFFF; border-radius: 10px; font-size: 1rem;"
                                                data-bs-dismiss="modal">
                                                Confirm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">No reserved events yet.</p>
                <?php endif; ?>
            </div>




            <!-- Attended Events Section (Initially Hidden) -->
            <div id="attendedEvents" class="event-list d-none">
                <div>
                    <a href="#"><img src="../assets/event3.png" class="event-img"></a>
                    <p>Battle of the Bands 2024</p>
                </div>
                <div>
                    <a href="#"><img src="../assets/event2.png" class="event-img"></a>
                    <p>Music Fest 2023</p>
                </div>
                <div>
                    <a href="#"><img src="../assets/event1.png" class="event-img"></a>
                    <p>Another Attended Event</p>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="changePasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="passwordForm">
                            <div class="mb-3">
                                <label for="oldPassword" class="form-label">Old Password</label>
                                <input type="password" class="form-control" id="oldPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" required>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-warning" id="confirmChangeBtn">Confirm</button>
                                <button type="button" class="btn btn-link text-dark"
                                    data-bs-dismiss="modal">Back</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"> <!-- Added modal-dialog-centered -->
                <div class="modal-content p-5 text-center">
                    <div class="modal-body">
                        <h4 class="fw-bold text-success">Password Change Successful!</h4>
                        <button type="button" class="btn btn-warning mt-5" data-bs-dismiss="modal">Return to
                            Account</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Footer -->
        <div class="text-center py-3 mt-4 bg-dark text-light">
            <small>Copyright © easyTickIT 2025. All rights reserved. Designed by Back(log) Burners.</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.getElementById("confirmChangeBtn").addEventListener("click", function () {
            let oldPassword = document.getElementById("oldPassword").value;
            let newPassword = document.getElementById("newPassword").value;
            let confirmPassword = document.getElementById("confirmPassword").value;

            if (newPassword !== confirmPassword) {
                alert("New passwords do not match!");
                return;
            }

            if (newPassword.length < 6) {
                alert("Password must be at least 6 characters long.");
                return;
            }

            // Send AJAX request to change password
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../includes/change_password.inc.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);

                    if (response.status === "success") {
                        // Hide the change password modal
                        let changePasswordModal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                        if (changePasswordModal) {
                            changePasswordModal.hide();
                        }

                        // Show the success modal
                        let successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();

                        // Clear input fields
                        document.getElementById("oldPassword").value = "";
                        document.getElementById("newPassword").value = "";
                        document.getElementById("confirmPassword").value = "";

                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send(`oldPassword=${encodeURIComponent(oldPassword)}&newPassword=${encodeURIComponent(newPassword)}`);
        });

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Select the tab elements
            const reservedTag = document.getElementById("reservedTag");
            const attendedTag = document.getElementById("attendedTag");

            // Select the event lists
            const reservedEvents = document.getElementById("reservedEvents");
            const attendedEvents = document.getElementById("attendedEvents");

            // Function to switch between tabs
            function switchTab(activeTab) {
                if (activeTab === "reserved") {
                    reservedEvents.classList.remove("d-none");
                    attendedEvents.classList.add("d-none");

                    reservedTag.classList.add("active-tag");
                    attendedTag.classList.remove("active-tag");
                } else {
                    attendedEvents.classList.remove("d-none");
                    reservedEvents.classList.add("d-none");

                    attendedTag.classList.add("active-tag");
                    reservedTag.classList.remove("active-tag");
                }
            }

            // Add event listeners to tabs
            reservedTag.addEventListener("click", function () {
                switchTab("reserved");
            });

            attendedTag.addEventListener("click", function () {
                switchTab("attended");
            });
        });

    </script>




</body>

</html>