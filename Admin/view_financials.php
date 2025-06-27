<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../database/db.php';

$filter_month = $_GET['month'] ?? date('Y-m');

$monthly_revenue = $pdo->prepare("SELECT SUM(total_cost) AS total FROM Orders WHERE order_status IN ('delivered', 'shipped') AND order_date LIKE :month");
$monthly_revenue->execute(['month' => "$filter_month%"]);
$monthly_revenue = $monthly_revenue->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$platform_fees_collected = $pdo->prepare("SELECT SUM(calculated_fee) AS total FROM platform_fees WHERE fee_status = 'charged' AND fee_date LIKE :month");
$platform_fees_collected->execute(['month' => "$filter_month%"]);
$platform_fees_collected = $platform_fees_collected->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$pending_fees = $pdo->prepare("SELECT SUM(calculated_fee) AS total FROM platform_fees WHERE fee_status = 'pending' AND fee_date LIKE :month");
$pending_fees->execute(['month' => "$filter_month%"]);
$pending_fees = $pending_fees->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$cancelled_value = $pdo->prepare("SELECT SUM(total_cost) AS total FROM Orders WHERE order_status = 'cancelled' AND order_date LIKE :month");
$cancelled_value->execute(['month' => "$filter_month%"]);
$cancelled_value = $cancelled_value->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;


$monthly_data = [];
for ($i = 5; $i >= 0; $i--) {
  $month = date('Y-m', strtotime("-$i months"));
  $stmt = $pdo->prepare("SELECT SUM(total_cost) AS revenue FROM Orders WHERE order_status IN ('delivered', 'shipped') AND order_date LIKE :month");
  $stmt->execute(['month' => "$month%"]);
  $revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;
  $monthly_data[] = [
    'month' => date('M Y', strtotime("$month-01")),
    'revenue' => $revenue
  ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Financial Dashboard - ShopInk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background:rgb(255, 255, 255);
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
       width: 250px;
      background-color: #4c5cc3;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      flex-shrink: 0;
      position: fixed;
      top: 0; left: 0;
      bottom: 0;
      padding: 2rem 1rem;
      color: white;
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
    .sidebar .active,
    .submenu .active {
      background-color:rgb(111, 122, 196);
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .main-content {
      margin-left: 230px;
      padding: 2rem;
    }
    .summary-card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      padding: 1.2rem;
      margin-bottom: 1.5rem;
    }
    .summary-card h5 {
      color: #777;
      font-size: 14px;
    }
    .summary-card p {
      font-size: 1.4rem;
      font-weight: bold;
    }
    .chart-container {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    h3{
      color: rgb(54, 54, 54);
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
    <a href="view_admins.php">Admins</a>
    <a href="view_financials.php" class="active">Financials</a>
    <a href="reports.php">Reports</a>
    <a href="admin_logout.php">Logout</a>
  </div>

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-1 ">
  </br></br></br></br>
    <h3>Financial Overview</h3>
    <form method="get" class="d-flex align-items-center gap-2">
      <label for="month">Month:</label>
      <input type="month" name="month" id="month" class="form-control" value="<?= htmlspecialchars($filter_month) ?>">
      <button class="btn btn-primary">Apply</button>
    </form>
  </div>

  <div class="row mb-4">
    <div class="col-md-3">
      <div class="summary-card text-success">
        <h5>Monthly Revenue</h5>
        <p>R<?= number_format($monthly_revenue, 2) ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-card text-info">
        <h5>Platform Fees Collected</h5>
        <p>R<?= number_format($platform_fees_collected, 2) ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-card text-warning">
        <h5>Pending Fees</h5>
        <p>R<?= number_format($pending_fees, 2) ?></p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="summary-card text-danger">
        <h5>Cancelled Orders Loss</h5>
        <p>R<?= number_format($cancelled_value, 2) ?></p>
      </div>
    </div>
  </div>

  <div class="chart-container mb-4">
    <h5 class="mb-3">Revenue Breakdown - <?= date('F Y', strtotime("$filter_month-01")) ?></h5>
    <canvas id="financialChart" height="100"></canvas>
  </div>

  <div class="chart-container">
    <h5 class="mb-3">Revenue Trend (Last 6 Months)</h5>
    <canvas id="revenueChart" height="100"></canvas>
  </div>
</div>

<script>
  const financialCtx = document.getElementById('financialChart').getContext('2d');
  new Chart(financialCtx, {
    type: 'bar',
    data: {
      labels: ['Monthly Revenue', 'Fees Collected', 'Pending Fees', 'Cancelled Orders'],
      datasets: [{
        label: 'ZAR (R)',
        data: [
          <?= $monthly_revenue ?>,
          <?= $platform_fees_collected ?>,
          <?= $pending_fees ?>,
          <?= $cancelled_value ?>
        ],
        borderColor: '#3f51b5',
        backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#f44336'],
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#3f51b5',
        pointRadius: 5
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  const revenueCtx = document.getElementById('revenueChart').getContext('2d');
  new Chart(revenueCtx, {
    type: 'line',
    data: {
      labels: <?= json_encode(array_column($monthly_data, 'month')) ?>,
      datasets: [{
        label: 'Monthly Revenue',
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
        title: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
        }
      }
    }
  });
</script>

</body>
</html>
