<?php
require_once 'session_config.inc.php';
require_once 'dbh.inc.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $department = $_POST['department'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Image upload handling
    $uploadDir = '../uploads/events/';
    $imageFields = ['image_1', 'image_2', 'image_3', 'image_4'];
    $imagePaths = [];

    foreach ($imageFields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $imageName = time() . '_' . basename($_FILES[$field]['name']);
            $targetFilePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetFilePath)) {
                $imagePaths[$field] = $imageName;
            }
        }
    }

    // Fetch existing event images
    $stmt = $pdo->prepare("SELECT image_1, image_2, image_3, image_4 FROM events WHERE event_id = ?");
    $stmt->execute([$event_id]);
    $existingImages = $stmt->fetch(PDO::FETCH_ASSOC);

    // Keep existing images if new ones are not uploaded
    foreach ($imageFields as $field) {
        if (!isset($imagePaths[$field])) {
            $imagePaths[$field] = $existingImages[$field] ?? null;
        }
    }

    // Update event details in the database
    $sql = "UPDATE events SET 
                title = ?, 
                department = ?, 
                start_datetime = ?, 
                description = ?, 
                capacity = ?, 
                price = ?, 
                status = ?, 
                image_1 = ?, 
                image_2 = ?, 
                image_3 = ?, 
                image_4 = ? 
            WHERE event_id = ?";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        $title, $department, $date, $description, $capacity, $price, $status,
        $imagePaths['image_1'], $imagePaths['image_2'], $imagePaths['image_3'], $imagePaths['image_4'],
        $event_id
    ]);

    if ($success) {
        $_SESSION['success'] = "Event updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating event.";
    }

    header("Location: ../pages/event-management.php?save=update");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../pages/event-management.php");
    exit();
}
?>
