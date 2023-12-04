<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in and has the 'FAC' role
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role']) || $_COOKIE['role'] !== 'FAD') {
    header("Location: login.html");
    exit();
}

// Check if the child ID is provided
if (!isset($_GET['id'])) {
    header("Location: facadm-view-children.php");
    exit();
}

$childId = $_GET['id'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Update the approval status based on the action
if ($action === 'reject') {
    $approvalStatus = 0; // Set to disapprove
} else {
    $approvalStatus = 1; // Default to approve
}

// Update the child's approval status in the database
$updateStmt = mysqli_prepare($conn, "UPDATE children SET approved = ? WHERE id = ?");
mysqli_stmt_bind_param($updateStmt, "ii", $approvalStatus, $childId);
mysqli_stmt_execute($updateStmt);
mysqli_stmt_close($updateStmt);

header("Location: facadm-view-children.php");
exit();
?>
