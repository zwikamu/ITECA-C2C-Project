<?php
include '../database/db.php';

// Filter role from dropdown
$filter_role = $_GET['role'] ?? '';

// Build query
$query = "SELECT u.*, 
         (SELECT application_status FROM seller_applications sa WHERE sa.user_id = u.user_id ORDER BY submitted_at DESC LIMIT 1) AS seller_application_status
         FROM Users u";

if ($filter_role !== '') {
    $query .= " WHERE u.role = :role";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['role' => $filter_role]);
} else {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_user_count = count($users);

// Handle AJAX Role Update or Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
    if ($_POST['ajax_action'] === 'update_role') {
        $stmt = $pdo->prepare("UPDATE Users SET role = :role WHERE user_id = :id");
        $stmt->execute(['role' => $_POST['new_role'], 'id' => $_POST['user_id']]);
        echo "success";
        exit;
    }

    if ($_POST['ajax_action'] === 'delete_user') {
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = :id");
        $stmt->execute(['id' => $_POST['user_id']]);
        echo "deleted";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View All Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styling/styles/styles.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      display: flex;
      background: rgb(255, 255, 255);
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
    .main-wrapper {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 2rem;
    }

    .card-summary {
      background-color: white;
      padding: 1rem;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
    .btn{
      background-color: gray;
      border: none;
    }
    .editable-role {
  cursor: pointer;
}
.role-select {
  padding: 0.25rem;
}

  </style>
</head>
<body>

<div class="sidebar">
    <h4>ShopInk Admin</h4>
    <a href="admin.php">Dashboard</a>
    <a href="view_products.php">Products</a>
    <a href="view_users.php" class="active">Users</a>
    <a href="view_orders.php">Orders</a>
    <a href="view_applications.php">Seller Applications</a>
    <a href="view_admins.php" >Admins</a>
    <a href="view_financials.php">Financials</a>
    <a href="reports.php">Reports</a>
    <a href="admin_logout.php">Logout</a>
  </div>

<div class="main-wrapper">
  <h2 class="text-dark mb-1">All Users</h2>

 <form method="GET" class="mb-4">
  <label for="role">Filter by User Role:</label>
  <select name="role" id="role" onchange="this.form.submit()" class="form-select" style="max-width: 300px;">
    <option value="">All Users</option>
    <option value="buyer" <?= $filter_role === 'buyer' ? 'selected' : '' ?>>Buyer</option>
    <option value="seller" <?= $filter_role === 'seller' ? 'selected' : '' ?>>Seller</option>
  </select>
</form>


  <table class="table table-bordered table-striped">
    <thead class="table-head">
      <tr>
        <th>User ID</th>
        <th>Email</th>
        <th>Name</th>
        <th>Role</th>
        <th>Seller Status</th>
        <th>Created At</th>
        <th></th>

      </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= htmlspecialchars($user['user_id']) ?></td>
            <td><?= htmlspecialchars($user['email_address']) ?></td>
            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
            <td class="editable-role" data-user-id="<?= $user['user_id'] ?>">
              <span class="role-label"><?= htmlspecialchars($user['role']) ?></span>
              <select class="form-select d-none role-select">
                <option value="buyer" <?= $user['role'] === 'buyer' ? 'selected' : '' ?>>Buyer</option>
                <option value="seller" <?= $user['role'] === 'seller' ? 'selected' : '' ?>>Seller</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
              </select>
            </td>
            <td><?= ucfirst($user['seller_application_status']) ?></td>
            <td><?= htmlspecialchars($user['created_at']) ?></td>
            <td>
              <button class="btn btn-danger btn-sm delete-user" data-user-id="<?= $user['user_id'] ?>">Delete</button>
            </td>

          </tr>
        <?php endforeach; ?>
      </tbody>
  </table>
  <p class="text-muted">Total Users: <?= $total_user_count ?></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.editable-role').forEach(cell => {
  const span = cell.querySelector('.role-label');
  const select = cell.querySelector('.role-select');

  cell.addEventListener('click', () => {
    span.classList.add('d-none');
    select.classList.remove('d-none');
  });

  select.addEventListener('change', () => {
    const userId = cell.dataset.userId;
    const newRole = select.value;

    fetch('view_users.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({
        ajax_action: 'update_role',
        user_id: userId,
        new_role: newRole
      })
    })
    .then(res => res.text())
    .then(response => {
      if (response === 'success') {
        span.textContent = newRole;
        select.classList.add('d-none');
        span.classList.remove('d-none');
      }
    });
  });
});

document.querySelectorAll('.delete-user').forEach(btn => {
  btn.addEventListener('click', () => {
    if (!confirm("Are you sure you want to delete this user?")) return;

    const userId = btn.dataset.userId;
    fetch('view_users.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({
        ajax_action: 'delete_user',
        user_id: userId
      })
    })
    .then(res => res.text())
    .then(response => {
      if (response === 'deleted') {
        document.querySelector(`[data-user-id="${userId}"]`).closest('tr').remove();
      }
    });
  });
});
</script>

</body>
</html>
