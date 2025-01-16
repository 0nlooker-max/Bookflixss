<?php
session_start();
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch the user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    // Check if user exists and password matches
    if ($user && $password === $user['password']) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Role could be 'admin' or 'user'

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: adminpage.php");
        } else {
            header("Location: user_page.php"); // Redirect to a user-specific page
        }
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: #f3f3f3;
}

.login-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 450px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: linear-gradient(to right, #ff7a18, #af002d 40%, #319197);
    padding: 30px;
}

.login-left {
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.login-left .logo {
    width: 100px;
    height: 100px;
    margin-bottom: 20px;
    border-radius: 50%;
    object-fit: cover;
}

.login-left h2 {
    font-size: 28px;
    margin-bottom: 10px;
}

.login-left p {
    font-size: 14px;
    margin-bottom: 30px;
}

.login-left input {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: none;
    border-radius: 5px;
    font-size: 14px;
}

.login-left .login-btn {
    width: 100%;
    padding: 12px;
    background-color: #ff7a18;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.login-left .login-btn:hover {
    background-color: #ff5c00;
}

.login-left .forgot-password {
    color: #ccc;
    text-decoration: none;
    font-size: 12px;
    margin-top: 10px;
}

.login-left .create-account {
    font-size: 12px;
    margin-top: 20px;
}

.login-left .create-account a {
    color: #ff7a18;
    text-decoration: none;
}

</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <img src="images/download.jpeg" alt="Bookstore Logo" class="logo">
            <h2>Welcome to Bookflix</h2>
            <p>Please login to your account</p>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="login-btn">Log In</button>
            </form>
            <a href="#" class="forgot-password">Forgot password?</a>
            <p class="create-account">Don't have an account? <a href="register.php">Create new</a></p>
        </div>
    </div>
</body>
</html>
