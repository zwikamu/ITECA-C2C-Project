<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../database/db.php';

require_once '../libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Generate Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background:rgb(255, 255, 255);
      font-family: 'Segoe UI', sans-serif;
    }
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
    .main-content {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 2rem;
    }
    .btn-primary{
      background-color:rgb(141, 140, 140);
      border: none;
    }
    h3{
      color: rgb(54, 54, 54);
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
    <a href="view_financials.php">Financials</a>
    <a href="reports.php" class="active">Reports</a>
    <a href="admin_logout.php">Logout</a>
  </div>

  <div class="main-content">
    <h3 class="mb-4">Generate Reports</h3>

    <form method="POST" action="generate_report.php" class="row g-3">
      <div class="col-md-4">
        <label for="month" class="form-label">Select Month</label>
        <input type="month" class="form-control" name="month" id="month" required>
      </div>
      <div class="col-md-4">
        <label for="type" class="form-label">Report Type</label>
        <select class="form-select" name="type" id="type" required>
          <option value="financial">Financial Summary</option>
          <option value="orders">Orders</option>
          <option value="products">Products</option>
          <option value="users">Users</option>
          <option value="custom">All Data</option>
        </select>
      </div>
      <div class="col-md-4 align-self-end">
        <button type="submit" class="btn btn-primary">Generate Report</button>
      </div>
    </form>
  </div>
</body>
</html>
