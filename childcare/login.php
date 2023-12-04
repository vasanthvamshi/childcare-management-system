<?php
include 'db_connection.php'; // Include the database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    // Prepare and bind the SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT email, password, role FROM creds WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Bind the result variables
    mysqli_stmt_bind_result($stmt, $dbEmail, $dbPassword, $dbRole);

    // Fetch the values
    mysqli_stmt_fetch($stmt);

    // Verify the password
    if ($dbEmail && password_verify($password, $dbPassword)) {
        setcookie("email", $dbEmail, time() + (86400 * 30), "/"); // 30 days expiration
        setcookie("role", $dbRole, time() + (86400 * 30), "/"); // 30 days expiration
        if ($dbRole == "PAR") {
            header("Location: parent-dashboard.php");
        } else if ($dbRole == "TEA") {
            header("Location: teacher-dashboard.php");
        } else if ($dbRole == "FAD") {
            header("Location: facadm-dashboard.php");
        } else {
            echo "another dashboard";
        }
    } else {
        echo "Invalid email or password";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}
?>
