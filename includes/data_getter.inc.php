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
    $sql = "SELECT * FROM Users ORDER BY created_at DESC";
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
