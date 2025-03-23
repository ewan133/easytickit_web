<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/register.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 h-100">
            <!-- Register Section -->
            <div class="col-md-7 bg-white d-flex flex-column align-items-center justify-content-center p-3">

                <h2 class="text-primary fw-bold">Create Account</h2>
                <p class="text-muted">Fill in the details below to sign up.</p>

                <!-- Display Errors -->
                <?php
                session_start();
                if (isset($_SESSION["register_error"])) {
                    echo '<div class="alert alert-danger">' . $_SESSION["register_error"] . '</div>';
                    unset($_SESSION["register_error"]);
                }
                ?>

                <div class="w-75">
                    <form action="../includes/register.inc.php" method="POST" enctype="multipart/form-data"
                        onsubmit="return validateForm()">

                        <div class="text-center mb-3 d-none">
                            <?php
                            // Default profile image
                            $profileImage = "../uploads/default.jpg";
                            if (isset($_SESSION["uploaded_image"])) {
                                $profileImage = "../uploads/" . $_SESSION["uploaded_image"];
                                unset($_SESSION["uploaded_image"]); // Remove after displaying
                            }
                            ?>

                            <!-- Profile Image Preview Container -->
                            <div class="d-flex flex-column align-items-center">
                                <img id="profilePreview" src="<?= htmlspecialchars($profileImage) ?>"
                                    alt="Profile Picture" width="120"
                                    class="rounded-circle border border-3 shadow-sm mb-2">

                                <!-- Hidden File Input -->
                                <input type="file" name="profile_image" id="profileImage" accept="image/*"
                                    class="d-none">

                                <!-- Centered Add Photo Button -->
                                <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 py-1"
                                    onclick="document.getElementById('profileImage').click();">
                                    <i class="bi bi-camera"></i> Add Photo
                                </button>
                            </div>
                        </div>

                        <input type="hidden" name="profile_image_hidden" id="profileImageHidden">

                        <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>

                        <!-- Phone Number Validation -->
                        <input type="text" name="phone_number" id="phone_number" class="form-control mb-3"
                            placeholder="Phone Number (e.g. 09123456789)" pattern="^09\d{9}$"
                            title="Phone number must be in the format 09123456789" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">

                        <input type="text" name="student_number" class="form-control mb-3" placeholder="Student Number"
                            required>
                        <select name="department" class="form-control mb-3" required>
                            <option value="" disabled selected>Select Department</option>
                            <option value="CABA">CABA</option>
                            <option value="CEIT">CEIT</option>
                            <option value="CAS">CAS</option>
                        </select>

                        <div class="row">
                            <div class="col-md-6">
                                <select name="course" class="form-control mb-3" required>
                                    <option value="" disabled selected>Select Course</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCE">BSCE</option>
                                    <option value="BSEE">BSEE</option>
                                    <option value="BSBA">BSBA</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="year_section" id="year_section" class="form-control mb-3"
                                    placeholder="Year & Section (e.g. 3-7)" pattern="^[1-6]-[1-9]$"
                                    title="Year & Section must be formatted like 3-7" required
                                    oninput="validateYearSection(this);">
                            </div>
                        </div>


                        <input type="email" name="email" class="form-control mb-3" placeholder="Institutional Email"
                            required>

                        <!-- Password Fields with Validation -->
                        <input type="password" name="password" id="password" class="form-control mb-3"
                            placeholder="Password" required>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control mb-3"
                            placeholder="Confirm Password" required>

                        <!-- Password Mismatch Warning -->
                        <div id="password_error" class="text-danger mb-2" style="display: none;">Passwords do not match!
                        </div>

                        <button type="submit" class="btn btn-orange w-100 py-2">Continue</button>
                    </form>
                </div>
            </div>

            <!-- Info Section -->
            <div class="col-md-5 d-flex flex-column align-items-center justify-content-center text-white"
                style="background: linear-gradient(135deg, #008f9a, #007583);">
                <h2 class="fw-bold">ACCOUNT PROFILE</h2>
            </div>
        </div>
    </div>

    <!-- JavaScript Validation -->
    <script>
        document.getElementById('profileImage').addEventListener('change', function (event) {
            let reader = new FileReader();
            reader.onload = function () {
                document.getElementById('profilePreview').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        });

        function validateForm() {
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;
            let passwordError = document.getElementById("password_error");

            if (password.length < 8) {
                passwordError.innerText = "Password must be at least 8 characters long.";
                passwordError.style.display = "block";
                return false;
            }

            if (password !== confirmPassword) {
                passwordError.style.display = "block";
                return false;
            } else {
                passwordError.style.display = "none";
                return true;
            }
        }
    </script>
    
    <script>
        function validateYearSection(input) {
            let value = input.value.replace(/[^1-9-]/g, ''); // Allow only 1-6 and dash
            if (value.length === 1 && !/^[1-9]$/.test(value)) {
                value = ''; // Clear input if the first character is not 1-6
            }
            if (value.length === 2 && value[1] !== '-') {
                value = value[0] + '-'; // Automatically insert a dash after the first digit
            }
            if (value.length > 3) {
                value = value.slice(0, 3); // Limit input to exactly 3 characters
            }
            input.value = value;
        }
    </script>

</body>

</html>