<?php
require_once 'session_config.inc.php';
require_once 'dbh.inc.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $userId = $_SESSION['user_id'];
        $userName = trim($_POST['user_name']);
        $userEmail = trim($_POST['user_email']);
        $userPhone = trim($_POST['user_phone_number']);
        $userCollege = trim($_POST['user_department']);
        $userCourse = trim($_POST['user_course']);
        $userSection = trim($_POST['user_year_section']);
        $userStudentNumber = trim($_POST['user_student_number']);

        // Default to the current profile image
        $img = $_SESSION['user_img'] ?? "default.png";

        // Handle Image Upload
        if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] === 0) {
            $target_dir = "../uploads/";
            $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
            $allowed_types = ["jpg", "jpeg", "png"];

            // Validate file type
            if (!in_array($imageFileType, $allowed_types)) {
                $_SESSION["update_error"] = "Only JPG, JPEG, and PNG files are allowed.";
                header("Location: ../pages/accountedit.php?error=1");
                exit();
            }

            // Ensure upload directory is writable
            if (!is_writable($target_dir)) {
                $_SESSION["update_error"] = "Upload directory is not writable.";
                header("Location: ../pages/accountedit.php?error=1");
                exit();
            }

            // Generate unique file name
            $fileName = uniqid() . "." . $imageFileType;
            $target_file = $target_dir . $fileName;

            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $img = $fileName;

                // Update user image in database
                $stmt = $pdo->prepare("UPDATE Users SET img = ? WHERE user_id = ?");
                $stmt->execute([$img, $userId]);

                // Update session
                $_SESSION['user_img'] = $img;
            } else {
                $_SESSION["update_error"] = "Error uploading the file.";
                header("Location: ../pages/accountedit.php?error=1");
                exit();
            }
        }

        // Update user details in the database, including student number
        $stmt = $pdo->prepare("UPDATE Users SET name=?, email=?, phone_number=?, department=?, course=?, year_section=?, student_number=? WHERE user_id=?");
        $stmt->execute([$userName, $userEmail, $userPhone, $userCollege, $userCourse, $userSection, $userStudentNumber, $userId]);

        // Update session variables
        $_SESSION['user_name'] = htmlspecialchars($userName);
        $_SESSION['user_email'] = $userEmail;
        $_SESSION['user_phone_number'] = $userPhone;
        $_SESSION['user_department'] = $userCollege;
        $_SESSION['user_course'] = $userCourse;
        $_SESSION['user_year_section'] = $userSection;
        $_SESSION['user_student_number'] = $userStudentNumber;

        header("Location: ../pages/account_page.php?success=1");
        exit();
    } catch (PDOException $e) {
        $_SESSION["update_error"] = "Database error: " . $e->getMessage();
        header("Location: accountedit.php?error=1");
        exit();
    }
} else {
    header("Location: accountedit.php");
    exit();
}
?>
