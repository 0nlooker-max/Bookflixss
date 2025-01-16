<?php
// messages.php
session_start();
include "database.php";

// Fetch messages with the user names using JOIN
$messages = $pdo->query("
    SELECT messages.*, users.username 
    FROM messages 
    JOIN users ON messages.user_id = users.user_id 
    ORDER BY messages.sent_date DESC
")->fetchAll();

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $message_id = $_GET['delete_id']; // Get the message_id from the URL
    // Prepare the delete query for the specific message
    $stmt = $pdo->prepare("DELETE FROM messages WHERE message_id = ?");
    $stmt->execute([$message_id]);
    // Redirect to the messages page after deletion
    header("Location: messages.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        /* Navbar styling */
        .navbar {
            background-color: #343a40; /* Dark background for the navbar */
            padding: 10px 20px; /* Padding for spacing */
        }

        /* Navbar brand styling */
        .navbar-brand {
            color: #fff; /* White text color */
            font-size: 1.5rem; /* Font size for the brand */
            text-decoration: none; /* Remove underline */
        }

        /* Center navbar links */
        .navbar-nav {
            display: flex;
            justify-content: center; /* Center the links */
            flex-grow: 1; /* Allow it to take up available space */
        }

        /* Styling for nav links */
        .navbar-nav .nav-link {
            color: #fff; /* White text color for links */
            margin: 0 15px; /* Space between the links */
            text-decoration: none; /* Remove underline */
            font-size: 1rem; /* Set a font size for the links */
        }

        /* Add hover effect to links */
        .navbar-nav .nav-link:hover {
            color: #007bff; /* Change to blue on hover */
        }

        /* Style for Logout link */
        .logout {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            margin-left: 20px; /* Add some margin for spacing */
        }

        .logout:hover {
            color: blue; /* Red color for hover effect on logout */
        }
        .navbar-brand span{
            color: black;
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
    <a class="navbar-brand ms-3" href="dashboard.php">Book<span>Flix</span> Admin</a>
    <div class="collapse navbar-collapse">
        <div class="navbar-nav mx-auto"> <!-- Added mx-auto to center the nav items -->
            <a href="adminpage.php" class="nav-link">Dashboard</a>
            <a href="categories.php" class="nav-link">Categories</a>
            <a href="books.php" class="nav-link">Books</a>
            <a href="sales.php" class="nav-link">Sales</a>
            <a href="messages.php" class="nav-link">Messages</a>
        </div>
        <a href="login.php" class="nav-link logout">Logout</a>
    </div>
</nav>  

<div class="container mt-4">
    <h2>User Messages</h2>

    <!-- Messages Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Message</th>
                <th>Sent Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($messages) > 0): ?>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($message['username']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
                        <td><?php echo $message['sent_date']; ?></td>
                        <td>
                            <a href="messages.php?delete_id=<?php echo $message['message_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this message?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No messages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
