<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$seller_id = $_SESSION['user_id'];


// Handle product update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE Products SET name = ?, price = ?, stock_quantity = ? WHERE product_id = ? AND seller_id = ?");
    $stmt->execute([
        $_POST['name'],
        $_POST['price'],
        $_POST['quantity'],
        $_POST['product_id'],
        $seller_id
    ]);
    header("Location: seller_products.php");
    exit;
}

// Handle product delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM Products WHERE product_id = ? AND seller_id = ?");
    $stmt->execute([$_POST['product_id'], $seller_id]);
    header("Location: seller_products.php");
    exit;
}

// Handle new product row
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    if (!empty($_POST['new_name']) && !empty($_POST['new_price']) && !empty($_POST['new_quantity'])) {
        $stmt = $pdo->prepare("INSERT INTO Products (name, price, stock_quantity, seller_id, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['new_name'],
            $_POST['new_price'],
            $_POST['new_quantity'],
            $seller_id,
            'default.png'
        ]);
        header("Location: seller_products.php");
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM Products WHERE seller_id = ?");
$stmt->execute([$seller_id]);
$products = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Seller Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styling/styles/styles.css?v=<?= time() ?>">
  
  <style>
    .low-stock { color: red; font-weight: bold; }
    .table th, .table td { vertical-align: middle; }
    .form-inline input { width: 100px; }
    .badge { font-size: 0.85rem; }

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
    
    
  </style>
</head>
<body class="bg-light"> 
  <div class="sidebar">
    <h4>Seller Panel</h4>
    <a href="dashboard.php" >Dashboard</a>
    <a href="seller_products.php" class="active">Products</a>
    <a href="seller_orders.php">Orders</a>
    <a href="view_financials.php">Financials</a>
    <a href="logout.php">Logout</a>
    <a href="user_welcome.php">Back to ShopInk</a>
  </div>
  <div class="main-content">
    <h2 class="mb-4">Your Products</h2>

    <form method="post">
      <button name="add_trigger" class="btn btn-primary mb-3">+ Add New Product</button>
    </form>

    <div class="card shadow-lg p-4" style="border-radius: 16px; background-color: #fff;">
      <table class="table table-bordered table-hover bg-white">
        <thead class="table-primary">
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price (ZAR)</th>
            <th>Quantity</th>
            <th>Status</th>
            <th> </th>
            <th> </th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($_POST['add_trigger'])): ?>
            <tr>
              <form method="post">
                <td><img src="../styling/images/default.png" alt="Product Image" style="height: 60px;"></td>
                <td><input type="text" name="new_name" class="form-control" required></td>
                <td><input type="number" step="0.01" name="new_price" class="form-control" required></td>
                <td><input type="number" name="new_quantity" class="form-control" required></td>
                <td><span class="badge bg-secondary">New</span></td>
                <td><button type="submit" name="add" class="btn btn-success">Save</button></td>
                <td><button type="button" class="btn btn-secondary" onclick="window.location='dashboard.php'">Cancel</button></td>
              </form>
            </tr>
          <?php endif; ?>

          <?php foreach ($products as $product): ?>
            <tr>
              <td><img src="../styling/images/<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image" style="height: 60px;"></td>
              <form method="post">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <td><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required></td>
                <td><input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" class="form-control" required></td>
                <td><input type="number" name="quantity" value="<?= $product['stock_quantity'] ?>" class="form-control <?= $product['stock_quantity'] < 5 ? 'low-stock' : '' ?>" required></td>
                <td>
                  <?php if ($product['stock_quantity'] == 0): ?>
                    <span class="badge bg-danger">Out of Stock</span>
                  <?php elseif ($product['stock_quantity'] < 5): ?>
                    <span class="badge bg-warning text-dark">Low Stock</span>
                  <?php else: ?>
                    <span class="badge bg-success">In Stock</span>
                  <?php endif; ?>
                </td>
                <td><button type="submit" name="update" class="btn btn-primary">Update</button></td>
              </form>
              <td>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this product?');">
                  <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                  <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>


