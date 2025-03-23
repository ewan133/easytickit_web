<?php
require_once 'session_config.inc.php';
require_once 'dbh.inc.php';

echo "Script started<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received<br>";

    if (!isset($_SESSION['user_id'])) {
        echo "User not logged in, redirecting to login.<br>";
        $_SESSION["reservation_error"] = "You must be logged in to unreserve an event.";
        header("Location: ../pages/login.php");
        exit();
    }

    $userId = $_SESSION['user_id'];
    $eventId = isset($_POST['event_id']) ? (int)$_POST['event_id'] : null; // Convert to integer

    echo "User ID: $userId, Event ID: $eventId<br>";

    if (!$eventId) {
        echo "Invalid event ID.<br>";
        $_SESSION["reservation_error"] = "Invalid event ID.";
        header("Location: ../pages/detailed_events.php?event_id=" . urlencode($eventId));
        exit();
    }

    try {
        echo "Starting transaction.<br>";
        $pdo->beginTransaction();

        // Check if the reservation exists
        $stmt = $pdo->prepare("SELECT * FROM Reservations WHERE user_id = ? AND event_id = ?");
        $stmt->execute([$userId, $eventId]);

        if ($stmt->rowCount() === 0) {
            echo "No reservation found.<br>";
            $_SESSION["reservation_error"] = "You have no reservation for this event.";
            $pdo->rollBack();
            header("Location: ../pages/detailed_events.php?event_id=" . urlencode($eventId));
            exit();
        }

        echo "Reservation found, proceeding with deletion.<br>";

        // Remove the reservation
        $stmt = $pdo->prepare("DELETE FROM Reservations WHERE user_id = ? AND event_id = ?");
        $stmt->execute([$userId, $eventId]);

        echo "Reservation deleted.<br>";

        // Decrease the reserved count in the Events table (ensure it does not go below zero)
        $stmt = $pdo->prepare("UPDATE Events SET reserved = GREATEST(reserved - 1, 0) WHERE event_id = ?");
        $stmt->execute([$eventId]);

        echo "Event reserved count updated.<br>";

        // Fetch event details for notification
        $stmt = $pdo->prepare("SELECT title FROM Events WHERE event_id = ?");
        $stmt->execute([$eventId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "Event title fetched: {$event['title']}<br>";

        // Notify the user about the cancellation
        $stmt = $pdo->prepare("INSERT INTO Notifications (user_id, event_id, message, status) VALUES (?, ?, ?, 'unread')");
        $stmt->execute([$userId, $eventId, "You have unreserved '{$event['title']}'."]);

        echo "User notification inserted.<br>";

        // Notify admin (user_id = 1) about the unreservation
        $stmt = $pdo->prepare("INSERT INTO Notifications (user_id, event_id, message, status) VALUES (1, ?, ?, 'unread')");
        $stmt->execute([$eventId, "A reservation for '{$event['title']}' has been cancelled."]);

        echo "Admin notification inserted.<br>";

        $pdo->commit();
        echo "Transaction committed.<br>";

        $_SESSION["reservation_success"] = "
            <div class='alert alert-success text-center'>
                <strong>Unreservation successful!</strong> <br>
                <a href='../pages/detailed_events.php?event_id=" . urlencode($eventId) . "' class='btn btn-outline-light mt-3'>Back to Event</a>
            </div>
        ";

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Database error: " . htmlspecialchars($e->getMessage()) . "<br>";

        $_SESSION["reservation_error"] = "
            <div class='alert alert-danger text-center'>
                <strong>Database error:</strong> " . htmlspecialchars($e->getMessage()) . " <br>
                <a href='../pages/detailed_events.php?event_id=" . urlencode($eventId) . "' class='btn btn-outline-light mt-3'>Back to Event</a>
            </div>
        ";
    }

    echo "Redirecting to detailed_events.php?event_id=" . $eventId . "<br>";
    header("Location: ../pages/detailed_events.php?event_id=" . urlencode($eventId));
    exit();
}

echo "No POST request detected, exiting script.<br>";
?>
