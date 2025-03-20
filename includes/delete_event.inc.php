<?php
require_once 'dbh.inc.php'; // Database connection

header('Content-Type: application/json'); // Set header for JSON response

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM Events WHERE event_id = :event_id");
        $stmt->bindParam(":event_id", $eventId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Event deleted successfully!"]);
            exit;
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete event."]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}
