<?php
require_once 'session_config.inc.php';
require_once 'dbh.inc.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate required fields
    $required_fields = ["name", "email", "password", "confirm_password", "student_number", "phone_number", "department", "course", "year_section"];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $_SESSION["register_error"] = "All fields are required!";
            header("Location: ../pages/register.php");
            exit();
        }
    }

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $student_number = trim($_POST["student_number"]);
    $phone_number = trim($_POST["phone_number"]);
    $department = trim($_POST["department"]);
    $course = trim($_POST["course"]);
    $year_section = trim($_POST["year_section"]);

    // Password confirmation check
    if ($password !== $confirm_password) {
        $_SESSION["register_error"] = "Passwords do not match!";
        header("Location: ../pages/register.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Default profile image
    $img = "default.jpg";

    // Handle Image Upload
    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] === 0) {
        $target_dir = "../uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png"];
        
        // Validate file type
        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION["register_error"] = "Only JPG, JPEG, and PNG files are allowed.";
            header("Location: ../pages/register.php");
            exit();
        }

        // Ensure upload directory is writable
        if (!is_writable($target_dir)) {
            $_SESSION["register_error"] = "Upload directory is not writable.";
            header("Location: ../pages/register.php");
            exit();
        }

        // Generate unique file name
        $fileName = uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $fileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $img = $fileName;
        } else {
            $_SESSION["register_error"] = "Error uploading the file.";
            header("Location: ../pages/register.php");
            exit();
        }
    }

    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO Users (name, email, password_hash, img, student_number, phone_number, department, course, year_section) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password, $img, $student_number, $phone_number, $department, $course, $year_section]);

        // Fetch the newly created user
        $user_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Store user data in session
        session_regenerate_id(true);
        // Setting each session variable individually
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["user_name"] = htmlspecialchars($user["name"]);
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["user_role"] = $user["role"];
        $_SESSION["user_student_number"] = $user["student_number"];
        $_SESSION["user_phone_number"] = $user["phone_number"];
        $_SESSION["user_department"] = $user["department"];
        $_SESSION["user_course"] = $user["course"];
        $_SESSION["user_year_section"] = $user["year_section"];
        $_SESSION["user_img"] = $user["img"];

        $_SESSION["register_success"] = "Registration successful!";
        header("Location: ../index.php");
        
        exit();
    } catch (PDOException $e) {
        $_SESSION["register_error"] = "Database error: " . $e->getMessage();
        header("Location: ../pages/register.php");
        exit();
    }
} else {
    header("Location: ../pages/register.php");
    exit();
}
