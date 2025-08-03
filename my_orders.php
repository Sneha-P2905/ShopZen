<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">📦 My Orders</h2>
        <a href="product.php" class="btn btn-outline-secondary">🔙 Back to Home</a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="row g-4">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img src="images/<?= htmlspecialchars($row['product_image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['product_name']) ?></h5>
                            <p class="mb-1"><strong>Quantity:</strong> <?= $row['quantity'] ?></p>
                            <p class="mb-1"><strong>Total:</strong> ₹<?= number_format($row['total_amount']) ?></p>
                            <p class="mb-1"><strong>Payment:</strong> <?= ucfirst($row['payment_method']) ?></p>
                            <p class="mb-0 text-muted"><small><strong>Ordered on:</strong> <?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></small></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">You have not placed any orders yet.</div>
        <div class="text-center">
            <a href="product.php" class="btn btn-outline-primary mt-3">Browse Products</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
