<?php
// books.php
session_start();
include "database.php";

// Fetch Books and Categories
$books = $pdo->query("SELECT b.*, c.name AS category_name FROM books b JOIN categories c ON b.category_id = c.id")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Handle Add Book
if (isset($_POST['add_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $description = trim($_POST['description']);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $image_path = '';

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = 'uploads/' . $image_name;
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    if ($title && $author && $price !== false && $stock !== false && $category_id) {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, description, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $author, $description, $price, $stock, $category_id, $image_path]);
        $_SESSION['success'] = "Book added successfully!";
        header("Location: books.php");
        exit;
    }
}

// Handle Edit Book
if (isset($_POST['edit_book'])) {
    $id = filter_var($_POST['edit_id'], FILTER_VALIDATE_INT);
    $title = trim($_POST['edit_title']);
    $author = trim($_POST['edit_author']);
    $description = trim($_POST['edit_description']);
    $price = filter_var($_POST['edit_price'], FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['edit_stock'], FILTER_VALIDATE_INT);
    $category_id = filter_var($_POST['edit_category_id'], FILTER_VALIDATE_INT);
    $image_path = '';

    if (!empty($_FILES['edit_image']['name']) && $_FILES['edit_image']['error'] == 0) {
        $image_name = time() . '_' . basename($_FILES['edit_image']['name']);
        $image_path = 'uploads/' . $image_name;
        move_uploaded_file($_FILES['edit_image']['tmp_name'], $image_path);

        $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, description = ?, price = ?, stock = ?, category_id = ?, image = ? WHERE book_id = ?");
        $stmt->execute([$title, $author, $description, $price, $stock, $category_id, $image_path, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, description = ?, price = ?, stock = ?, category_id = ? WHERE book_id = ?");
        $stmt->execute([$title, $author, $description, $price, $stock, $category_id, $id]);
    }

    $_SESSION['success'] = "Book updated successfully!";
    header("Location: books.php");
    exit;
}

// Handle Delete Book
if (isset($_POST['delete_book'])) {
    $id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "Book deleted successfully!";
    header("Location: books.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
        }
        img.book-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        table th:nth-child(4), table td:nth-child(4) {
    width: 400px;  /* Set the desired width */
    word-wrap: break-word; /* Ensure long text is wrapped within the cell */
    overflow-wrap: break-word; /* Another property to ensure word wrapping */
    white-space: normal; /* Allow text to wrap onto new lines */
    word-break: break-all; /* Break long words if necessary */
}
table th:nth-child(2), table td:nth-child(2) {
    width: 200px;  /* Set the desired width */
    word-wrap: break-word; /* Ensure long text is wrapped within the cell */
    overflow-wrap: break-word; /* Another property to ensure word wrapping */
    white-space: normal; /* Allow text to wrap onto new lines */
    word-break: break-all; /* Break long words if necessary */
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
    <h2>Book List</h2>
    <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addBookModal">Add Book</button>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?= $book['book_id'] ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['description']) ?></td>
                <td>₱<?= number_format($book['price'], 2) ?></td>
                <td><?= $book['stock'] ?></td>
                <td><?= htmlspecialchars($book['category_name']) ?></td>
                <td>
                    <?php if (!empty($book['image'])): ?>
                        <img src="<?= htmlspecialchars($book['image']) ?>" alt="Book Image" class="book-img">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-primary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editBookModal"
                            data-id="<?= $book['book_id'] ?>"
                            data-title="<?= htmlspecialchars($book['title']) ?>"
                            data-author="<?= htmlspecialchars($book['author']) ?>"
                            data-price="<?= $book['price'] ?>"
                            data-stock="<?= $book['stock'] ?>"
                            data-category-id="<?= $book['category_id'] ?>"
                            data-description="<?= htmlspecialchars($book['description']) ?>"
                            data-image="<?= $book['image'] ?>">
                        Edit
                    </button>

                    <!-- Delete Button -->
                    <button class="btn btn-danger btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteBookModal" 
                            data-id="<?= $book['book_id'] ?>">
                        Delete
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="books.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookModalLabel">Add Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                    <!-- Description field in both forms -->
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" rows="3" style="width: 100%;"></textarea>
</div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="add_book">Add Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="books.php">
                <input type="hidden" id="edit_id" name="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="edit_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="edit_author" name="edit_author" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="edit_description" rows="3" style="width: 100%;"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="edit_price" name="edit_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="edit_stock" name="edit_stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Category</label>
                        <select class="form-control" id="edit_category_id" name="edit_category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="edit_image" name="edit_image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="edit_book">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-bs-target="#editBookModal"]').forEach(button => {
    button.addEventListener('click', () => {
        // Set the values in the modal based on the button attributes
        document.getElementById('edit_id').value = button.getAttribute('data-id');
        document.getElementById('edit_title').value = button.getAttribute('data-title');
        document.getElementById('edit_author').value = button.getAttribute('data-author');
        document.getElementById('edit_description').value = button.getAttribute('data-description');
        document.getElementById('edit_price').value = button.getAttribute('data-price');
        document.getElementById('edit_stock').value = button.getAttribute('data-stock');
        document.getElementById('edit_category_id').value = button.getAttribute('data-category-id');
    });
});
</script>


<!-- Delete Book Modal -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="books.php">
                <input type="hidden" id="delete_id" name="delete_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteBookModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this book?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" name="delete_book">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include your modal HTML here -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('[data-bs-target="#editBookModal"]').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('edit_id').value = button.getAttribute('data-id');
        document.getElementById('edit_title').value = button.getAttribute('data-title');
        document.getElementById('edit_author').value = button.getAttribute('data-author');
        document.getElementById('edit_price').value = button.getAttribute('data-price');
        document.getElementById('edit_stock').value = button.getAttribute('data-stock');
        document.getElementById('edit_category_id').value = button.getAttribute('data-category-id');
        document.getElementById('edit_description').value = button.getAttribute('data-description');
        document.getElementById('edit_image').value = button.getAttribute('data-image');
    });
});
document.querySelectorAll('[data-bs-target="#deleteBookModal"]').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('delete_id').value = button.getAttribute('data-id');
    });
});
</script>
</body>
</html>
