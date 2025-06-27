<?php
session_start();
include '../database/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM Users WHERE email_address = ? AND role = 'admin'");
  $stmt->execute([$email]);
  $admin = $stmt->fetch();

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['user_id'];
    $_SESSION['admin_email'] = $admin['email_address'];
    header("Location: admin.php");
    exit;
  } else {
    $error = "Invalid admin credentials.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | ShopInk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0f2f5;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-container {
      max-width: 450px;
      margin: 80px auto;
      padding: 2rem;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .login-header {
      text-align: center;
      margin-bottom: 2rem;
      color: #4c5cc3;
    }
    .btn-primary {
      background-color: #4c5cc3;
      border: none;
    }
    .btn-primary:hover {
      background-color: #3a4ab2;
    }
    .form-label {
      font-weight: 600;
      color: #333;
    }
    .error-message {
      color: red;
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h2 class="login-header">ShopInk Admin Login</h2>

  <?php if ($error): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">Admin Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
</div>

</body>
</html>
