<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in and has the 'FAC' role
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role']) || $_COOKIE['role'] !== 'FAD') {
    header("Location: login.html");
    exit();
}

// Fetch approved children
$approvedChildrenStmt = mysqli_prepare($conn, "SELECT id, first_name, last_name, category, date_of_birth FROM children WHERE approved = 1");
mysqli_stmt_execute($approvedChildrenStmt);
mysqli_stmt_bind_result($approvedChildrenStmt, $childId, $childFirstName, $childLastName, $childCategory, $childDOB);

// Create an array to store approved child details
$approvedChildren = array();

while (mysqli_stmt_fetch($approvedChildrenStmt)) {
    $approvedChildren[] = array(
        'id' => $childId,
        'first_name' => $childFirstName,
        'last_name' => $childLastName,
        'category' => $childCategory,
        'date_of_birth' => $childDOB,
    );
}

// Close the statement
mysqli_stmt_close($approvedChildrenStmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Approved Children</title>
    <link rel="stylesheet" href="viewChildDetails.css"> <!-- Reuse the existing style sheet -->

    <style>
        /* viewApprovedChildren.css */

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

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #2196F3;
    text-decoration: none;
    font-weight: bold;
}

.back-link:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Approved Children</h2>

        <?php
        if (empty($approvedChildren)) {
            echo '<p>No approved children.</p>';
        } else {
            echo '<table>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>First Name</th>';
            echo '<th>Last Name</th>';
            echo '<th>Category</th>';
            echo '<th>Date of Birth</th>';
            echo '</tr>';

            foreach ($approvedChildren as $child) {
                echo '<tr>';
                echo '<td>' . $child['id'] . '</td>';
                echo '<td>' . $child['first_name'] . '</td>';
                echo '<td>' . $child['last_name'] . '</td>';
                echo '<td>' . $child['category'] . '</td>';
                echo '<td>' . $child['date_of_birth'] . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }
        ?>

        <a href="facadm-dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
