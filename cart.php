<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="text-center mb-4">🛒 Your Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info text-center">Your cart is empty.</div>
        <div class="text-center mt-3">
            <a href="product.php" class="btn btn-outline-primary">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price (₹)</th>
                        <th>Quantity</th>
                        <th>Total (₹)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $product_id => $item):
                        $subtotal = (int)$item['price'] * (int)$item['quantity'];
                        $grand_total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="70" height="70" style="object-fit: cover; border-radius: 8px;">
                        </td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price']) ?></td>
                        <td>
                            <form action="update_cart.php" method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">−</button>
                            </form>
                            <span class="mx-2 fw-bold"><?= $item['quantity'] ?></span>
                            <form action="update_cart.php" method="POST" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">+</button>
                            </form>
                        </td>
                        <td><?= number_format($subtotal) ?></td>
                        <td>
                            <a href="remove_from_cart.php?product_id=<?= $product_id ?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                        <td class="fw-bold text-success">₹<?= number_format($grand_total) ?></td>
                        <td>
                            <a href="checkout.php" class="btn btn-primary btn-sm">Checkout</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <a href="product.php" class="btn btn-outline-primary">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
