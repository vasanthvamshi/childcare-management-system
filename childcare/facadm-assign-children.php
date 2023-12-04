<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in and has the 'FAC' role
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role']) || $_COOKIE['role'] !== 'FAD') {
    header("Location: login.html");
    exit();
}

// Fetch unassigned children
$unassignedChildrenStmt = mysqli_prepare($conn, "SELECT id, first_name, last_name FROM children WHERE approved = 1 AND id NOT IN (SELECT child_id FROM assignments)");
mysqli_stmt_execute($unassignedChildrenStmt);
mysqli_stmt_bind_result($unassignedChildrenStmt, $childId, $childFirstName, $childLastName);

// Create an array to store unassigned child details
$unassignedChildren = array();

while (mysqli_stmt_fetch($unassignedChildrenStmt)) {
    $unassignedChildren[] = array(
        'id' => $childId,
        'first_name' => $childFirstName,
        'last_name' => $childLastName,
    );
}

// Close the statement
mysqli_stmt_close($unassignedChildrenStmt);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $childId = $_POST['child_id'];
    $teacherEmail = $_POST['teacher_email'];

    // Insert assignment into the assignments table
    $insertAssignmentStmt = mysqli_prepare($conn, "INSERT INTO assignments (child_id, teacher_email) VALUES (?, ?)");
    mysqli_stmt_bind_param($insertAssignmentStmt, "is", $childId, $teacherEmail);
    mysqli_stmt_execute($insertAssignmentStmt);
    mysqli_stmt_close($insertAssignmentStmt);

    // Redirect to the same page to refresh the list
    header("Location: facadm-assign-children.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Children to Teachers</title>
    <link rel="stylesheet" href="enrollChildDetails.css"> <!-- Use existing or create a new style sheet -->
    <style>
        /* enrollChildDetails.css */

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

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
}

select, input {
    width: 100%;
    padding: 10px;
    margin-bottom: 16px;
    box-sizing: border-box;
}

select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"] {
    background-color: #4caf50;
    color: #fff;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    padding: 12px;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
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
        <h2>Assign Children to Teachers</h2>

        <?php
        if (empty($unassignedChildren)) {
            echo '<p>No unassigned children.</p>';
        } else {
            echo '<form method="post">';
            echo '<label for="child">Select a child:</label>';
            echo '<select name="child_id" id="child">';
            
            foreach ($unassignedChildren as $child) {
                echo '<option value="' . $child['id'] . '">' . $child['first_name'] . ' ' . $child['last_name'] . '</option>';
            }

            echo '</select>';
            echo '<br>';
            echo '<label for="teacher">Select a teacher:</label>';
            echo '<select name="teacher_email" id="teacher">';
            // Fetch and display faculty admins as teachers (you may customize this based on your actual users)
            $teachersStmt = mysqli_prepare($conn, "SELECT email FROM creds WHERE role = 'TEA'");
            mysqli_stmt_execute($teachersStmt);
            mysqli_stmt_bind_result($teachersStmt, $teacherEmail);

            while (mysqli_stmt_fetch($teachersStmt)) {
                echo '<option value="' . $teacherEmail . '">' . $teacherEmail . '</option>';
            }

            mysqli_stmt_close($teachersStmt);
            
            echo '</select>';
            echo '<br>';
            echo '<input type="submit" value="Assign">';
            echo '</form>';
        }
        ?>

        <a href="facadm-dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
