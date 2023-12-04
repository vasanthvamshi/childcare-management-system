<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role'])) {
    header("Location: login.html");
    exit();
}

// Get the parent's email from the cookie
$parentEmail = $_COOKIE['email'];

// Fetch children linked to the parent's email
$childrenStmt = mysqli_prepare($conn, "SELECT id, first_name, last_name, category, date_of_birth, approved FROM children WHERE parent_email = ?");
mysqli_stmt_bind_param($childrenStmt, "s", $parentEmail);
mysqli_stmt_execute($childrenStmt);
mysqli_stmt_bind_result($childrenStmt, $childId, $childFirstName, $childLastName, $childCategory, $childDOB, $approved);

// Create an array to store child details
$children = array();

while (mysqli_stmt_fetch($childrenStmt)) {
    $children[] = array(
        'id' => $childId,
        'first_name' => $childFirstName,
        'last_name' => $childLastName,
        'category' => $childCategory,
        'date_of_birth' => $childDOB,
        'approved' => $approved,
    );
}

// Close the statement
mysqli_stmt_close($childrenStmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Children</title>
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
            margin-bottom: 20px;
        }

        p {
            color: #555;
            margin-top: 20px;
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

        .not-approved {
            background-color: #ffcccc;
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
        <h2>Children Linked to <?php echo $parentEmail; ?></h2>

        <?php
        if (empty($children)) {
            echo '<p>No children linked to your account.</p>';
        } else {
            echo '<table>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>First Name</th>';
            echo '<th>Last Name</th>';
            echo '<th>Category</th>';
            echo '<th>Date of Birth</th>';
            echo '<th>Approved</th>';
            echo '</tr>';

            foreach ($children as $child) {
                echo '<tr>';
                echo '<td>' . $child['id'] . '</td>';
                echo '<td>' . $child['first_name'] . '</td>';
                echo '<td>' . $child['last_name'] . '</td>';
                echo '<td>' . $child['category'] . '</td>';
                echo '<td>' . $child['date_of_birth'] . '</td>';
                echo '<td>' . ($child['approved'] ? 'Yes' : 'No') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }
        ?>

        <a href="parent-dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
