<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role'])) {
    header("Location: login.html");
    exit();
}

// Display the dashboard based on the user's role
$userRole = $_COOKIE['role'];
$userEmail = $_COOKIE['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 80%;
    text-align: center;
}

h2 {
    color: #333;
}

.dashboard-options {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.dashboard-box {
    width: 150px;
    height: 150px;
    background-color: #e0e0e0;
    border: 1px solid #ccc;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.3s;
}

.dashboard-box:hover {
    background-color: #d0d0d0;
}

.dashboard-box a {
    text-decoration: none;
    color: #333;
}

.dashboard-box h3 {
    margin: 0;
}

.logout-link {
    display: block;
    margin-top: 20px;
    color: #4caf50;
    text-decoration: none;
}

.logout-link:hover {
    text-decoration: underline;
}



    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_COOKIE['email']; ?>!</h2>
        <br /><br /><br /><br />
        <div class="dashboard-options">
            <!-- Option 1: Add Child Details -->
            <div class="dashboard-box">
                <a href="parent-add-child.php">
                    <h3>Add Child Details</h3>
                </a>
            </div>

            <!-- Option 2: View Attendance -->
            <div class="dashboard-box">
                <a href="parent-view-attendance.php">
                    <h3>View Attendance</h3>
                </a>
            </div>

            <!-- Option 3: Another Option -->
            <div class="dashboard-box">
                <a href="parent-view-child.php">
                    <h3>View Children</h3>
                </a>
            </div>

            <!-- Option 4: Yet Another Option -->
            <div class="dashboard-box">
                <a href="parent-payment.php">
                    <h3>Payment Details</h3>
                </a>
            </div>
        </div>
        <br /><br /><br /><br />
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>