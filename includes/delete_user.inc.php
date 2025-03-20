<?php
require_once 'dbh.inc.php';

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Sanitize input

    $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
    if ($stmt->execute([$user_id])) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "Invalid request.";
}
