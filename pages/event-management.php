<?php
require_once '../includes/data_getter.inc.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
                            <li class="nav-item"><a href="event-management.php" class="nav-link active">Events</a></li>
                            <li class="nav-item"><a href="ReservedUsers.php" class="nav-link">Reserved Users</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="user-management.php" class="nav-link">User Management</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4">
            <h1>EVENTS</h1>

            <!-- ✅ Success Alert (Initially Hidden) -->
            <div id="successAlert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                Event added successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- ✅ Success Alert (Initially Hidden) -->
            <div id="successEditAlert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                Event updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- ✅ Delete Success Alert (Initially Hidden) -->
            <div id="deleteAlert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                Event deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>


            <div class="d-flex justify-content-end">
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#eventModal">+ Add
                    Event</button>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>No. of Tickets</th>
                        <th>Date</th>
                        <th>Actions</th> <!-- Added Actions Column -->
                    </tr>
                </thead>

                <tbody id="eventTableBody">
                    <?php
                    if (isset($_SESSION['events']) && !empty($_SESSION['events'])) {
                        foreach ($_SESSION['events'] as $event) {
                            $formattedDate = date("Y-m-d\TH:i", strtotime($event['start_datetime'])); // Format for datetime-local input
                    
                            echo "<tr>
                    <td>{$event['title']}</td>
                    <td>{$event['department']}</td>
                    <td>{$event['status']}</td>
                    <td>₱ {$event['price']}</td>
                    <td>{$event['capacity']}</td>
                    <td>" . date("F j, Y h:i A", strtotime($event['start_datetime'])) . "</td>
                    <td>
                        <!-- Edit Button -->
                        <button class='btn btn-primary btn-sm' 
                            onclick='openEditModal(" . json_encode($event) . ")'>
                            <i class='bi bi-pencil'></i> Edit
                        </button>

                        <!-- Delete Button -->
                        <button class='btn btn-danger btn-sm' 
                            onclick='confirmDelete({$event['event_id']})'>
                            <i class='bi bi-trash'></i> Delete
                        </button>
                    </td>
                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No events available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>




        </div>

        <!-- Modal for Add Event -->
        <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="eventForm" enctype="multipart/form-data" action="../includes/add_event.inc.php"
                            method="POST">
                            <div class="row">
                                <!-- Left Side: Image Uploads -->
                                <div class="col-md-4">
                                    <div class="mb-3 text-center">
                                        <label class="form-label">Add Header Photo (1 Image)</label>
                                        <input type="file" id="headerPhoto" name="headerPhoto" class="form-control"
                                            accept="image/*">
                                        <img id="headerImg" src="" alt="Header Image"
                                            style="width: 100%; display: none; margin-top: 10px;">
                                    </div>
                                    <div class="mb-3 text-center">
                                        <label class="form-label">Add Other Photos (Exactly 3 Images)</label>
                                        <input type="file" id="otherPhotos" name="otherPhotos[]" class="form-control"
                                            accept="image/*" multiple>
                                        <div id="otherImagePreview" class="d-flex flex-wrap mt-2"></div>
                                        <!-- Image Preview Here -->
                                    </div>
                                </div>

                                <!-- Right Side: Event Details -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Event Title</label>
                                        <input type="text" id="eventName" name="title" class="form-control" required>
                                    </div>
                                    <!-- Department Dropdown -->
                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <select id="eventDepartment" name="department" class="form-control" required>
                                            <option value="" selected disabled>Select a Department</option>
                                            <option value="BSIT">BSIT</option>
                                            <option value="BSEE">BSEE</option>
                                            <option value="BSCE">BSCE</option>
                                            <option value="BSCS">BSCS</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Date & Time</label>
                                        <input type="datetime-local" id="eventDate" name="date" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea id="eventDescription" name="description" class="form-control" rows="3"
                                            required></textarea>
                                    </div>



                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">No. of Tickets</label>
                                            <input type="number" id="eventTickets" name="capacity" class="form-control"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Price</label>
                                            <input type="number" id="eventPrice" name="price" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="editingEventId"> <!-- Hidden field for editing event -->

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                                <button type="submit" class="btn btn-success" id="addEventBtn">Add Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Editing Event -->
        <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editEventForm" enctype="multipart/form-data" action="../includes/edit_event.inc.php"
                            method="POST">
                            <div class="row">
                                <!-- Left Side: Image Uploads -->
                                <div class="col-md-4">
                                    <!-- Header Image Section -->
                                    <div class="mb-3 text-center">
                                        <label class="form-label">Update Header Photo</label>
                                        <div class="position-relative d-inline-block">
                                            <!-- Header Image Preview -->
                                            <img id="editHeaderImg" src="uploads/events/default.jpg" alt="Header Image"
                                                style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px;">
                                            <!-- Hidden File Input -->
                                            <input type="file" id="editHeaderPhoto" name="image_1"
                                                class="form-control d-none" accept="image/*"
                                                onchange="previewImage(event, 'editHeaderImg')">
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-primary mt-2"
                                                onclick="document.getElementById('editHeaderPhoto').click();">
                                                Edit Photo
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Other Images Section (Images 2, 3, 4) -->
                                    <div class="mb-3 text-center">
                                        <label class="form-label">Update Other Photos</label>
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Image 2 -->
                                            <div class="position-relative text-center">
                                                <img id="editImg2" src="" alt="Image 2"
                                                    style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                                <input type="file" id="editPhoto2" name="image_2" class="d-none"
                                                    accept="image/*" onchange="previewImage(event, 'editImg2')">
                                                <!-- Always Visible Edit Icon -->
                                                <button type="button"
                                                    class="btn btn-sm p-1 position-absolute d-flex align-items-center justify-content-center"
                                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0, 0, 0, 0.7); border-radius: 50%; width: 30px; height: 30px; z-index: 2;"
                                                    onclick="document.getElementById('editPhoto2').click();">
                                                    <i class="bi bi-pencil text-white" style="font-size: 16px;"></i>
                                                </button>
                                            </div>

                                            <!-- Image 3 -->
                                            <div class="position-relative text-center">
                                                <img id="editImg3" src="" alt="Image 3"
                                                    style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                                <input type="file" id="editPhoto3" name="image_3" class="d-none"
                                                    accept="image/*" onchange="previewImage(event, 'editImg3')">
                                                <!-- Always Visible Edit Icon -->
                                                <button type="button"
                                                    class="btn btn-sm p-1 position-absolute d-flex align-items-center justify-content-center"
                                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0, 0, 0, 0.7); border-radius: 50%; width: 30px; height: 30px; z-index: 2;"
                                                    onclick="document.getElementById('editPhoto3').click();">
                                                    <i class="bi bi-pencil text-white" style="font-size: 16px;"></i>
                                                </button>
                                            </div>

                                            <!-- Image 4 -->
                                            <div class="position-relative text-center">
                                                <img id="editImg4" src="" alt="Image 4"
                                                    style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                                <input type="file" id="editPhoto4" name="image_4" class="d-none"
                                                    accept="image/*" onchange="previewImage(event, 'editImg4')">
                                                <!-- Always Visible Edit Icon -->
                                                <button type="button"
                                                    class="btn btn-sm p-1 position-absolute d-flex align-items-center justify-content-center"
                                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0, 0, 0, 0.7); border-radius: 50%; width: 30px; height: 30px; z-index: 2;"
                                                    onclick="document.getElementById('editPhoto4').click();">
                                                    <i class="bi bi-pencil text-white" style="font-size: 16px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>



                                </div>



                                <!-- Right Side: Event Details -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Event Title</label>
                                        <input type="text" id="editEventName" name="title" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <select id="editEventDepartment" name="department" class="form-control"
                                            required>
                                            <option value="" disabled>Select a Department</option>
                                            <option value="BSIT">BSIT</option>
                                            <option value="BSEE">BSEE</option>
                                            <option value="BSCE">BSCE</option>
                                            <option value="BSCS">BSCS</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Date & Time</label>
                                        <input type="datetime-local" id="editEventDate" name="date" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea id="editEventDescription" name="description" class="form-control"
                                            rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">No. of Tickets</label>
                                            <input type="number" id="editEventTickets" name="capacity"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Price</label>
                                            <input type="number" id="editEventPrice" name="price" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select id="editEventStatus" name="status" class="form-control" required>
                                            <option value="active">Active</option>
                                            <option value="ended">Ended</option>
                                            <option value="cancelled">cancelled</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="editEventId" name="event_id"> <!-- Hidden field for event ID -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="updateEventBtn">Update Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </div>

    <!-- Add event constraint-->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let now = new Date();
            let formattedDate = now.toISOString().slice(0, 16);
            document.getElementById("eventDate").value = formattedDate;

            // Validate Header Photo (Required & Preview)
            document.getElementById("headerPhoto").addEventListener("change", function () {
                validateImage(this, 1, "headerImg");
            });

            // Validate Other Photos (Up to 3 Images & Preview)
            document.getElementById("otherPhotos").addEventListener("change", function () {
                if (this.files.length > 3) {
                    alert("You can only select up to 3 images.");
                    this.value = ""; // Clear selection
                } else {
                    previewMultipleImages(this, "otherImagePreview");
                }
            });

            // Ensure Header Image is Required Before Submitting
            document.getElementById("eventForm").addEventListener("submit", function (event) {
                if (!document.getElementById("headerPhoto").files.length) {
                    alert("Please upload a header image before submitting.");
                    event.preventDefault();
                }
            });

            // Clear Form Fields After Closing the Modal
            let eventModal = document.getElementById("eventModal");
            eventModal.addEventListener("hidden.bs.modal", function () {
                document.getElementById("eventForm").reset();
                document.getElementById("eventDate").value = formattedDate;
                document.getElementById("headerImg").style.display = "none";
                document.getElementById("otherImagePreview").innerHTML = "";
            });
        });

        function validateImage(input, maxFiles, previewId) {
            const allowedExtensions = ["jpg", "jpeg", "png"];
            let files = input.files;

            if (files.length > maxFiles) {
                alert("You can only select up to " + maxFiles + " image(s).");
                input.value = "";
                return;
            }

            for (let file of files) {
                let fileExt = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExt)) {
                    alert("Only JPG, JPEG, and PNG files are allowed.");
                    input.value = "";
                    return;
                }
            }

            if (previewId) {
                previewSingleImage(input, previewId);
            }
        }

        function previewSingleImage(input, previewId) {
            let file = input.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.style.display = "block";
                    preview.style.width = "100px";
                    preview.style.height = "auto";
                };
                reader.readAsDataURL(file);
            }
        }

        function previewMultipleImages(input, previewContainerId) {
            let container = document.getElementById(previewContainerId);
            container.innerHTML = "";

            for (let file of input.files) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.createElement("img");
                    img.src = e.target.result;
                    img.style.width = "50px";
                    img.style.height = "50px";
                    img.style.marginRight = "5px";
                    img.style.borderRadius = "5px";
                    img.style.boxShadow = "0 0 5px rgba(0,0,0,0.2)";
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

    <!-- Edit Event JS -->
    <script>
        function openEditModal(eventData) {
            if (typeof eventData === "string") {
                eventData = JSON.parse(eventData);
            }

            console.log("Event Data:", eventData); // Debugging output

            // Fill event details
            document.getElementById("editEventId").value = eventData.event_id;
            document.getElementById("editEventName").value = eventData.title;
            document.getElementById("editEventDepartment").value = eventData.department;
            document.getElementById("editEventDate").value = eventData.start_datetime;
            document.getElementById("editEventPrice").value = eventData.price;
            document.getElementById("editEventTickets").value = eventData.capacity;
            document.getElementById("editEventDescription").value = eventData.description;
            document.getElementById("editEventStatus").value = eventData.status;

            // Function to check if the image exists
            function getImagePath(image) {
                let path = image && image.trim() !== "" ? "../uploads/events/" + image : "../uploads/events/default.jpg";
                console.log("Resolved Image Path:", path);
                return path;
            }

            // Debug: Check if images are correctly retrieved from `eventData`
            console.log("Event Data Images:");
            console.log("Image 1 (Header):", eventData.image_1);
            console.log("Image 2:", eventData.image_2);
            console.log("Image 3:", eventData.image_3);
            console.log("Image 4:", eventData.image_4);

            // Assign images to <img> elements
            document.getElementById("editHeaderImg").src = getImagePath(eventData.image_1);
            document.getElementById("editImg2").src = getImagePath(eventData.image_2);
            document.getElementById("editImg3").src = getImagePath(eventData.image_3);
            document.getElementById("editImg4").src = getImagePath(eventData.image_4);

            // Debug: Check the loaded image paths
            console.log("Loaded Image Paths:");
            console.log("Header Image:", document.getElementById("editHeaderImg").src);
            console.log("Image 2:", document.getElementById("editImg2").src);
            console.log("Image 3:", document.getElementById("editImg3").src);
            console.log("Image 4:", document.getElementById("editImg4").src);

            // Show modal
            let editModal = new bootstrap.Modal(document.getElementById("editEventModal"));
            editModal.show();
        }

    </script>

    <!-- Delete Event JS -->
    <script>
        // Confirm Delete Action
        function confirmDelete(eventId) {
            if (confirm("Are you sure you want to delete this event?")) {
                // Send delete request via AJAX
                fetch('../includes/delete_event.inc.php', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `event_id=${eventId}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            sessionStorage.setItem("deleteSuccess", "true");
                            window.location.reload();
                        } else {
                            alert(data.message || "Error deleting event.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }

        // Show Delete Notification on Page Load if Set
        document.addEventListener("DOMContentLoaded", function () {
            const deleteAlert = document.getElementById("deleteAlert");
            if (sessionStorage.getItem("deleteSuccess") === "true") {
                deleteAlert.innerHTML = "Event deleted successfully!";
                deleteAlert.classList.remove("d-none");
                sessionStorage.removeItem("deleteSuccess");

                // Auto-hide after 2 seconds
                setTimeout(() => {
                    deleteAlert.classList.add("d-none");
                }, 2000);
            }
        });
    </script>

    <!-- Success Notification JS-->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const successAlert = document.getElementById("successAlert");

            if (urlParams.has("save")) {
                let saveType = urlParams.get("save");
                if (saveType === "success") {
                    successAlert.innerHTML = "Event added successfully!";
                } else if (saveType === "update") {
                    successAlert.innerHTML = "Event updated successfully!";
                }

                successAlert.classList.remove("d-none"); // Show success alert

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    successAlert.classList.add("d-none");
                    window.history.replaceState({}, document.title, window.location.pathname); // Remove URL param
                }, 3000);
            }
        });
    </script>

    <script>
        // Function to preview selected images
        function previewImage(event, imgId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(imgId).src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Function to load existing images from database
        function loadExistingImages(headerImg, img1, img2, img3) {
            if (headerImg) document.getElementById('editHeaderImg').src = "uploads/events/" + headerImg;
            if (img1) document.getElementById('editImg1').src = "uploads/events/" + img1;
            if (img2) document.getElementById('editImg2').src = "uploads/events/" + img2;
            if (img3) document.getElementById('editImg3').src = "uploads/events/" + img3;
        }

        // Example: Load images dynamically from the backend
        // loadExistingImages("header.jpg", "image1.jpg", "image2.jpg", "image3.jpg");
    </script>



</body>

</html>