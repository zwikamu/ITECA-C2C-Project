<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../database/db.php';

// Require seller login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../login.php");
    exit;
}

$seller_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT product_id, name FROM products WHERE seller_id = ?");
$stmt->execute([$seller_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$product_ids = array_column($products, 'product_id');

$orders = [];
if (!empty($product_ids)) {
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    $query = "
        SELECT o.order_id, o.user_id, u.first_name, u.last_name, u.email_address, o.order_date, o.order_status, o.total_cost, o.shipping_address,
               oi.product_id, oi.quantity, oi.price
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN users u ON o.user_id = u.user_id
        WHERE oi.product_id IN ($placeholders)
        ORDER BY o.order_date DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute($product_ids);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$status_counts = ['pending' => 0, 'shipped' => 0, 'delivered' => 0, 'cancelled' => 0];
foreach ($orders as $order) {
    $status = $order['order_status'];
    if (isset($status_counts[$status])) $status_counts[$status]++;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Orders - ShopInk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <style>
    body {
      display: flex;
      min-height: 100vh;
      background: #f5f7fa;
    }
    .sidebar {
      width: 250px;
      background-color: #4c5cc3;
      color: white;
      flex-shrink: 0;
      position: fixed;
      height: 100vh;
      overflow-y: auto;
    }
    .sidebar h4 {
      padding: 1rem;
      background: #3a4ab2;
      margin: 0;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 0.75rem 1.25rem;
    }
    .sidebar a:hover,
    .sidebar .active {
      background-color: #3a4ab2;
    }
    .main-content {
      margin-left: 250px;
      flex-grow: 1;
      padding: 2rem;
    }
    .order-card {
      cursor: pointer;
    }
    thead {
      background-color: #3a4ab2;
      color: white;
    }
  </style>
</head>
<body class="bg-light">
    <div class="sidebar">
    <h4>Seller Panel</h4>
    <a href="dashboard.php" >Dashboard</a>
    <a href="seller_products.php">Products</a>
    <a href="seller_orders.php" class="active">Orders</a>
    <a href="view_financials.php">Financials</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main-content">
    <h2 class="mb-4">Your Orders</h2>

    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card text-white bg-secondary mb-3">
          <div class="card-body">
            <h5 class="card-title">Pending</h5>
            <p class="card-text fs-4"><?= $status_counts['pending'] ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
          <div class="card-body">
            <h5 class="card-title">Shipped</h5>
            <p class="card-text fs-4"><?= $status_counts['shipped'] ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
          <div class="card-body">
            <h5 class="card-title">Delivered</h5>
            <p class="card-text fs-4"><?= $status_counts['delivered'] ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
          <div class="card-body">
            <h5 class="card-title">Cancelled</h5>
            <p class="card-text fs-4"><?= $status_counts['cancelled'] ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label for="statusFilter" class="form-label">Filter by Status:</label>
      <select id="statusFilter" class="form-select" onchange="filterTable()">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <?php if (empty($orders)): ?>
      <div class="alert alert-info">You have no orders yet.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="ordersTable">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Total Cost</th>
              <th>Status</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $seenOrders = [];
          foreach ($orders as $order): 
            if (in_array($order['order_id'], $seenOrders)) continue;
            $seenOrders[] = $order['order_id'];
          ?>
            <tr data-status="<?= $order['order_status'] ?>">
              <td><?= $order['order_id'] ?></td>
              <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
              <td>R<?= number_format($order['total_cost'], 2) ?></td>
              <td><?= $order['order_status'] ?></td>
              <td><?= date('Y-m-d H:i', strtotime($order['order_date'])) ?></td>
              <td><a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-primary">View</a></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

<script>
  function filterTable() {
    const filter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#ordersTable tbody tr');
    rows.forEach(row => {
      const status = row.getAttribute('data-status');
      row.style.display = (filter === '' || status === filter) ? '' : 'none';
    });
  }
</script>
</body>
</html>
