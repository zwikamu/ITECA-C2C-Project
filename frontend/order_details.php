<?php
include '../database/db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    die("Order ID is required.");
}

// Fetch order details
$stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name, u.email_address 
                       FROM orders o 
                       JOIN users u ON o.user_id = u.user_id 
                       WHERE o.order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}

// Fetch ordered products
$stmt = $pdo->prepare("SELECT oi.*, p.name 
                       FROM order_items oi 
                       JOIN products p ON oi.product_id = p.product_id 
                       WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['order_status'] ?? $order['order_status'];
    $update = $pdo->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $update->execute([$new_status, $order_id]);
    header("Location: order_details.php?order_id=" . $order_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details - ShopInk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-3">Order #<?= htmlspecialchars($order_id) ?> Details</h2>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Customer Info</h5>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email_address']) ?></p>
        <p><strong>Shipping Address:</strong> <?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Order Summary</h5>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i', strtotime($order['order_date'])) ?></p>
        <p><strong>Total Cost:</strong> R<?= number_format($order['total_cost'], 2) ?></p>
        <form method="POST" class="row g-2">
          <div class="col-md-4">
            <label for="order_status" class="form-label">Order Status</label>
            <select name="order_status" id="order_status" class="form-select">
              <?php
              $statuses = ['pending', 'shipped', 'delivered', 'cancelled'];
              foreach ($statuses as $status) {
                  $selected = $order['order_status'] === $status ? 'selected' : '';
                  echo "<option value='$status' $selected>" . ucfirst($status) . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Update Status</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Ordered Products</h5>
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Price (R)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <a href="seller_orders.php" class="btn btn-secondary mt-3">Back to Orders</a>
  </div>
</body>
</html>
