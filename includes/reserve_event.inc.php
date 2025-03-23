<?php
require_once 'session_config.inc.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") { // Using GET method for reservation

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION["reservation_error"] = "You must be logged in to reserve an event.";
        header("Location: ../pages/login.php");
        exit();
    }

    $userId = $_SESSION['user_id'];
    $eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : null; // Get event_id from GET request
    $transactionStarted = false; // Flag to track transaction

    try {
        require_once 'dbh.inc.php'; // Database connection

        // Validate event_id
        if (!$eventId || !ctype_digit((string) $eventId)) {
            $_SESSION["reservation_error"] = "Invalid event ID.";
            header("Location: ../pages/detailed_events.php?event_id=" . $eventId);
            exit();
        }

        // Fetch event details
        $stmt = $pdo->prepare("SELECT * FROM Events WHERE event_id = ? AND status = 'active'");
        $stmt->execute([$eventId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $_SESSION["reservation_error"] = "Event not found or is no longer active.";
            header("Location: ../pages/detailed_events.php?event_id=" . $eventId);
            exit();
        }

        // Check if event is fully booked (considering reservations)
        if (($event['reserved'] + $event['sold']) >= $event['capacity']) {
            $_SESSION["reservation_error"] = "Event is fully booked.";
            header("Location: ../pages/detailed_events.php?event_id=" . $eventId);
            exit();
        }

        // Check if the user has already reserved this event
        $stmt = $pdo->prepare("SELECT * FROM Reservations WHERE user_id = ? AND event_id = ?");
        $stmt->execute([$userId, $eventId]);

        if ($stmt->rowCount() > 0) {
            $_SESSION["reservation_error"] = "You have already reserved this event.";
            header("Location: ../pages/detailed_events.php?event_id=" . $eventId);
            exit();
        }

        // Start database transaction
        $pdo->beginTransaction();
        $transactionStarted = true; // Set flag

        // Insert reservation into Reservations table
        $stmt = $pdo->prepare("INSERT INTO Reservations (user_id, event_id, status, payment_status) VALUES (?, ?, 'pending', 'pending')");
        $stmt->execute([$userId, $eventId]);

        // Update event's reserved count
        $stmt = $pdo->prepare("UPDATE Events SET reserved = reserved + 1 WHERE event_id = ?");
        $stmt->execute([$eventId]);

        // Notify user about their reservation
        $stmt = $pdo->prepare("INSERT INTO Notifications (user_id, event_id, message, status) VALUES (?, ?, ?, 'unread')");
        $stmt->execute([$userId, $eventId, "Your reservation for '{$event['title']}' is pending approval."]);

        // Notify admin (user_id = 1) about the new reservation
        $stmt = $pdo->prepare("INSERT INTO Notifications (user_id, event_id, message, status) VALUES (1, ?, ?, 'unread')");
        $stmt->execute([$eventId, "A new reservation has been made for '{$event['title']}'"]);

        // Commit the transaction
        $pdo->commit();

        // Success Message
        $_SESSION["reservation_success"] = "
            <div class='alert alert-success text-center'>
                <strong>Reservation successful!</strong> <br>
                <a href='../pages/detailed_events.php?event_id=" . $eventId . "' class='btn btn-outline-light mt-3'>Back to Event</a>
            </div>
        ";
    } catch (PDOException $e) {
        // Rollback only if transaction was started
        if ($transactionStarted) {
            $pdo->rollBack();
        }

        $_SESSION["reservation_error"] = "
            <div class='alert alert-danger text-center'>
                <strong>Database error:</strong> " . htmlspecialchars($e->getMessage()) . " <br>
                <a href='../pages/detailed_events.php?event_id=" . $eventId . "' class='btn btn-outline-light mt-3'>Back to Event</a>
            </div>
        ";
    }

    header("Location: ../pages/detailed_events.php?event_id=" . $eventId);
    exit();
}
?>
