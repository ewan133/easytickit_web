<?php
require_once 'session_config.inc.php';
require_once 'dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['reservation_id']) && isset($_POST['new_status'])) {
        $reservation_id = $_POST['reservation_id'];
        $new_status = $_POST['new_status'];

        try {
            // Update the reservation status in the MySQL database
            $sql = "UPDATE Reservations SET status = ? WHERE reservation_id = ?";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$new_status, $reservation_id])) {
                echo "Success";
            } else {
                echo "Failed to update status.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        echo "Invalid request. Missing reservation ID or status.";
    }
} else {
    echo "Invalid request method.";
}
?>
