<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../database/db.php');

$searchTerm = $_GET['search'] ?? '';
$categoryId = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? '';

$query = "SELECT * FROM Products WHERE 1";

if (!empty($searchTerm)) {
  $query .= " AND name LIKE '%" . addslashes($searchTerm) . "%'";
}
if (!empty($categoryId)) {
  $query .= " AND category_id = " . intval($categoryId);
}

switch ($sort) {
  case 'price_asc':
    $query .= " ORDER BY price ASC";
    break;
  case 'price_desc':
    $query .= " ORDER BY price DESC";
    break;
  case 'newest':
    $query .= " ORDER BY created_at DESC";
    break;
  default:
    $query .= " ORDER BY created_at DESC";
}

$stmt = $pdo->query($query);
$products = $stmt->fetchAll();
$categories = $pdo->query("SELECT * FROM Categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - ShopInk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styling/styles/styles.css"> 
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-2">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="index.php">ShopInk</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <form class="d-flex mx-auto my-2 my-lg-0" method="GET" action="products.php" style="max-width: 700px; width: 100%;">
  <input 
    class="form-control me-2" 
    type="search" 
    name="search" 
    placeholder="Search products..." 
    aria-label="Search"
    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
  <button class="btn btn-outline-primary" type="submit">Search</button>
</form>

      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="cart.php">
            <i class="bi bi-cart3" style="font-size: 1.4rem;"></i>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 px-2">
    <h2 class="text-dark mb-2">All Products</h2>

    <!-- Filters and Sort -->
    <div class="d-flex gap-2">
      <!-- Sort -->
      <div class="dropdown">
        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
          Sort
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="?sort=price_asc">Price: Low to High</a></li>
          <li><a class="dropdown-item" href="?sort=price_desc">Price: High to Low</a></li>
          <li><a class="dropdown-item" href="?sort=newest">Newest</a></li>
        </ul>
      </div>

      <!-- Category -->
      <div class="dropdown">
        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
          Category
        </button>
        <ul class="dropdown-menu">
  <li><a class="dropdown-item" href="products.php">All Products</a></li>
  <?php foreach ($categories as $cat): ?>
    <li>
      <a class="dropdown-item" href="?category=<?= $cat['category_id'] ?>">
        <?= htmlspecialchars($cat['name']) ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>

      </div>
    </div>
  </div>

  <!-- Product Grid -->
  <div class="products-row">
    <?php if (count($products) === 0): ?>
      <p class="text-muted text-center">No products found.</p>
    <?php else: ?>
      <?php foreach ($products as $product):
        $imageSrc = filter_var($product['image_url'], FILTER_VALIDATE_URL)
          ? $product['image_url']
          : '../styling/images/' . ($product['image_url'] ?? 'default.png');
      ?>
        <div class="card">
          <div class="img-div">
            <img src="<?= htmlspecialchars($imageSrc) ?>" class="product-img" alt="<?= htmlspecialchars($product['name']) ?>"
                 onerror="this.onerror=null;this.src='../styling/images/default.png';">
          </div>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="card-text fw-bold">R<?= number_format($product['price'], 2) ?></p>
            <div class="card-buttons">
              <a href="product_detail.php?product_id=<?= $product['product_id'] ?>" class="btn btn-primary">View</a>
              <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <button type="submit" class="btn btn-outline-secondary">Add to cart</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<footer class="text-center mt-5 py-4 bg-light">© 2025 ShopInk <a href="logout.php">Logout</a></footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
