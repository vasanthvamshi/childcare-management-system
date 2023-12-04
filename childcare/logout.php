<?php
session_start();

// Clear cookies
setcookie('email', '', time() - 3600, '/');
setcookie('role', '', time() - 3600, '/');

// Redirect to login page
header("Location: login.html");
exit();
?>
