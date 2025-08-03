<?php
session_start();
include '../includes/db.php';
include '../includes/header_admin.php';

// Optional: access control check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<div class="container my-5">
    <h2 class="text-center text-primary mb-4">📦 All Products</h2>

    <div class="mb-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Back to Dashboard</a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 30%;">Name</th>
                    <th style="width: 20%;">Price (₹)</th>
                    <th style="width: 40%;">Image</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= number_format($row['price']) ?></td>
                        <td>
                            <div style="width: 100px; height: 100px; overflow: hidden; margin: auto;">
                                <img src="../images/<?= $row['image'] ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No products found.</div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
