<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php
include '../database/db.php';


$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';


$query = "SELECT o.*, u.first_name, u.last_name FROM Orders o JOIN Users u ON o.user_id = u.user_id WHERE 1=1";

$params = [];

if (!empty($filter_status)) {
    $query .= " AND o.order_status = :status";
    $params['status'] = $filter_status;
}
if (!empty($filter_date)) {
    $query .= " AND DATE(o.order_date) = :date";
    $params['date'] = $filter_date;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_order_count = $stmt->rowCount();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styling/styles/styles.css">
  <style>
    body {
      display: flex;
      min-height: 100vh;
      background: #f5f7fa;
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
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 2rem;
    }
      .table-head {
      background-color: rgba(58, 74, 178, 0.79);
      color: white;
    }
    .table-head :hover{
      background-color: rgba(58, 74, 178, 0.79);
      color: white;
    }
    .table{
      background-color:rgb(250, 250, 251) ;
      border-radius: 3px;
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
    <a href="view_orders.php" class="active">Orders</a>
    <a href="view_applications.php">Seller Applications</a>
    <a href="view_admins.php">Admins</a>
    <a href="view_financials.php">Financials</a>
    <a href="reports.php">Reports</a>
    <a href="admin_logout.php">Logout</a>
  </div>

  <div class="main-content container">
    <h2 class="text-dark mb-3">All Orders</h2>

    <form method="get" class="d-flex mb-3 justify-content-between">
      <div class="d-flex gap-2">
        <select name="status" class="form-select w-auto">
          <option value="">All Statuses</option>
          <option value="pending" <?= $filter_status === 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="shipped" <?= $filter_status === 'shipped' ? 'selected' : '' ?>>Shipped</option>
          <option value="delivered" <?= $filter_status === 'delivered' ? 'selected' : '' ?>>Delivered</option>
          <option value="cancelled" <?= $filter_status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($filter_date) ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
      </div>
    </form>

    <table class="table table-bordered table-striped">
      <thead class="table-head">
        <tr>
          <th>Order ID</th>
          <th>User</th>
          <th>Status</th>
          <th>Date</th>
          <th>Total Cost</th>
          <th>Shipping Address</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?= htmlspecialchars($order['order_id']) ?></td>
            <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
            <td><?= ucfirst($order['order_status']) ?></td>
            <td><?= htmlspecialchars($order['order_date']) ?></td>
            <td>R<?= number_format($order['total_cost'], 2) ?></td>
            <td><?= htmlspecialchars($order['shipping_address']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($orders)): ?>
          <tr><td colspan="6" class="text-center">No orders found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <p class="text-muted">Total Orders: <?= $total_order_count ?></p>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
