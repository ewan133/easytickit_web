<?php
require_once '../includes/data_getter.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
                    <a class="nav-link" data-bs-toggle="collapse" href="#eventManagementCollapse" role="button">
                        Event Management
                    </a>
                    <div class="collapse" id="eventManagementCollapse">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a href="event-management.php" class="nav-link">Events</a></li>
                            <li class="nav-item"><a href="ReservedUsers.html" class="nav-link active">Reserved Users</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="user-management.php" class="nav-link">User Management</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4">
            <h1>USER MANAGEMENT</h1>

            <!-- Search and Sort Controls -->
            <div class="d-flex justify-content-between mb-3">
                <input type="text" id="searchUser" class="form-control w-50" placeholder="Search Users" onkeyup="searchUsers()">
                <button class="btn btn-outline-secondary" id="sortButton" onclick="sortUsers()">Sort</button>
            </div>

            <!-- Success & Error Alerts (Initially Hidden) -->
            <div class="alert alert-success alert-dismissible fade d-none" id="successAlert" role="alert">
                User deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <div class="alert alert-danger alert-dismissible fade d-none" id="errorAlert" role="alert">
                Error deleting user.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- User Management Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Full Name</th>
                        <th>EMAIL ADDRESS</th>
                        <th>Student No.</th>
                        <th>Course</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php
                    require_once '../includes/data_getter.inc.php';

                    if (isset($_SESSION['users']) && !empty($_SESSION['users'])) {
                        foreach ($_SESSION['users'] as $user) {
                            echo "<tr id='userRow-{$user['user_id']}'>";
                            echo "<td><input type='checkbox' class='user-checkbox' value='{$user['user_id']}'></td>";
                            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($user['student_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($user['course']) . "</td>";
                            echo "<td>" . date("F j, Y", strtotime($user['created_at'])) . "</td>";
                            echo "<td>
                        <button class='btn btn-danger btn-sm' onclick='confirmDelete({$user['user_id']})'>
                            <i class='bi bi-trash'></i> Delete
                        </button>
                      </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                fetch(`../includes/delete_user.inc.php?user_id=${userId}`, {
                        method: "GET"
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result.includes("successfully")) {
                            showAlert("successAlert");
                            document.getElementById(`userRow-${userId}`).remove(); // Remove row without reloading
                        } else {
                            showAlert("errorAlert");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        showAlert("errorAlert");
                    });
            }
        }

        // Show Alert & Auto-Hide After 3 Seconds
        function showAlert(alertId) {
            let alertBox = document.getElementById(alertId);
            alertBox.classList.remove("d-none", "fade");
            alertBox.classList.add("show");

            setTimeout(() => {
                alertBox.classList.add("fade");
                alertBox.classList.add("d-none");
            }, 3000);
        }

        // Select All Checkbox Functionality
        document.getElementById("selectAll").addEventListener("change", function() {
            let checkboxes = document.querySelectorAll(".user-checkbox");
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>

    <script>
        function searchUsers() {
            let input = document.getElementById("searchUser").value.toLowerCase();
            let rows = document.querySelectorAll("#userTable tr");

            rows.forEach(row => {
                let name = row.cells[1]?.textContent.toLowerCase() || "";
                let email = row.cells[2]?.textContent.toLowerCase() || "";
                let studentNo = row.cells[3]?.textContent.toLowerCase() || "";
                let department = row.cells[4]?.textContent.toLowerCase() || "";

                if (name.includes(input) || email.includes(input) || studentNo.includes(input) || department.includes(input)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        let ascending = true; // Track sort order

        function sortUsers() {
            let table = document.getElementById("userTable");
            let rows = Array.from(table.rows);

            rows.sort((a, b) => {
                let nameA = a.cells[1]?.textContent.toLowerCase() || "";
                let nameB = b.cells[1]?.textContent.toLowerCase() || "";

                return ascending ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
            });

            ascending = !ascending; // Toggle sort order

            rows.forEach(row => table.appendChild(row)); // Reorder rows in table
        }
    </script>


</body>

</html>