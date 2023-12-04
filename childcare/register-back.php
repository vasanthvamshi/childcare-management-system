<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Validate and sanitize the data (you might want to add more validation)
    // $firstName = htmlspecialchars($firstName);
    // $lastName = htmlspecialchars($lastName);
    // $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    // You should hash the password before storing it in the database for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // echo $firstName;
    // Database connection (replace with your database details)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "childcare";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        echo "Failed";
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO creds (fname, lname, email, password, role) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $email, $hashedPassword, $role);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {

        header("Location: login.html");
        // echo "Registration successful";
    } else {
        
        echo "Error: " . mysqli_error($conn);
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    echo "GET Req not work";
}
?>
