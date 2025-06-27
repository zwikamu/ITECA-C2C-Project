<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

include '../database/db.php';

$user_role = $_SESSION['role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 10");
$featuredProducts = $stmt->fetchAll();





?>

<!DOCTYPE HTML>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopInk E-Commerce Platform</title>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="../styling/styles/styles.css">
  <style>
    .modal-content {
      border-radius: 10px;
      box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
    }

    .nav-tabs .nav-link.active {
      background-color: #4c5cc3;
      color: white;
    }
  </style>
  </style>
</head>

<body>


  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-2">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold text-primary" href="../index.php">ShopInk</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
        aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
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
          <?php if ($user_id): ?>
  <li class="nav-item">
    <?php if ($user_role === 'seller'): ?>
      <a class="nav-link" href="dashboard.php">Seller Dashboard</a>
    <?php else: ?>
      <a class="nav-link" href="apply_seller.php">Apply to be a Seller</a>
    <?php endif; ?>
  </li>
<?php else: ?>
  <li class="nav-item">
    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">Apply to be a Seller</a>
  </li>
<?php endif; ?>


          <!-- Cart Icon (always visible) -->
          <li class="nav-item">
            <a class="nav-link" href="cart.php">
              <i class="bi bi-cart3" style="font-size: 1.4rem;"></i>
            </a>
          </li>
          </ul>


      </div>
    </div>
  </nav>

  

  <div class="container mt-3">
  <div class="row">
    <!-- Carousel on the left -->
<section class="featured-carousel my-5">
  <div id="shopCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../styling/images/carasoul1.png" class="d-block w-40" alt="Promo 1">
      </div>
      <div class="carousel-item">
        <img src="../styling/images/carasoul2.png" class="d-block w-100" alt="Backpack Promo">
      </div>
      <div class="carousel-item">
        <img src="../styling/images/carasoul3.png" class="d-block w-100" alt="Jewellery Promo">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#shopCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#shopCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>

<!-- QUICK CATEGORIES SECTION -->
  <section class="quick-categories container my-4">
    <h4 class="mb-4 text-dark">Quick Categories</h4>
    <div class="row justify-content-center gx-3 gy-3">
      <?php
      $quickCategories = $pdo->query("SELECT * FROM Categories ORDER BY RAND() LIMIT 6")->fetchAll();
      foreach ($quickCategories as $cat):
        ?>
        <div class="col-6 col-sm-4 col-md-2">
          <button class="category-tile btn btn-outline-primary w-100"
        data-category-id="<?= $cat['category_id'] ?>">
        <?= htmlspecialchars($cat['name']) ?>
      </button>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

    <!-- Product cards to the right -->
    <div class="col-md-12">
      <div class="products-row">
        <?php foreach ($featuredProducts as $product): ?>
          <?php
            $imageSrc = filter_var($product['image_url'], FILTER_VALIDATE_URL)
              ? $product['image_url']
              : '../styling/images/' . ($product['image_url'] ?? 'default.png');
          ?>
          <div class="card">
            <div class="img-div">
            <img src="<?= $imageSrc ?>" class="product-img" alt="<?= $product['name'] ?>"
              onerror="this.onerror=null;this.src='../styling/images/default.png';">
              </div>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text">R<?= number_format($product['discount_price'] ?? $product['price'], 2) ?></p>
              <div class="card-buttons">
                <a href="product_detail.php?id=<?= $product['product_id'] ?>" class="btn btn-primary">View</a>
                <form action="cart.php" method="POST" style="display:inline;">
                  <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                  <button type="submit" class="btn btn-outline-secondary">Add to cart</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>


  <!-- Modal -->
  <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3">
        <div class="modal-header">
          <h5 class="modal-title text-dark" id="authModalLabel">Welcome to ShopInk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body ">
          <ul class="nav nav-tabs mb-3" id="authTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginTab"
                type="button" role="tab">Login</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerTab"
                type="button" role="tab">Register</button>
            </li>
          </ul>

          <div class="tab-content" id="authTabContent">
            <!-- Login -->
            <div class="tab-pane fade show active" id="loginTab" role="tabpanel">
              <form method="POST" action="login_handler.php">
                <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
              </form>
            </div>

            <!-- Register -->
            <div class="tab-pane fade" id="registerTab" role="tabpanel">
              <form method="POST" action="frontend/register_handler.php">
                <div class="mb-2">
                  <label>First Name</label>
                  <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="mb-2">
                  <label>Last Name</label>
                  <input type="text" name="last_name" class="form-control" required>
                </div>
                <div class="mb-2">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-2">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Confirm Password</label>
                  <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Register</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.category-tile').forEach(button => {
  button.addEventListener('click', () => {
    const categoryId = button.dataset.categoryId;
    const productArea = document.getElementById('productDisplayArea');

    fetch('frontend/load_products.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `category_id=${categoryId}`
    })
    .then(response => response.text())
    .then(data => {
      productArea.innerHTML = data;
    })
    .catch(err => {
      productArea.innerHTML = `<p class="text-danger">Failed to load products. Try again.</p>`;
    });
  });
});
</script>
<!-- View All Products Button -->
<div class="text-center my-5">
  <a href="products.php" class="btn btn-primary px-5">View All Products</a>
</div>

<!-- Footer -->
<footer class="text-center mt-5 py-4 bg-light">
  © 2025 ShopInk <a href="logout.php">Logout</a>
</footer>


</body>

</html>