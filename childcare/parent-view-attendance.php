<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in and has the 'PAR' role
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role']) || $_COOKIE['role'] !== 'PAR') {
    header("Location: login.html");
    exit();
}

$parentEmail = $_COOKIE['email'];

// echo $parentEmail;

// Fetch children assigned to the parent
$assignedChildrenStmt = mysqli_prepare($conn, "SELECT c.id, c.first_name, c.last_name FROM assignments a JOIN children c ON a.child_id = c.id WHERE c.parent_email = ?");
mysqli_stmt_bind_param($assignedChildrenStmt, "s", $parentEmail);
mysqli_stmt_execute($assignedChildrenStmt);
mysqli_stmt_bind_result($assignedChildrenStmt, $childId, $childFirstName, $childLastName);


// Create an array to store assigned child details
$assignedChildren = array();

while (mysqli_stmt_fetch($assignedChildrenStmt)) {
    $assignedChildren[] = array(
        'id' => $childId,
        'first_name' => $childFirstName,
        'last_name' => $childLastName,
    );
}

// Close the statement
mysqli_stmt_close($assignedChildrenStmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Attendance</title>
    <link rel="stylesheet" href="parentAttendance.css"> <!-- Create a new style sheet for the parent attendance page -->
    <style>
        /* parentAttendance.css */

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
        <h2>Child Attendance</h2>

        <?php
        if (empty($assignedChildren)) {
            echo '<p>No children assigned to you.</p>';
        } else {
            echo '<table>';
            echo '<tr>';
            echo '<th>Child ID</th>';
            echo '<th>Child Name</th>';
            echo '<th>Clock In Time</th>';
            echo '<th>Clock Out Time</th>';
            echo '</tr>';

            foreach ($assignedChildren as $child) {
                // Fetch attendance details for each child
                $attendanceStmt = mysqli_prepare($conn, "SELECT clock_in_time, clock_out_time FROM teacher_attendance WHERE child_id = ? ORDER BY clock_in_time DESC");
                mysqli_stmt_bind_param($attendanceStmt, "i", $child['id']);
                mysqli_stmt_execute($attendanceStmt);
                mysqli_stmt_bind_result($attendanceStmt, $clockInTime, $clockOutTime);
            
                
            
                // Display all attendance records for the child
                while (mysqli_stmt_fetch($attendanceStmt)) {
                    echo '<tr>';
                echo '<td>' . $child['id'] . '</td>';
                echo '<td>' . $child['first_name'] . ' ' . $child['last_name'] . '</td>';
                    echo "<td>". ($clockInTime ? date('Y-m-d H:i:s', strtotime($clockInTime)) : 'N/A') . '</td>';
                    echo "<td>". ($clockOutTime ? date('Y-m-d H:i:s', strtotime($clockOutTime)) : 'N/A') . '</td>';
                }
            
                
                echo '</tr>';
            
                // Close the statement inside the loop to reset for the next child
                mysqli_stmt_close($attendanceStmt);
            }

            echo '</table>';
        }
        ?>

        <a href="parent-dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
