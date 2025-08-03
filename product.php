<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';
?>

<div class="container my-5">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <h2 class="text-center mb-4">🛍️ All Products</h2>

    <div class="row">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $product_id = $row['id'];
                $in_cart = isset($_SESSION['cart'][$product_id]);
                $quantity = $in_cart ? (int)$_SESSION['cart'][$product_id]['quantity'] : 0;

                // Wishlist check
                $in_wishlist = false;
                if (isset($_SESSION['user_id'])) {
                    $uid = $_SESSION['user_id'];
                    $wish_stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
                    $wish_stmt->bind_param("ii", $uid, $product_id);
                    $wish_stmt->execute();
                    $wish_stmt->store_result();
                    $in_wishlist = $wish_stmt->num_rows > 0;
                    $wish_stmt->close();
                }
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <a href="product_detail.php?id=<?= $product_id ?>">
                    <img src="images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 250px; object-fit: cover;">
                </a>

                <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                    <p class="card-text fw-bold text-primary">₹<?= number_format($row['price']) ?></p>

                    <a href="product_detail.php?id=<?= $product_id ?>" class="btn btn-info btn-sm mb-2">🔍 View</a><br>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($in_cart): ?>
                            <form action="update_cart.php" method="POST" class="d-flex justify-content-center align-items-center mb-2">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <button type="submit" name="action" value="decrease" class="btn btn-outline-danger btn-sm me-2">−</button>
                                <input type="text" class="form-control text-center" value="<?= $quantity ?>" style="width: 50px;" readonly>
                                <button type="submit" name="action" value="increase" class="btn btn-outline-success btn-sm ms-2">+</button>
                            </form>
                        <?php else: ?>
                            <form action="add_to_cart.php" method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <button type="submit" class="btn btn-success btn-sm mb-2">➕ Add to Cart</button>
                            </form><br>
                        <?php endif; ?>

                        <!-- Wishlist -->
                        <?php if ($in_wishlist): ?>
                            <form action="remove_from_wishlist.php" method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <button type="submit" class="btn btn-danger btn-sm">❤️ Wishlisted</button>
                            </form>
                        <?php else: ?>
                            <form action="add_to_wishlist.php" method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">🤍 Add to Wishlist</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-warning btn-sm">🔐 Login to Add</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
            endwhile;
        else:
            echo '<div class="col-12"><div class="alert alert-info text-center">No products found.</div></div>';
        endif;
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
