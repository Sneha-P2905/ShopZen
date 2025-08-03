<?php
session_start();
include 'includes/db.php';

$error = '';

// Handle login submission
if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Check user by email
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role']    = $role;

            // ✅ Load user's cart from DB
            $cart_query = $conn->prepare("SELECT c.product_id, c.quantity, p.name, p.price, p.image 
                                          FROM cart c 
                                          JOIN products p ON c.product_id = p.id 
                                          WHERE c.user_id = ?");
            $cart_query->bind_param("i", $id);
            $cart_query->execute();
            $result = $cart_query->get_result();

            $_SESSION['cart'] = []; // reset cart session

            while ($item = $result->fetch_assoc()) {
                $pid = $item['product_id'];
                $_SESSION['cart'][$pid] = [
                    'id'       => $pid,
                    'name'     => $item['name'],
                    'price'    => $item['price'],
                    'image'    => $item['image'],
                    'quantity' => $item['quantity']
                ];
            }

            $cart_query->close();

            // Redirect to original page or role-based default
            $redirect = $_SESSION['redirect_to'] ?? ($role === 'admin' ? 'admin/dashboard.php' : 'product.php');
            unset($_SESSION['redirect_to']);
            header("Location: $redirect");
            exit();
        } else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ Email not found.";
    }

    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center flex-grow-1 py-5">
  <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
    <h3 class="text-center text-primary mb-4">🔐 Login to ShopZen</h3>

    <?php if (isset($_GET['registered']) && $_GET['registered'] === 'true'): ?>
      <div class="alert alert-success">✅ Registration successful! Please login.</div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>
      <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>

    <div class="text-center mt-3">
      <small>New to ShopZen? <a href="register.php">Create an account</a></small>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
