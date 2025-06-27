<?php
include '../database/db.php';

$statusFilter = $_GET['status'] ?? '';
$emailSearch = $_GET['email'] ?? '';

$query = "
  SELECT a.application_id, a.user_id, 
         CONCAT(u.first_name, ' ', u.last_name) AS full_name, 
         u.email_address, 
         a.application_status
  FROM seller_applications a
  JOIN users u ON a.user_id = u.user_id
  WHERE 1=1
";

$params = [];

if ($statusFilter) {
  $query .= " AND a.application_status = ?";
  $params[] = $statusFilter;
}

if ($emailSearch) {
  $query .= " AND u.email_address LIKE ?";
  $params[] = "%$emailSearch%";
}

$query .= " ORDER BY a.submitted_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$applications = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Seller Applications</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styling/styles/styles.css">
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
    .submenu {
      padding-left: 1.5rem;
      font-size: 0.95rem;
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
    <a href="view_orders.php">Orders</a>
    <a href="view_applications.php" class="active">Seller Applications</a>
    <a href="view_admins.php">Admins</a>
    <a href="view_financials.php">Financials</a>
    <a href="reports.php">Reports</a>
    <a href="admin_logout.php">Logout</a>
  </div>

  <div class="main-content">
    <h2 class="text-dark">Seller Applications</h2>

      <form method="GET" class="mb-3 d-flex flex-wrap align-items-center gap-2">
        <label for="status" class="fw-bold me-2">Filter by Status:</label>
        <select name="status" id="status" class="form-select" style="width: 150px;">
          <option value="">All</option>
          <option value="pending" <?= ($statusFilter === 'pending') ? 'selected' : '' ?>>Pending</option>
          <option value="approved" <?= ($statusFilter === 'approved') ? 'selected' : '' ?>>Approved</option>
          <option value="rejected" <?= ($statusFilter === 'rejected') ? 'selected' : '' ?>>Rejected</option>
        </select>

        <input type="text" name="email" class="form-control" placeholder="Search by Email" value="<?= htmlspecialchars($emailSearch) ?>" style="max-width: 250px;">

        <button type="submit" class="btn btn-primary">Apply</button>
      </form>


    <table class="table table-bordered mt-4">
      <thead class="table-head">
        <tr>
          <th>User ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Status</th>
          <th> </th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($applications as $app): ?>
          <tr>
            <td><?= htmlspecialchars($app['user_id']) ?></td>
            <td><?= htmlspecialchars($app['full_name']) ?></td>
            <td><?= htmlspecialchars($app['email_address']) ?></td>
            <td>
              <span class="
                <?= $app['application_status'] === 'approved' ? 'text-success' : 
                    ($app['application_status'] === 'rejected' ? 'text-danger' : 'text-secondary') ?>
              ">
                <?= ucfirst($app['application_status']) ?>
              </span>
            </td>
            <td>
              <a href="application_detail.php?id=<?= $app['application_id'] ?>" class="btn btn-sm btn-primary">View</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
