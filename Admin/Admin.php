<?php
include '../database/db.php';

// Count users
$user_query = $pdo->query("SELECT COUNT(*) AS total_users FROM Users");
$total_users = $user_query->fetch(PDO::FETCH_ASSOC)['total_users'];

// Count products
$product_query = $pdo->query("SELECT COUNT(*) AS total_products FROM Products");
$total_products = $product_query->fetch(PDO::FETCH_ASSOC)['total_products'];

// Count orders this month
$current_month = date('Y-m');
$order_query = $pdo->prepare("SELECT COUNT(*) AS monthly_orders FROM Orders WHERE order_date LIKE :month");
$order_query->execute(['month' => "$current_month%"]);
$monthly_orders = $order_query->fetch(PDO::FETCH_ASSOC)['monthly_orders'];

// Revenue per month (last 6 months)
$revenue_query = $pdo->query("
    SELECT DATE_FORMAT(order_date, '%Y-%m') AS month, SUM(total_cost) AS revenue
    FROM Orders
    WHERE order_status = 'delivered'
    GROUP BY month
    ORDER BY month DESC
    LIMIT 6
");
$monthly_data = array_reverse($revenue_query->fetchAll(PDO::FETCH_ASSOC));

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styling/styles/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .sidebar {
      width: 250px;
      background-color: #4c5cc3;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      position: fixed;
      top: 0; left: 0; bottom: 0;
      padding: 2rem 1rem;
      color: white;
      z-index: 1000;
    }
    .sidebar h4 {
      padding: 1rem;
      background: #3a4ab2;
      margin: -1rem -1rem 1rem -1rem;
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
      background-color: #f8f9fa;
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

    .chart-container {
      margin-top: 40px;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    body{
     background-color:  #f5f7fa;
    }
  </style>
</head>
<body>

<div class="sidebar">
    <h4>ShopInk Admin</h4>
    <a href="admin.php" class="active">Dashboard</a>
    <a href="view_products.php">Products</a>
    <a href="view_users.php">Users</a>
    <a href="view_orders.php">Orders</a>
    <a href="view_applications.php">Seller Applications</a>
    <a href="view_admins.php"">Admins</a>
    <a href="view_financials.php">Financials</a>
    <a href="reports.php">Reports</a>
    <a href="admin_logout.php">Logout</a>
  </div>

<div class="main-wrapper">
  <h2 class="mb-4 text-dark">Dashboard Overview</h2>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card-summary">
        <h5 class="text-dark">Total Users</h5>
        <p><?= $total_users ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-summary">
        <h5 class="text-dark">Total Products</h5>
        <p><?= $total_products ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-summary">
        <h5 class="text-dark">Orders This Month</h5>
        <p><?= $monthly_orders ?></p>
      </div>
    </div>
  </div>

  <div class="chart-container mt-5">
    <h5 class="text-dark">Revenue Trend</h5>
    <canvas id="revenueChart" height="100"></canvas>
  </div>
</div>

<script>
  const revenueCtx = document.getElementById('revenueChart').getContext('2d');
  new Chart(revenueCtx, {
    type: 'line',
    data: {
      labels: <?= json_encode(array_column($monthly_data, 'month')) ?>,
      datasets: [{
        label: 'Monthly Revenue (R)',
        data: <?= json_encode(array_map('floatval', array_column($monthly_data, 'revenue'))) ?>,
        borderColor: '#4caf50',
        backgroundColor: 'rgba(76, 175, 80, 0.2)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#4caf50',
        pointRadius: 4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: { display: false }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

