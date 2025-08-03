<?php
session_start();

// Only allow access if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
include '../includes/header_admin.php';

// Fetch counts
$product_count = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$user_count = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$order_count = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
?>

<div class="container py-5">
    <h2 class="text-center text-primary mb-4">👨‍💼 Admin Dashboard</h2>

    <!-- Summary Cards -->
    <div class="row mb-5">
        <div class="col-md-4">
            <a href="product_list.php" class="text-decoration-none">
                <div class="card text-white bg-primary mb-3 shadow rounded-4 text-center">
                    <div class="card-body">
                        <h5 class="card-title">📦 Total Products</h5>
                        <p class="display-6 fw-bold"><?= $product_count ?></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="user_list.php" class="text-decoration-none">
                <div class="card text-white bg-success mb-3 shadow rounded-4 text-center">
                    <div class="card-body">
                        <h5 class="card-title">👥 Registered Users</h5>
                        <p class="display-6 fw-bold"><?= $user_count ?></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="order_list.php" class="text-decoration-none">
                <div class="card text-white bg-warning mb-3 shadow rounded-4 text-center">
                    <div class="card-body">
                        <h5 class="card-title">🛒 Orders Placed</h5>
                        <p class="display-6 fw-bold"><?= $order_count ?></p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Admin Actions -->
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-light">
                <h4 class="mb-3 text-center text-success">⚙️ Quick Admin Actions</h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ➕ Add New Product
                        <a href="add_product.php" class="btn btn-sm btn-outline-primary">Go</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        🚪 Logout
                        <a href="../logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
