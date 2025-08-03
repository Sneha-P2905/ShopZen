<?php
session_start();
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="text-center mb-4">🧾 Checkout</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info text-center">Your cart is empty.</div>
    <?php else: ?>
        <form method="POST" action="delivery_info.php">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Price (₹)</th>
                            <th>Quantity</th>
                            <th>Subtotal (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $subtotal = (int)$item['price'] * (int)$item['quantity'];
                            $grand_total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>
                                <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="60" height="60" style="object-fit:cover; border-radius:8px;">
                            </td>
                            <td><?= number_format($item['price']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($subtotal) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                            <td class="fw-bold text-success">₹<?= number_format($grand_total) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-success px-4 py-2">Proceed to Delivery</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
