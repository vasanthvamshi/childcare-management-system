<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role']) || $_COOKIE['role'] !== 'FAD') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* facultyDashboard.css */

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
    margin-top: 40px;
}

.dashboard-box {
    width: 200px;
    height: 200px;
    background-color: #e0e0e0;
    border: 1px solid #ccc;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.3s;
    margin: 10px;
}

.dashboard-box:hover {
    background-color: #d0d0d0;
}

.dashboard-box a {
    text-decoration: none;
    color: #333;
}

.dashboard-box h3 {
    margin: 10px 0 0;
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

        <div class="dashboard-options">
            <!-- Option 1: Enroll Child Details -->
            <div class="dashboard-box">
                <a href="facadm-enroll-child.php">
                    <h3>Enroll Child Details</h3>
                </a>
            </div>

            <!-- Option 2: View Child Details -->
            <div class="dashboard-box">
                <a href="facadm-view-children.php">
                    <h3>View Child Details</h3>
                </a>
            </div>

            <!-- Option 3: Assign Child to Teacher -->
            <div class="dashboard-box">
                <a href="facadm-assign-children.php">
                    <h3>Assign Child to Teacher</h3>
                </a>
            </div>
        </div>

        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>
