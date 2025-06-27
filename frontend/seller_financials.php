<?php
include '../database/db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$seller_id = $_SESSION['user_id'];

// Fetch seller's product IDs
$stmt = $pdo->prepare("SELECT product_id FROM products WHERE seller_id = ?");
$stmt->execute([$seller_id]);
$product_ids = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'product_id');

$financials = [];
$total_earned = 0;
$total_platform_fee = 0;

if (!empty($product_ids)) {
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    $query = "
  SELECT 
    f.transaction_id, f.product_id, p.name AS product_name, 
    f.quantity, f.total_amount, f.payment_status, 
    f.payment_method, f.transaction_date
  FROM Financials f
  JOIN Products p ON f.product_id = p.product_id
  WHERE p.seller_id = ?
  ORDER BY f.transaction_date DESC
";
$stmt = $pdo->prepare($query);
$stmt->execute([$seller_id]);

    $financials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($financials as $f) {
        $total_earned += $f['seller_amount'];
        $total_platform_fee += $f['platform_fee'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Financials - ShopInk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <style>
    body { display: flex; min-height: 100vh; background: #f5f7fa; }
    .sidebar {
      width: 250px;
      background-color: #4c5cc3;
      color: white;
      flex-shrink: 0;
      position: fixed;
      height: 100vh;
      overflow-y: auto;
    }
    .sidebar h4 { padding: 1rem; background: #3a4ab2; margin: 0; }
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
    .summary-card { background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 1rem; }
  </style>
</head>
<body class="bg-light">
  <div class="sidebar">
    <h4>Seller Panel</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="seller_products.php">Products</a>
    <a href="seller_orders.php">Orders</a>
    <a href="seller_financials.php" class="active">Financials</a>
    <a href="logout.php">Logout</a>
    <a href="user_welcome.php">Back to ShopInk</a>
  </div>

  <div class="main-content">
    <h2 class="mb-4">Your Financial Overview</h2>

    <div class="row">
      <div class="col-md-6">
        <div class="summary-card">
          <h5>Total Earned</h5>
          <p class="fs-4 text-success">R<?= number_format($total_earned, 2) ?></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="summary-card">
          <h5>Platform Fees Paid</h5>
          <p class="fs-4 text-danger">R<?= number_format($total_platform_fee, 2) ?></p>
        </div>
      </div>
    </div>

    <?php if (empty($financials)): ?>
      <div class="alert alert-info mt-4">No financial records found.</div>
    <?php else: ?>
      <div class="table-responsive mt-4">
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Seller Amount</th>
              <th>Platform Fee</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($financials as $f): ?>
              <tr>
                <td><?= date('Y-m-d', strtotime($f['created_at'])) ?></td>
                <td><?= htmlspecialchars($f['product_name']) ?></td>
                <td><?= $f['quantity'] ?></td>
                <td>R<?= number_format($f['price'], 2) ?></td>
                <td>R<?= number_format($f['seller_amount'], 2) ?></td>
                <td>R<?= number_format($f['platform_fee'], 2) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </div>
</body>
</html>
