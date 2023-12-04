<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_COOKIE['email']) || !isset($_COOKIE['role'])) {
    header("Location: login.html");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $childFirstName = htmlspecialchars($_POST["childFirstName"]);
    $childLastName = htmlspecialchars($_POST["childLastName"]);
    $childCategory = $_POST["childCategory"];
    $childDOB = $_POST["childDOB"];

    // Validate date format (for example, YYYY-MM-DD)
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $childDOB)) {
        $_SESSION['error'] = "Invalid date format. Please use YYYY-MM-DD.";
        header("Location: parent-add-child.php");
        exit();
    }

    // Get the user's email from the cookie
    $parentEmail = $_COOKIE['email'];

    // Insert child details into the database
    $insertStmt = mysqli_prepare($conn, "INSERT INTO children (parent_email, first_name, last_name, category, date_of_birth, approved) VALUES (?, ?, ?, ?, ?, false)");
    mysqli_stmt_bind_param($insertStmt, "sssss", $parentEmail, $childFirstName, $childLastName, $childCategory, $childDOB);

    if (mysqli_stmt_execute($insertStmt)) {
        $_SESSION['success'] = "Child details submitted for approval";
        header("Location: parent-dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
        header("Location: parent-add-child.php");
        exit();
    }

    // Close the insert statement
    mysqli_stmt_close($insertStmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Child Details</title>
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

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-top: 10px;
    color: #333;
}

input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.submit-button {
    background-color: #4caf50;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.submit-button:hover {
    background-color: #45a049;
}

.error-message, .success-message {
    color: #ff0000;
    margin-top: 10px;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    color: #2196F3;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Add Child Details</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo '<p class="success-message">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        ?>

        <form action="parent-add-child.php" method="post">
            <label for="childFirstName">Child First Name:</label>
            <input type="text" name="childFirstName" required><br>

            <label for="childLastName">Child Last Name:</label>
            <input type="text" name="childLastName" required><br>

            <label for="childCategory">Child Category:</label>
            <select name="childCategory" required>
                <option value="Infant">Infant</option>
                <option value="Toddler">Toddler</option>
                <option value="Twaddler">Twaddler</option>
                <option value="3YearsOld">3 Years Old</option>
                <option value="4YearsOld">4 Years Old</option>
            </select><br>

            <label for="childDOB">Child Date of Birth (YYYY-MM-DD):</label>
            <input type="text" name="childDOB" required><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
