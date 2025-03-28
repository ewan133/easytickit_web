<?php
require_once '../includes/data_getter.inc.php';

// Fetch reserved users for active events
$reserved_users = getAllReservedUsersForActiveEvents($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserved Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="../assets/logo.png" alt="Logo" class="logo">
            <span class="logo-text">easytickIT</span>
        </a>
    </nav>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#eventManagementCollapse" role="button">Event
                        Management</a>
                    <div class="collapse show" id="eventManagementCollapse">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a href="event-management.php" class="nav-link">Events</a></li>
                            <li class="nav-item"><a href="reserved_users.php" class="nav-link active">Reserved Users</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="user-management.php" class="nav-link">User Management</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4">
            <h1>Reserved Users</h1>

            <!-- Search and Sort Controls -->
            <div class="d-flex justify-content-between mb-3">
                <input type="text" id="searchUser" class="form-control w-50" placeholder="Search User">
                <button class="btn btn-outline-secondary" id="sortButton">Sort</button>
            </div>

            <!-- Reserved Users Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Student No.</th>
                        <th>Phone Number</th>
                        <th>Event</th>
                        <th>Event Date</th>
                        <th>Status</th>
                        <th>Reserved At</th>
                    </tr>
                </thead>
                <tbody id="reservedUsersTable">
                    <?php
                    if (!empty($reserved_users)) {
                        foreach ($reserved_users as $user) {
                            echo "<tr>
                                <td>{$user['name']}</td>
                                <td>{$user['email']}</td>
                                <td>{$user['student_number']}</td>
                                <td>{$user['phone_number']}</td>
                                <td>{$user['title']}</td>
                                <td>" . date("F j, Y h:i A", strtotime($user['start_datetime'])) . "</td>
                                <td>{$user['reservation_status']}</td>
                                <td>" . date("F j, Y h:i A", strtotime($user['reserved_at'])) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No reserved users for active events</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/reserved-users.js"></script>
</body>
</html>
