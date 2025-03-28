﻿<?php
require_once '../includes/data_getter.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/accountedit.css">
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

        <div class="container mt-5">
            <div class="card profile-card p-4 shadow-sm mx-auto">
                <form id="accountForm" action="../includes/edit_account.inc.php" method="POST"
                    enctype="multipart/form-data">
                    <div class="row g-3 align-items-center">
                        <!-- Profile Picture -->
                        <div class="col-md-3 text-center">
                            <div class="profile-img-container">
                                <img id="profilePreview"
                                    src="<?= !empty($_SESSION['user_img']) ? '../uploads/' . htmlspecialchars($_SESSION['user_img']) : 'assets/images/profile.jpg' ?>"
                                    class="profile-img" alt="User Profile">
                                <input type="file" id="profileInput" name="profile_image" accept="image/*"
                                    style="display: none;">
                                <button type="button" class="edit-btn"
                                    onclick="document.getElementById('profileInput').click();">✎</button>
                            </div>
                        </div>

                        <!-- User Details -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-label">Full Name</h3>
                                    <input type="text" class="form-control" name="user_name"
                                        value="<?= htmlspecialchars($_SESSION['user_name']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-label">Student ID</h3>
                                    <input type="text" class="form-control" name="user_student_number"
                                        value="<?= htmlspecialchars($_SESSION['user_student_number']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-label">Email Address</h3>
                                    <input type="email" class="form-control" name="user_email"
                                        value="<?= htmlspecialchars($_SESSION['user_email']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-label">Phone Number</h3>
                                    <input type="text" class="form-control" name="user_phone_number"
                                        value="<?= htmlspecialchars($_SESSION['user_phone_number']) ?>">
                                </div>

                                <!-- College -->
                                <div class="col-md-12">
                                    <h3 class="form-label">Department</h3>
                                    <input type="text" class="form-control" name="user_department"
                                        value="<?= htmlspecialchars($_SESSION['user_department']) ?>">
                                </div>

                                <!-- Course & Section -->
                                <div class="col-md-6">
                                    <h3 class="form-label">Course</h3>
                                    <input type="text" class="form-control" name="user_course"
                                        value="<?= htmlspecialchars($_SESSION['user_course']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-label">Section</h3>
                                    <input type="text" class="form-control" name="user_year_section"
                                        value="<?= htmlspecialchars($_SESSION['user_year_section']) ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-3">
                            <a href="account_page.php" class="btn btn-link text-danger">Back</a>
                            <button type="submit" class="btn btn-warning text-white">Confirm</button>
                        </div>
                    </div>
                </form>
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
        document.getElementById("profileInput").addEventListener("change", function (event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById("profilePreview").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>

</html>