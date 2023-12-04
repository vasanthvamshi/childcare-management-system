<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
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
    width: 300px;
    text-align: center;
}

h2 {
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
}

input, select {
    margin-bottom: 15px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    background-color: #4caf50;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>CMS Registration</h2>
        <form action="register-back.php" method="post">
            <?php
                session_start(); // Start the session

                // Display the error message if it exists
                if (isset($_SESSION['error'])) {
                    echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
                    unset($_SESSION['error']); // Clear the error message
                }
            ?>
            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" required><br>

            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="TEA">Teacher</option>
                <option value="FAD">Faculty Admin</option>
                <option value="PAR">Parent</option>
            </select>
            <br>

            <input type="submit" value="Register">
            <a href="/login.html">Existing User Login Please</a>
        </form>
    </div>
</body>
</html>

