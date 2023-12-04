<?php
session_start();
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in and has the 'TEA' role
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role']) || $_COOKIE['role'] !== 'TEA') {
    header("Location: login.html");
    exit();
}

$teacherEmail = $_COOKIE['email'];


// Fetch teacher details
$teacherDetailsStmt = mysqli_prepare($conn, "SELECT email, fname, lname FROM creds WHERE email = ?");
mysqli_stmt_bind_param($teacherDetailsStmt, "s", $teacherEmail);
mysqli_stmt_execute($teacherDetailsStmt);
mysqli_stmt_bind_result($teacherDetailsStmt, $teacherEmail, $teacherFirstName, $teacherLastName);

// Fetch the first row (there should be only one result)
mysqli_stmt_fetch($teacherDetailsStmt);

// Close the statement
mysqli_stmt_close($teacherDetailsStmt);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newFirstName = $_POST['new_first_name'];
    $newLastName = $_POST['new_last_name'];

    // Update teacher details in the users table
    $updateTeacherStmt = mysqli_prepare($conn, "UPDATE creds SET fname = ?, lname = ? WHERE email = ?");
    mysqli_stmt_bind_param($updateTeacherStmt, "sss", $newFirstName, $newLastName, $teacherEmail);
    mysqli_stmt_execute($updateTeacherStmt);
    mysqli_stmt_close($updateTeacherStmt);

    // Redirect to the same page to refresh the details
    header("Location: teacher-details.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Details</title>
    <link rel="stylesheet" href="teacherDetails.css"> <!-- Create a new style sheet for the teacher details page -->
    <style>
    /* teacherDetails.css */

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
    text-align: left;
}

label {
    display: block;
    margin-bottom: 8px;
}

input[type="text"] {
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
        <h2>Teacher Details</h2>

        <form method="post">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo $teacherEmail; ?>" disabled>
            
            <label for="new_first_name">First Name:</label>
            <input type="text" id="new_first_name" name="new_first_name" value="<?php echo $teacherFirstName; ?>" required>
            
            <label for="new_last_name">Last Name:</label>
            <input type="text" id="new_last_name" name="new_last_name" value="<?php echo $teacherLastName; ?>" required>

            <input type="submit" value="Save Changes">
        </form>

        <a href="teacher-dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
