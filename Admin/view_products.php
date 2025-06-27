<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php
include '../database/db.php';

$category_query = "SELECT * FROM Categories";
$categories_result = $pdo->query($category_query);

$filter_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

if ($filter_category > 0) {
  $product_query = "SELECT * FROM Products WHERE category_id = $filter_category";
} else {
  $product_query = "SELECT * FROM Products";
}
$products_result = $pdo->query($product_query);
$total_product_count = $products_result->rowCount();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Products - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styling/styles/styles.css">
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
    .card-summary {
      background-color: white;
      padding: 1rem;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .table img {
      height: 50px;
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
     body{
     background-color:  #f5f7fa;
    }
  </style>
</head>
<body>
 <div class="sidebar">
    <h4>ShopInk Admin</h4>
    <a href="admin.php">Dashboard</a>
    <a href="view_products.php" class="active">Products</a>
    <a href="view_users.php">Users</a>
    <a href="view_orders.php">Orders</a>
    <a href="view_applications.php">Seller Applications</a>
    <a href="view_admins.php" >Admins</a>
    <a href="view_financials.php">Financials</a>
    <a href="reports.php">Reports</a>
    <a href="admin_logout.php">Logout</a>
  </div>

  <div class="main-content">
   <h2 class="mb-4 text-dark">All Products</h2>

<form method="GET" class="mb-4">
  <label for="category">Filter by Category:</label>
  <select name="category" id="category" onchange="this.form.submit()" class="form-select" style="max-width: 300px;">
    <option value="0">All Categories</option>
    <?php while ($row = $categories_result->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?= $row['category_id'] ?>" <?= $filter_category == $row['category_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($row['name']) ?>
      </option>
    <?php endwhile; ?>
  </select>
</form>


    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-head">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price (R)</th>
            <th>Stock</th>
            <th>Category ID</th>
            <th>Image</th>
            <th>Created</th>
          </tr>
        </thead>
        <body>
          <?php while($row = $products_result->fetch(PDO::FETCH_ASSOC)): ?>
          <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['stock_quantity']; ?></td>
            <td><?php echo $row['category_id']; ?></td>
            <td>
              <?php
                $imageFilename = basename($row['image_url'] ?? '');
                $imagePath = '../styling/images/' . $imageFilename;
                $defaultImage = '../styling/images/default.jpeg';

                if (!file_exists($imagePath) || empty($imageFilename)) {
                  $imagePath = $defaultImage;
                }
                ?>
                <img src="<?= $imagePath ?>" alt="Product Image">

            </td>
            <td><?php echo $row['created_at']; ?></td>
          </tr>
          <?php endwhile; ?>
        </body>
      </table>
      <p class="text-muted">Total Products: <?= $total_product_count ?></p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



