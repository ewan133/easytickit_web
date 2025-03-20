<?php
require_once 'session_config.inc.php';
require_once 'dbh.inc.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
        exit();
    }

    $userId = $_SESSION["user_id"];
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    try {
        $stmt = $pdo->prepare("SELECT password_hash FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($oldPassword, $user["password_hash"])) {
            echo json_encode(["status" => "error", "message" => "Incorrect old password."]);
            exit();
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password in database
        $stmt = $pdo->prepare("UPDATE Users SET password_hash = ? WHERE user_id = ?");
        $stmt->execute([$hashedPassword, $userId]);

        echo json_encode(["status" => "success", "message" => "Password changed successfully!"]);
        exit();
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit();
}
