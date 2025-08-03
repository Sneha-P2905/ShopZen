<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// ✅ Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>❌ Invalid product ID.</div></div>";
    include 'includes/footer.php';
    exit();
}

$product_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Product not found
if ($result->num_rows === 0) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>❌ Product not found.</div></div>";
    include 'includes/footer.php';
    exit();
}

$product = $result->fetch_assoc();
$in_cart = isset($_SESSION['cart'][$product_id]) && isset($_SESSION['cart'][$product_id]['quantity']);
$quantity = $in_cart ? $_SESSION['cart'][$product_id]['quantity'] : 0;
?>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-md-6">
            <img src="images/<?= htmlspecialchars($product['image']) ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="col-md-6">
            <h2 class="text-primary"><?= htmlspecialchars($product['name']) ?></h2>
            <h5 class="text-muted mb-3">Price: ₹<?= number_format($product['price']) ?></h5>
            <p class="mb-4"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($in_cart): ?>
                    <form action="update_cart.php" method="POST" class="d-flex align-items-center gap-2 mb-3">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <button type="submit" name="action" value="decrease" class="btn btn-outline-danger">−</button>
                        <input type="text" value="<?= $quantity ?>" readonly class="form-control text-center" style="width: 60px;">
                        <button type="submit" name="action" value="increase" class="btn btn-outline-success">+</button>
                    </form>
                    <p class="text-success">✅ Added to cart</p>
                <?php else: ?>
                    <form action="add_to_cart.php" method="POST" class="d-inline">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-success me-2">➕ Add to Cart</button>
                    </form>
                    <form action="add_to_wishlist.php" method="POST" class="d-inline">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-outline-danger">❤️ Wishlist</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php" class="btn btn-warning">🔐 Login to Purchase</a>
            <?php endif; ?>

            <!-- Continue Shopping Button -->
            <div class="mt-4">
                <a href="product.php" class="btn btn-primary">⬅️ Continue Shopping</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
