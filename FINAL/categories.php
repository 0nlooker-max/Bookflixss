<?php
// categories.php
session_start();
include "database.php";


// Fetch Categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Handle Add Category
if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        header("Location: categories.php");
    }
}

// Handle Edit Category
if (isset($_POST['edit_category'])) {
    $id = $_POST['edit_id'];
    $name = trim($_POST['edit_name']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
        header("Location: categories.php");
    }
}

// Handle Delete Category
if (isset($_POST['delete_category'])) {
    $id = $_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
           /* Navbar styling */
.navbar {
    background-color: #343a40; /* Dark background for the navbar */
    padding: 10px 20px; /* Padding for spacing */
}
.navbar-brand span{
    color: black;
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

<div class="container mt-5">
    <h2 class="fw-bold text-primary">Category List</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="bi bi-plus-circle"></i> Add Category
    </button>

    <!-- Category Table -->
    <table class="table table-bordered table-hover table-striped shadow-sm rounded-3">
        <thead>
            <tr class="table-secondary">
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['id']; ?></td>
                <td><?php echo htmlspecialchars($category['name']); ?></td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-outline-primary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editCategoryModal" 
                            data-id="<?php echo $category['id']; ?>"
                            data-name="<?php echo htmlspecialchars($category['name']); ?>">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>

                    <!-- Delete Button -->
                    <button class="btn btn-outline-danger btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteCategoryModal" 
                            data-id="<?php echo $category['id']; ?>">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control" name="name" id="name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" name="add_category">Add Category</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="mb-3">
            <label for="edit_name" class="form-label">Category Name</label>
            <input type="text" class="form-control" name="edit_name" id="edit_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="edit_category">Update Category</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          Are you sure you want to delete this category?
          <input type="hidden" name="delete_id" id="delete_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger" name="delete_category">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
<script>
    // Populate Edit Modal Fields
    document.querySelectorAll('[data-bs-target="#editCategoryModal"]').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('edit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_name').value = button.getAttribute('data-name');
        });
    });

    // Populate Delete Modal ID
    document.querySelectorAll('[data-bs-target="#deleteCategoryModal"]').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('delete_id').value = button.getAttribute('data-id');
        });
    });
</script>
</body>
</html>
