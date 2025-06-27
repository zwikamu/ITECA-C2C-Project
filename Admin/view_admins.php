<?php
include '../database/db.php';

// Handle add admin form
$add_admin_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
  $email = trim($_POST['email_address']);
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  if ($email && $first_name && $last_name && $password) {
    $stmt = $pdo->prepare("INSERT INTO Admins (email_address, first_name, last_name, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $first_name, $last_name, $password]);
    header("Location: view_admins.php");
    exit;
  } else {
    $add_admin_error = "All fields are required.";
  }
}

// Fetch all admins
$stmt = $pdo->query("SELECT * FROM Admins ORDER BY created_at DESC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Admins</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      background: #ffffff;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
    }
    .sidebar {
      width: 250px;
      background-color: #4c5cc3;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      padding: 2rem 1rem;
      color: white;
    }
    .sidebar h4 {
      margin: -1rem -1rem 1rem -1rem;
      padding: 1rem;
      background: #3a4ab2;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 0.75rem 1.25rem;
    }
    .sidebar a:hover,
    .sidebar .active {
      background-color: rgb(111, 122, 196);
    }
    .main-wrapper {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 2rem;
    }
    .form-section {
      background: #f8f9fa;
      border: 1px solid #ddd;
      padding: 1.5rem;
      border-radius: 8px;
      margin-bottom: 2rem;
    }
    .table-head {
      background-color: rgba(58, 74, 178, 0.79);
      color: white;
    }
    .btn-primary{
      background-color:rgb(141, 140, 140);
      border: none;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h4>ShopInk Admin</h4>
    <a href="admin.php">Dashboard</a>
    <a href="view_products.php">Products</a>
    <a href="view_users.php">Users</a>
    <a href="view_orders.php">Orders</a>
    <a href="view_applications.php">Seller Applications</a>
    <a href="view_admins.php" class="active">Admins</a>
    <a href="view_financials.php">Financials</a>
    <a href="reports.php">Reports</a>
    <a href="admin_logout.php">Logout</a>
  </div>

  <div class="main-wrapper">
    <h2 class="mb-4 text-dark">Admin Management</h2>

    <div class="form-section">
      <h5 class="mb-3">Add New Admin</h5>
      <?php if ($add_admin_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($add_admin_error) ?></div>
      <?php endif; ?>
      <form method="POST" class="row g-3">
        <div class="col-md-4">
          <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
        </div>
        <div class="col-md-4">
          <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
        </div>
        <div class="col-md-4">
          <input type="email" name="email_address" class="form-control" placeholder="Email Address" required>
        </div>
        <div class="col-md-4">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="col-md-4">
          <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
        </div>
      </form>
    </div>

<table class="table table-bordered table-striped">
  <thead class="table-head">
    <tr>
      <th>Admin ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Created At</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($admins as $admin): ?>
      <tr>
        <td><?= htmlspecialchars($admin['admin_id']) ?></td>
        <td><?= htmlspecialchars($admin['username']) ?></td>
        <td><?= htmlspecialchars($admin['email']) ?></td>
        <td class="editable-role" data-admin-id="<?= $admin['admin_id'] ?>">
          <span class="role-label"><?= htmlspecialchars($admin['role']) ?></span>
          <select class="form-select d-none role-select">
            <option value="full_admin" <?= $admin['role'] === 'full_admin' ? 'selected' : '' ?>>full_admin</option>
            <option value="moderator" <?= $admin['role'] === 'moderator' ? 'selected' : '' ?>>moderator</option>
          </select>
        </td>
        <td><?= htmlspecialchars($admin['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  </div>

</body>
</html>
