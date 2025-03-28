<?php
require_once 'session_config.inc.php';
require_once '../includes/dbh.inc.php';

if (isset($_POST['verify_otp'])) {
    echo "Step 1: Form submitted.<br>";

    $entered_otp = trim($_POST['otp']);
    echo "Step 2: OTP Entered: $entered_otp<br>";

    if (!isset($_SESSION['otp'])) {
        echo "Step 3: No OTP found in session.<br>";
        exit();
    } else {
        echo "Step 3: Session OTP: " . $_SESSION['otp'] . "<br>";
    }

    if ($_SESSION['otp'] == $entered_otp) {
        echo "Step 4: OTP Matched.<br>";

        // Retrieve stored user data
        if (!isset($_SESSION['register_data'])) {
            echo "Step 5: No registration data found in session.<br>";
            exit();
        } else {
            echo "Step 5: Registration data found.<br>";
        }

        $data = $_SESSION['register_data'];

        // Hash password
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        echo "Step 6: Password Hashed.<br>";

        // Insert user into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO Users (name, email, password_hash, student_number, phone_number, department, course, year_section) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['name'], $data['email'], $hashed_password, $data['student_number'], $data['phone_number'], $data['department'], $data['course'], $data['year_section']]);
            echo "Step 7: User inserted into the database.<br>";
        } catch (PDOException $e) {
            echo "Step 7: Database error - " . $e->getMessage() . "<br>";
            exit();
        }

        // Cleanup session
        unset($_SESSION['otp']);
        unset($_SESSION['register_data']);
        echo "Step 8: Session cleared.<br>";

        $_SESSION["register_success"] = "Registration successful!";
        echo "Step 9: Redirecting to index.php.<br>";

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
    } else {
        echo "Step 4: Invalid OTP.<br>";
        $_SESSION["register_error"] = "Invalid OTP.";
        header("Location: ../pages/verify_otp.php");
        exit();
    }
} else {
    echo "Step 1: No form submission detected.<br>";
    exit();
}
?>