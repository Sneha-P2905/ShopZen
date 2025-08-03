<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT p.* FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
    <h2 class="text-center mb-4">💖 My Wishlist</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <a href="product_detail.php?id=<?= $row['id'] ?>">
                            <img src="images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 250px; object-fit: cover;">
                        </a>
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                            <p class="card-text fw-bold text-success">₹<?= number_format($row['price']) ?></p>

                            <a href="product_detail.php?id=<?= $row['id'] ?>" class="btn btn-info mb-2">🔍 View</a><br>

                            <!-- ✅ Fixed Add to Cart (uses correct ID) -->
                            <form action="add_to_cart.php" method="GET" class="d-inline">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-success btn-sm">➕ Add to Cart</button>
                            </form>

                            <!-- Remove from wishlist -->
                            <form action="remove_from_wishlist.php" method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">❌ Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="text-center mt-4">
            <a href="product.php" class="btn btn-primary btn-lg">Continue Shopping 🛍️</a>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">Your wishlist is empty.</div>
        <div class="text-center mt-4">
            <a href="product.php" class="btn btn-primary btn-lg">Browse Products 🛍️</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
