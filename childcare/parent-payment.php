<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in and has the 'PAR' role
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role']) || $_COOKIE['role'] !== 'PAR') {
    header("Location: login.html");
    exit();
}

$parentEmail = $_COOKIE['email'];

// Fetch children assigned to the parent
$assignedChildrenStmt = mysqli_prepare($conn, "SELECT c.id, c.first_name, c.last_name, c.category FROM assignments a JOIN children c ON a.child_id = c.id WHERE c.parent_email = ?");
mysqli_stmt_bind_param($assignedChildrenStmt, "s", $parentEmail);
mysqli_stmt_execute($assignedChildrenStmt);
mysqli_stmt_bind_result($assignedChildrenStmt, $childId, $childFirstName, $childLastName, $childAgeCategory);

// Create an array to store assigned child details
$assignedChildren = array();

while (mysqli_stmt_fetch($assignedChildrenStmt)) {
    $assignedChildren[] = array(
        'id' => $childId,
        'first_name' => $childFirstName,
        'last_name' => $childLastName,
        'age_category' => $childAgeCategory,
    );
}

// Close the statement
mysqli_stmt_close($assignedChildrenStmt);

// Define the rate per week for each age category
$ratePerWeek = array(
    'Infant' => 300,
    'Toddler' => 275,
    'Twadler' => 250,
    '3YearsOld' => 225,
    '4YearsOld' => 200,
);

$paymentDetails = array();

foreach ($assignedChildren as $child) {
    // Fetch the number of clocked-in days for each child
    $clockedInDaysStmt = mysqli_prepare($conn, "SELECT COUNT(DISTINCT DATE(clock_in_time)) FROM teacher_attendance WHERE child_id = ?");
    mysqli_stmt_bind_param($clockedInDaysStmt, "i", $child['id']);
    mysqli_stmt_execute($clockedInDaysStmt);
    mysqli_stmt_bind_result($clockedInDaysStmt, $numClockedInDays);

    // Fetch the first row (there should be only one result)
    mysqli_stmt_fetch($clockedInDaysStmt);

    // Close the statement
    mysqli_stmt_close($clockedInDaysStmt);

    // Calculate the payment for each child
    $paymentDetails[] = array(
        'child_id' => $child['id'],
        'child_name' => $child['first_name'] . ' ' . $child['last_name'],
        'rate_per_week' => $ratePerWeek[$child['age_category']],
        'num_clocked_in_days' => $numClockedInDays,
        'total_payment' => ($ratePerWeek[$child['age_category']] / 7) * $numClockedInDays,
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link rel="stylesheet" href="paymentDetails.css"> <!-- Create a new style sheet for the payment details page -->

    <style>
        /* paymentDetails.css */

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
        <h2>Payment Details</h2>

        <?php
        if (empty($assignedChildren)) {
            echo '<p>No children assigned to you.</p>';
        } else {
            echo '<table>';
            echo '<tr>';
            echo '<th>Child ID</th>';
            echo '<th>Child Name</th>';
            echo '<th>Age Category</th>';
            echo '<th>Rate per Week</th>';
            echo '<th>Clocked In Days</th>';
            echo '<th>Total Payment</th>';
            echo '</tr>';

            foreach ($paymentDetails as $details) {
                echo '<tr>';
                echo '<td>' . $details['child_id'] . '</td>';
                echo '<td>' . $details['child_name'] . '</td>';
                echo '<td>' . $details['age_category'] . '</td>';
                echo '<td>$' . $details['rate_per_week'] . '/wk</td>';
                echo '<td>' . $details['num_clocked_in_days'] . '</td>';
                echo '<td>$' . number_format($details['total_payment'], 2) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }
        ?>

        <a href="parent-dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
