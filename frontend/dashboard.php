<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../index.php");
    exit;
}

$seller_id = $_SESSION['user_id']; // Dynamically use session ID
$current_month = date('Y-m');

// Monthly revenue
$stmt = $pdo->prepare("SELECT SUM(oi.price * oi.quantity) AS total_revenue 
    FROM Order_Items oi 
    JOIN Orders o ON oi.order_id = o.order_id 
    JOIN Products p ON oi.product_id = p.product_id 
    WHERE p.seller_id = ? 
    AND o.order_status = 'delivered' 
    AND o.order_date LIKE ?");
$stmt->execute([$seller_id, "$current_month%"]);
$monthly_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

// Total products
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_products FROM Products WHERE seller_id = ?");
$stmt->execute([$seller_id]);
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'] ?? 0;

// Low stock
$stmt = $pdo->prepare("SELECT COUNT(*) AS low_stock FROM Products WHERE seller_id = ? AND stock_quantity < 5");
$stmt->execute([$seller_id]);
$low_stock = $stmt->fetch(PDO::FETCH_ASSOC)['low_stock'] ?? 0;

// Unfulfilled orders
$stmt = $pdo->prepare("SELECT COUNT(*) AS unfulfilled 
    FROM Order_Items oi 
    JOIN Products p ON oi.product_id = p.product_id 
    JOIN Orders o ON oi.order_id = o.order_id 
    WHERE p.seller_id = ? 
    AND o.order_status = 'pending'");
$stmt->execute([$seller_id]);
$unfulfilled_orders = $stmt->fetch(PDO::FETCH_ASSOC)['unfulfilled'] ?? 0;

// Monthly earnings history
$earnings_stmt = $pdo->prepare("SELECT DATE_FORMAT(o.order_date, '%Y-%m') AS month, 
    SUM(oi.price * oi.quantity) AS earnings 
    FROM Orders o 
    JOIN Order_Items oi ON o.order_id = oi.order_id 
    JOIN Products p ON oi.product_id = p.product_id 
    WHERE p.seller_id = ? 
    AND o.order_status = 'delivered' 
    GROUP BY month 
    ORDER BY month ASC");
$earnings_stmt->execute([$seller_id]);
$monthly_earnings = $earnings_stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Seller Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    .card-summary {
      background-color: white;
      padding: 1rem;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .card-summary h5 {
      margin: 0;
      color: #4c5cc3;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h4>Seller Panel</h4>
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="seller_products.php">Products</a>
    <a href="seller_orders.php">Orders</a>
    <a href="seller_financials.php">Financials</a>
    <a href="logout.php">Logout</a>
    <a href="user_welcome.php">Back to ShopInk</a>
  </div>

  <div class="main-content">
    <h2 class="mb-4 text-dark">Seller Dashboard Overview</h2>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="card-summary">
          <h5>Total Products</h5>
          <p><?= $total_products ?></p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-summary">
          <h5>Low Stock Items</h5>
          <p><?= $low_stock ?></p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-summary">
          <h5>Unfulfilled Orders</h5>
          <p><?= $unfulfilled_orders ?></p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-summary">
          <h5>June Earnings</h5>
          <p>R<?= number_format($monthly_revenue, 2) ?></p>
        </div>
      </div>
    </div>

    <div class="mt-5">
      <h4 class="mb-3">Earnings Over Time</h4>
      <canvas id="earningsChart" height="100"></canvas>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('earningsChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode(array_column($monthly_earnings, 'month')) ?>,
        datasets: [{
          label: 'Monthly Earnings (R)',
          data: <?= json_encode(array_map('floatval', array_column($monthly_earnings, 'earnings'))) ?>,
          borderColor: '#4c5cc3',
          backgroundColor: 'rgba(76, 92, 195, 0.1)',
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</body>
</html>


