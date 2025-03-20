<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    try {
        require_once 'session_config.inc.php';
        require_once 'dbh.inc.php'; // Database connection

        // Fetch user by email
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if (!$user) {
            $_SESSION["login_error"] = "Invalid email or password.";
            header("Location: ../pages/login.php");
            exit();
        }

        // Check if password matches
        if (!password_verify($password, $user["password_hash"])) {
            $_SESSION["login_error"] = "Invalid email or password.";
            header("Location: ../pages/login.php");
            exit();
        }

        // If everything is correct, log the user in
        //session_regenerate_id(true);
        $_SESSION["user_data"] = [
            "user_id" => $user["user_id"],
            "name" => htmlspecialchars($user["name"]),
            "email" => $user["email"],
            "role" => $user["role"],
            "student_number" => $user["student_number"],
            "phone_number" => $user["phone_number"],
            "department" => $user["department"],
            "course" => $user["course"],
            "year_section" => $user["year_section"],
            "img" => $user["img"]
        ];

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




        // Redirect based on role (optional: redirect admins to admin panel)
        if ($user["role"] === "admin") {
            header("Location: ../pages/dashboard.php?login=success");
            exit(); // Prevent further execution
        } else {
            header("Location: ../index.php");
            exit();
        }


        exit();
    } catch (PDOException $e) {
        $_SESSION["login_error"] = "Database error: " . $e->getMessage();
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}

