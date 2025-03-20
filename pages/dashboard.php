<?php
require_once '../includes/data_getter.inc.php';

$departmentDataJSON = json_encode($_SESSION['users_by_department']);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Increase height of tickets and active events cards */
        .summary-card {
            min-height: 140px;
        }

        /* Ensure Sales Report and Right Column have equal height */
        .equal-height {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Increase sales chart height */
        #salesChart {
            height: 350px !important;
        }

        /* Align legends to the right of the pie chart */
        #usersChartLegend {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-left: 15px;
        }
    </style>
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
                <li class="nav-item"><a href="dashboard.php" class="nav-link active">Dashboard</a></li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#eventManagementCollapse" role="button">
                        Event Management
                    </a>
                    <div class="collapse" id="eventManagementCollapse">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a href="event-management.php" class="nav-link">Events</a></li>
                            <li class="nav-item"><a href="ReservedUsers.html" class="nav-link">Reserved Users</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="user-management.php" class="nav-link">User Management</a></li>
                <li class="nav-item"><a href="../includes/logout.inc.php" class="nav-link">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <main class="content container">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="mt-3">Hello Admin</h2>
                <div>
                    <label for="timeFilter" class="form-label">Filter by:</label>
                    <select id="timeFilter" class="form-select">
                        <option value="7" selected>Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-7 d-flex flex-column">
                    <!-- Tickets Sold & Active Events Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-3 text-center summary-card">
                                <h5>Tickets Sold</h5>
                                <h2 class="text-success">250</h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card p-3 text-center summary-card">
                                <h5>Active Events</h5>
                                <h2 class="text-primary"><?php echo $_SESSION['total_active_events']; ?></h2>
                            </div>
                        </div>

                    </div>
                    <!-- Sales Report and Analytics -->
                    <div class="card p-3 mt-3 flex-grow-1 equal-height">
                        <h5>Sales Report and Analytics</h5>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Right Column (Users Per Department & Top Events) -->
                <div class="col-md-5 d-flex flex-column">
                    <div class="card p-3 h-100">
                        <h5>Users per Department</h5>
                        <div class="d-flex align-items-center">
                            <canvas id="usersChart"></canvas>
                            <div id="usersChartLegend"></div>
                        </div>
                    </div>
                    <div class="card p-3 mt-3 h-100">
                        <h5>Top Events</h5>
                        <ol>
                            <?php
                            if (!empty($_SESSION['most_rated_events'])) {
                                foreach ($_SESSION['most_rated_events'] as $event) {
                                    echo "<li><strong>{$event['title']}</strong> - Rating: <strong>{$event['ratings']}/5</strong></li>";
                                }
                            } else {
                                echo "<li>No top-rated events available</li>";
                            }
                            ?>
                        </ol>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Sales Chart - Taller Height
            new Chart(document.getElementById("salesChart"), {
                type: "line",
                data: {
                    labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
                    datasets: [{
                        label: "Sales ($)",
                        data: [3000, 5000, 4000, 12000],
                        borderColor: "#dea06b",
                        backgroundColor: "rgba(222, 160, 107, 0.2)",
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: "#dea06b"
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMin: 0,
                            suggestedMax: 15000
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get department data from PHP session
            let departmentData = <?php echo $departmentDataJSON; ?>;

            // Check if data is available
            if (!departmentData || departmentData.length === 0) {
                console.error("Error: No department data available");
                return;
            }

            // Extract labels (Department Names) and data (User Count)
            let labels = departmentData.map(item => item.course);
            let data = departmentData.map(item => item.user_count);

            // Define colors for each department
            let backgroundColors = ["#b91d47", "#00aba9", "#2b5797", "#e8c3b9", "#f4a261"];

            // Ensure canvas exists
            const usersChartCanvas = document.getElementById("usersChart");
            if (!usersChartCanvas) {
                console.error("Error: Pie chart canvas not found!");
                return;
            }

            // Create Pie Chart
            new Chart(usersChartCanvas, {
                type: "pie",
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors.slice(0, labels.length)
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "right"
                        }
                    }
                }
            });
        });
    </script>


</body>

</html>