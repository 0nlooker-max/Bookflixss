<?php
include "database.php";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate input fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required!";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Validate password confirmation
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    try {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->rowCount() > 0) {
            echo "Username or Email already taken!";
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        echo "Registration successful!";
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
        exit;
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

.register-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 450px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: linear-gradient(to right, #ff7a18, #af002d 40%, #319197);
    padding: 30px;
}

.register-left {
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.register-left .logo {
    width: 100px;
    height: 100px;
    margin-bottom: 20px;
    border-radius: 50%;
    object-fit: cover;
}

.register-left h2 {
    font-size: 28px;
    margin-bottom: 10px;
}

.register-left p {
    font-size: 14px;
    margin-bottom: 30px;
}

.register-left input {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: none;
    border-radius: 5px;
    font-size: 14px;
}

.register-left .register-btn {
    width: 100%;
    padding: 12px;
    background-color: #ff7a18;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.register-left .register-btn:hover {
    background-color: #ff5c00;
}

.register-left .login-redirect {
    color: #ccc;
    text-decoration: none;
    font-size: 12px;
    margin-top: 10px;
}

.register-left .login-redirect a {
    color: #ff7a18;
    text-decoration: none;
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <img src="images/download.jpeg" alt="Bookstore Logo" class="logo">
            <h2>Create Your Account</h2>
            <p>Please fill in the details to register</p>
            <form action="register.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" class="register-btn">Register</button>
            </form>
            <p class="login-redirect">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
