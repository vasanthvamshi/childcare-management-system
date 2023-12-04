<?php
// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "childcare";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
