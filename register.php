<?php
session_start();
include 'includes/db.php';

if (isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $allowedRoles = ['user', 'admin'];
    if (!in_array($role, $allowedRoles)) {
        $error = "❌ Invalid role selected.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "❌ Email already exists!";
        } else {
            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                header("Location: login.php?registered=true");
                exit();
            } else {
                $error = "❌ Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center flex-grow-1">
  <div class="card shadow-lg p-4" style="max-width: 450px; width: 100%;">
    <h3 class="text-center text-primary mb-4">📝 Register</h3>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required minlength="5">
      </div>
      <div class="mb-3">
        <select name="role" class="form-select" required>
          <option value="">-- Select Role --</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button type="submit" name="register" class="btn btn-success w-100">Register</button>
    </form>

    <div class="text-center mt-3">
      <small>Already have an account? <a href="login.php">Login</a></small>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
