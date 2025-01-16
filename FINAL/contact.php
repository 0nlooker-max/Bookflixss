<?php
session_start();
include "database.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the user details
$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $name = $_SESSION['name']; // Assuming user’s name is stored in session, or you can fetch it from the database

    // Insert the message into the messages table
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, name, message) VALUES (:user_id, :name, :message)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':name' => $name,
        ':message' => $message
    ]);

    // Redirect to contact.php with a success message
    header("Location: contact.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            color: #333;
        }

        .navbar {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-nav {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        .nav-link {
            color: white !important;
            padding: 10px 15px;
            text-decoration: none;
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        .nav-link:hover {
            color: #ffc107 !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        #logout {
            margin-left: auto;
            color: white !important;
            padding: 10px 15px;
            text-decoration: none;
        }

        #logout:hover {
            color: #ffc107 !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .container {
            max-width: 800px;
            margin-top: 40px;
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .alert {
            margin-top: 20px;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: none;
            transition: box-shadow 0.3s ease-in-out;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.6);
        }

        .btn {
            border-radius: 8px;
            padding: 12px 25px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .back-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand ms-3" href="user_dashboard.php">Bookstore</a>
        <div class="navbar-nav mx-auto">
            <a class="nav-link" href="user_page.php">Home</a>
            <a class="nav-link" href="cart.php">Cart</a>
            <a class="nav-link" href="contact.php">Contact Admin</a>
        </div>
        <a class="nav-link" href="logout.php" id="logout">Logout</a>
    </nav>

    <div class="container">
        <h2>Contact Admin</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success" role="alert">
                Your message has been sent successfully!
            </div>
        <?php endif; ?>

        <form method="POST" action="contact.php">
            <div class="mb-3">
                <label for="message" class="form-label">Your Message</label>
                <textarea class="form-control" id="message" name="message" rows="4" required><?php if (isset($_GET['success']) && $_GET['success'] == 1) echo ''; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>

        <a href="user_page.php" class="btn btn-secondary back-btn">Back to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
