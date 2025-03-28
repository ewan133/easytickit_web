<?php
require_once 'dbh.inc.php';
require_once 'session_config.inc.php';

// Fetch and store events
$_SESSION['events'] = getAllLatestEvents($pdo);

// Fetch and store users
$_SESSION['users'] = getAllUsers($pdo);

// Fetch and store most rated events
$_SESSION['most_rated_events'] = getMostRatedEvents($pdo);

// Fetch and store total active events
$_SESSION['total_active_events'] = getTotalActiveEvents($pdo);

//Carousel Events fetching
$_SESSION['new_events'] = getTopThreeEvents($pdo);

// Fetch and store users by department
$_SESSION['users_by_department'] = getUsersByDepartment($pdo);

//Fetch all active events for events page
$_SESSION['active_events'] = getAllActiveEvents($pdo);

// Fetch user's reserved events (if logged in)
if (isset($_SESSION['user_id'])) {
    $_SESSION['reserved_events'] = getUserReservedEvents($pdo, $_SESSION['user_id']);
}

$reserved_users = getAllReservedUsersForActiveEvents($pdo);


// Function to get all latest events
function getAllLatestEvents($pdo)
{
    $sql = "SELECT * FROM Events ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all users
function getAllUsers($pdo)
{
    $sql = "SELECT * FROM Users WHERE role != 'admin' ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get the most rated events
function getMostRatedEvents($pdo)
{
    $sql = "SELECT * FROM Events ORDER BY ratings DESC, created_at DESC LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get the total number of active events
function getTotalActiveEvents($pdo)
{
    $sql = "SELECT COUNT(*) as total_active FROM Events WHERE status = 'active'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_active'];
}

//function for getting top 3 events for corousel
function getTopThreeEvents($pdo)
{
    $sql = "SELECT * FROM Events ORDER BY created_at DESC LIMIT 3";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get the count of users in each unique department
function getUsersByDepartment($pdo)
{
    $sql = "SELECT course, COUNT(*) as user_count 
            FROM Users 
            WHERE course IS NOT NULL 
              AND course != '' 
              AND course != 'N/A' 
            GROUP BY course";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all active events
function getAllActiveEvents($pdo)
{
    $sql = "SELECT * FROM Events WHERE status = 'active' ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all the events that user reserved
function getUserReservedEvents($pdo, $userId)
{
    $sql = "SELECT e.event_id, e.title, e.image_1, e.start_datetime, e.price, r.status 
            FROM Reservations r
            INNER JOIN Events e ON r.event_id = e.event_id
            WHERE r.user_id = ?
            ORDER BY r.reserved_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all users who have reserved active events
function getAllReservedUsersForActiveEvents($pdo)
{
    $sql = "SELECT u.user_id, u.name, u.email, u.phone_number, u.student_number, 
                   e.event_id, e.title, e.start_datetime, e.price, e.status, 
                   r.status as reservation_status, r.reserved_at
            FROM Reservations r
            INNER JOIN Users u ON r.user_id = u.user_id
            INNER JOIN Events e ON r.event_id = e.event_id
            WHERE e.status = 'active'
            ORDER BY e.start_datetime DESC, r.reserved_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

