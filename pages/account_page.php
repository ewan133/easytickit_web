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
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container d-flex align-items-center">
                <a class="navbar-brand" href="#"><img src="../assets/logo.png" alt="Logo"></a>
                <input class="form-control search-bar" type="search" placeholder="Search an event..."
                    aria-label="Search">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link text-white" href="../index.php">HOME</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="events_page.php">EVENTS</a></li>
                        <li class="nav-item">
                            <a href="#"><img src="<?= htmlspecialchars('../uploads/' . $_SESSION['user_img']) ?>"
                                    class="nav-profile" alt="Profile"></a>
                        </li>
                    </ul>
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
                            <button class="btn btn-dark">My Events</button>
                            <a href="account_edit.php" class="btn btn-outline-dark">Edit Details</a>
                            <form action="../includes/logout.inc.php" method="post">
                                <button type="submit" class="btn btn-danger">Log Out</button>
                            </form>

                        </div>
                    </div>

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
            <div class="modal-dialog">
                <div class="modal-content p-3 text-center">
                    <div class="modal-body">
                        <h5 class="fw-bold text-success">Password Change Successful!</h5>
                        <button type="button" class="btn btn-warning mt-3" data-bs-dismiss="modal">Return to
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





</body>

</html>