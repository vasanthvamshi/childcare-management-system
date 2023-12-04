<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in and has the 'TEA' role
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role']) || $_COOKIE['role'] !== 'TEA') {
    header("Location: login.html");
    exit();
}
$teacherEmail = $_COOKIE['email'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $childIds = $_POST['child_id'];
    $clockInTimes = $_POST['clock_in_time'];
    $clockOutTimes = $_POST['clock_out_time'];

    // Loop through the arrays
    foreach ($childIds as $key => $childId) {
        $clockInTime = $clockInTimes[$key];
        $clockOutTime = $clockOutTimes[$key];

        // Check if either clock_in_time or clock_out_time is empty
        if (!empty($clockInTime) || !empty($clockOutTime)) {
            // Insert attendance record into the teacher_attendance table
            $insertAttendanceStmt = mysqli_prepare($conn, "INSERT INTO teacher_attendance (teacher_email, child_id, clock_in_time, clock_out_time) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insertAttendanceStmt, "siss", $teacherEmail, $childId, $clockInTime, $clockOutTime);
            mysqli_stmt_execute($insertAttendanceStmt);
            mysqli_stmt_close($insertAttendanceStmt);
        }
    }
}

// Fetch children assigned to the teacher
$teacherEmail = $_COOKIE['email'];
$assignedChildrenStmt = mysqli_prepare($conn, "SELECT c.id, c.first_name, c.last_name FROM assignments a JOIN children c ON a.child_id = c.id WHERE a.teacher_email = ?");
mysqli_stmt_bind_param($assignedChildrenStmt, "s", $teacherEmail);
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
    <title>Teacher Attendance</title>
    <link rel="stylesheet" href="attendance.css"> <!-- Create a new style sheet for the attendance page -->
    <style>
        /* attendance.css */

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

input[type="datetime-local"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 16px;
    box-sizing: border-box;
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
        <h2>Children Attendance</h2>

        <?php
        if (empty($assignedChildren)) {
            echo '<p>No children assigned to you.</p>';
        } else {
            echo '<form method="post">';
            echo '<table>';
            echo '<tr>';
            echo '<th>Child ID</th>';
            echo '<th>Child Name</th>';
            echo '<th>Clock In Time</th>';
            echo '<th>Clock Out Time</th>';
            echo '</tr>';

            foreach ($assignedChildren as $child) {
                echo '<tr>';
                echo '<td>' . $child['id'] . '<input type="hidden" name="child_id[]" value="' . $child['id'] . '"></td>';
                echo '<td>' . $child['first_name'] . ' ' . $child['last_name'] . '</td>';
                echo '<td><input type="datetime-local" name="clock_in_time[]"></td>';
                echo '<td><input type="datetime-local" name="clock_out_time[]"></td>';
                echo '</tr>';
            }

            echo '</table>';
            echo '<input type="submit" value="Submit Attendance">';
            echo '</form>';
        }
        ?>

        <a href="teacher-dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
