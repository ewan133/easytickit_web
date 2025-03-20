<?php
require_once 'session_config.inc.php';
require_once 'dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
        exit();
    }

    // Sanitize and validate input
    $admin_id = $_SESSION["user_id"];
    $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));
    $description = trim(filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING));
    $department = trim(filter_input(INPUT_POST, "department", FILTER_SANITIZE_STRING));
    $start_datetime = $_POST["date"] ?? '';
    $price = filter_var($_POST["price"], FILTER_VALIDATE_FLOAT);
    $capacity = filter_var($_POST["capacity"], FILTER_VALIDATE_INT);

    if (!$title || !$description || !$department || !$start_datetime || $price === false || $capacity === false) {
        echo json_encode(["status" => "error", "message" => "Invalid input data."]);
        exit();
    }

    $target_dir = "../uploads/events/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $allowed_extensions = ["jpg", "jpeg", "png"];
    $image_1 = $image_2 = $image_3 = $image_4 = "default_image.jpg";

    function uploadImage($file, $target_dir, $allowed_extensions) {
        if (isset($file) && $file['error'] === 0) {
            $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (in_array($imageFileType, $allowed_extensions)) {
                $fileName = uniqid() . "." . $imageFileType;
                if (move_uploaded_file($file["tmp_name"], $target_dir . $fileName)) {
                    return $fileName;
                }
            }
        }
        return "default_image.jpg";
    }

    // Upload Header Image
    $image_1 = uploadImage($_FILES["headerPhoto"], $target_dir, $allowed_extensions);

    // Upload Other Photos
    $uploadedImages = [];
    if (isset($_FILES["otherPhotos"]) && is_array($_FILES["otherPhotos"]["name"])) {
        foreach ($_FILES["otherPhotos"]["name"] as $key => $name) {
            $file = [
                "name" => $_FILES["otherPhotos"]["name"][$key],
                "tmp_name" => $_FILES["otherPhotos"]["tmp_name"][$key],
                "error" => $_FILES["otherPhotos"]["error"][$key]
            ];
            $uploadedImages[] = uploadImage($file, $target_dir, $allowed_extensions);
        }
    }

    // Assign uploaded images, fallback to default
    $image_2 = $uploadedImages[0] ?? "default_image.jpg";
    $image_3 = $uploadedImages[1] ?? "default_image.jpg";
    $image_4 = $uploadedImages[2] ?? "default_image.jpg";

    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO events (admin_id, title, description, department, start_datetime, price, capacity, image_1, image_2, image_3, image_4, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
        $stmt->execute([$admin_id, $title, $description, $department, $start_datetime, $price, $capacity, $image_1, $image_2, $image_3, $image_4]);

        echo json_encode(["status" => "success", "message" => "Event added successfully."]);
        header("Location: ../pages/event-management.php?save=success");
        exit();
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        header("Location: ../pages/event-management.php?save=failed");
        exit();
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit();
}