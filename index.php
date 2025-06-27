<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'database/db.php';

$user_role = $_SESSION['role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
$featuredProducts = $stmt->fetchAll();

$fashionProducts = $pdo->query("
  SELECT p.* FROM products p
  JOIN categories c ON p.category_id = c.category_id
  WHERE c.name = 'Fashion'
  ORDER BY p.created_at DESC
  LIMIT 10
")->fetchAll();

$top_selling = $pdo->query("
  SELECT p.*, SUM(oi.quantity) AS total_sold
  FROM Products p
  JOIN Order_Items oi ON p.product_id = oi.product_id
  GROUP BY p.product_id
  ORDER BY total_sold DESC
  LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

$new_product = $pdo->query("SELECT * FROM Products ORDER BY created_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$discounted = $pdo->query("SELECT * FROM Products WHERE discount_price IS NOT NULL ORDER BY created_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

$carousel_items = [
  'Top Seller' => $top_selling ?: ['name' => 'No Top Seller', 'image_url' => 'styling/images/laptop.jpeg'],
  'New Arrival' => $new_product ?: ['name' => 'No New Product', 'image_url' => 'styling/images/default.jpeg'],
  'On Discount' => $discounted ?: ['name' => 'No Discounted Product', 'image_url' => 'styling/images/default.jpeg'],
];

 if (isset($_SESSION['register_error'])): ?>
  <div class="alert alert-danger small">
    <?= htmlspecialchars($_SESSION['register_error']) ?>
  </div>
<?php unset($_SESSION['register_error']); ?>
<?php endif; 



?>

<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopInk E-Commerce Platform</title>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styling/styles/styles.css?v=<?= time() ?>">
  <style>
     .modal-content {
  font-size: 0.92rem;
  padding: 1rem 1rem;
  transition: all 0.1s ease;
  overflow: hidden
}

.modal-body{
  
}
.modal-body label {
  font-weight: 500;
  font-size: 0.9rem;
}



  </style>
</head>
<body>

 
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-2">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold text-primary" href="index.php">ShopInk</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

 
      <div class="collapse navbar-collapse" id="mainNavbar">
 
        <form class="d-flex mx-auto my-2 my-lg-0" style="max-width: 700px; width: 100%;">
          <input class="form-control me-2" type="search" placeholder="Search for products" aria-label="Search">
          <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>

       
          <ul class="navbar-nav ms-auto align-items-center">
          
            <li class="nav-item">
              <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">Login / Register</a>
            </li>
          
        </ul>

      </div>
    </div>
  </nav>


  
  <div class="container">
    <div class="hero text-center">
      <h1>Welcome to ShopInk</h1>
      <p>Buy and Sell products to and from locals in South Africa</p>
    </div>
  </div>

  <div class="container mt-5">
    <div id="productCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
 <section class="featured-carousel my-5">
  <div id="shopCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="styling/images/carasoul1.png" class="d-block w-40" alt="Promo 1">
      </div>
      <div class="carousel-item">
        <img src="/ShopInk/styling/images/carasoul2.png" class="d-block w-40" alt="Promo 1">
      </div>
      <div class="carousel-item">
        <img src="styling/images/carasoul3.png" class="d-block w-100" alt="Jewellery Promo">
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
</div>

    <h2 class="text-dark">Featured Products</h2>
    <div class="col-md-12">
      <div class="products-row">
        <?php foreach ($featuredProducts as $product): ?>
          <?php
            $imageSrc = filter_var($product['image_url'], FILTER_VALIDATE_URL)
              ? $product['image_url']
              : 'styling/images/' . ($product['image_url'] ?? 'default.png');
          ?>
          <div class="card">
            <div class="img-div">
            <img src="<?= $imageSrc ?>" class="product-img" alt="<?= $product['name'] ?>"
              onerror="this.onerror=null;this.src='styling/images/default.png';">
              </div>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text">R<?= number_format($product['discount_price'] ?? $product['price'], 2) ?></p>
              <div class="card-buttons">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#authModal">View</button>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#authModal">Add to cart</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

<h2 class="text-dark mt-5">Fashion Products</h2>
<div class="col-md-12">
  <div class="products-row">
    <?php foreach ($fashionProducts as $product): ?>
      <?php
        $imageSrc = filter_var($product['image_url'], FILTER_VALIDATE_URL)
          ? $product['image_url']
          : 'styling/images/' . ($product['image_url'] ?? 'default.png');
      ?>
      <div class="card">
        <div class="img-div">
          <img src="<?= $imageSrc ?>" class="product-img" alt="<?= $product['name'] ?>"
            onerror="this.onerror=null;this.src='styling/images/default.png';">
        </div>
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
          <p class="card-text">R<?= number_format($product['discount_price'] ?? $product['price'], 2) ?></p>
          <div class="card-buttons">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#authModal">View</button>
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#authModal">Add to cart</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
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
      
      <div class="modal-body">
        <ul class="nav nav-tabs mb-3" id="authTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-dark " id="login-tab" data-bs-toggle="tab" data-bs-target="#loginTab" type="button" role="tab">Login</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-dark" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerTab" type="button" role="tab">Register</button>
          </li>
        </ul>

        <div class="tab-content" id="authTabContent" >
          <!-- Login -->
        <div class="tab-pane fade show active text-dark" id="loginTab" role="tabpanel">
          <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success small"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
            <?php unset($_SESSION['success_message']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger small"><?= htmlspecialchars($_SESSION['error']) ?></div>
          <?php endif; ?>

            <form method="POST" action="../ShopInk/frontend/login_handler.php">
              <div class="mb-2">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?= isset($_SESSION['error']) ? 'is-invalid' : '' ?>" 
                      value="<?= htmlspecialchars($_SESSION['old_email'] ?? '') ?>" required>
              </div>

              <div class="mb-2">
                <label>Password</label>
              <div class="input-group">
            <input type="password" name="password" id="loginPassword" class="form-control <?= isset($_SESSION['error']) ? 'is-invalid' : '' ?>" required>
            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="loginPassword">Show</button>
          </div>
            </div>

              <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <?php unset($_SESSION['error'], $_SESSION['old_email']); ?>

</div>


         

          <!-- Register -->
          <div class="tab-pane fade text-dark" id="registerTab" role="tabpanel">
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
                <div class="input-group">
                  <input type="password" name="password" id="registerPassword" class="form-control" required>
                  <button class="btn btn-outline-secondary toggle-password" type="button" data-target="registerPassword">Show</button>
                </div>
              </div>
              <div class="mb-3">
                <label>Confirm Password</label>
                <div class="input-group">
                  <input type="password" name="confirm_password" id="confirmRegisterPassword" class="form-control" required>
                  <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirmRegisterPassword">Show</button>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php if (
  isset($_GET['auth_error']) ||
  isset($_GET['auth_success']) ||
  isset($_SESSION['register_error']) ||
  isset($_SESSION['error'])
): ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const authModal = new bootstrap.Modal(document.getElementById('authModal'));
    authModal.show();

    const activeTab = "<?= $_SESSION['auth_tab'] ?? 'login' ?>";
    if (activeTab === 'register') {
      document.getElementById('register-tab').click();
    } else {
      document.getElementById('login-tab').click();
    }
  });
</script>
<?php
  unset($_SESSION['auth_tab']); // optional cleanup
endif;
?>


</body>
  <footer class="text-center mt-5 py-4 bg-light">© 2025 ShopInk</footer>
    
</html>