<?php
session_start();
include "database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Fetch books
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM books WHERE stock > 0";

if (!empty($search)) {
    $query .= " AND (title LIKE :search OR author LIKE :search)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':search' => "%$search%"]);
} else {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        /* Center the navigation links */
        .navbar-nav {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        /* Style for individual links */
        .nav-link {
            color: white !important;
            padding: 10px 15px;
            text-decoration: none;
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        /* Hover effect for the links */
        .nav-link:hover {
            color: #ffc107 !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        /* Align the logout link to the right */
        #logout {
            margin-left: auto;
            color: white !important;
            padding: 10px 15px;
            text-decoration: none;
        }

        /* Hover effect for logout */
        #logout:hover {
            color: #ffc107 !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        /* Card Styling */
        .card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .book-card img {
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 1.5rem;
        }

        .book-details-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 150px;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 1rem;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-radius: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .book-card:hover .book-details-overlay {
            display: flex;
            opacity: 1;
        }

        .no-results {
            font-size: 1.25rem;
            color: #888;
            text-align: center;
            margin-top: 2rem;
        }

        /* Search Bar */
        .search-bar input {
            max-width: 400px;
            border-radius: 25px;
        }

        .search-bar button {
            border-radius: 25px;
            margin-left: 10px;
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

    <div class="container mt-5">
        <!-- Search Bar -->
        <form class="d-flex mb-4 search-bar" method="GET">
            <input 
                id="search-input" 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search books by title or author..." 
                value="<?php echo htmlspecialchars($search); ?>"
                autocomplete="off">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div id="book-list" class="row">
            <!-- Display Books -->
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card book-card">
                            <?php if (!empty($book['image'])): ?>
                                <img src="<?php echo htmlspecialchars($book['image']); ?>" class="card-img-top book-image" alt="Book Image">
                            <?php else: ?>
                                <img src="placeholder.jpg" class="card-img-top book-image" alt="Placeholder Image">
                            <?php endif; ?>
                            <div class="book-details-overlay">
                                <h5><?php echo htmlspecialchars($book['title']); ?></h5>
                                <p class="description-text"><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                <p class="card-text">Author: <?php echo htmlspecialchars($book['author']); ?></p>
                                <p class="card-text">Price: ₱<?php echo number_format($book['price'], 2); ?></p> <!-- Changed to PHP peso symbol -->
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>"> 
                                    <div class="mb-3">
                                        <label for="quantity-<?php echo $book['book_id']; ?>" class="form-label">Quantity</label>
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            name="quantity" 
                                            id="quantity-<?php echo $book['book_id']; ?>" 
                                            min="1" 
                                            value="1">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    No books found. Try adjusting your search.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
