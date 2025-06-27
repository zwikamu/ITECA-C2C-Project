<?php
session_start();
include('../database/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
ini_set('display_errors', 1);
error_reporting(E_ALL);
$user_id = $_SESSION['user_id'];

if (!isset($_GET['product_id'])) {
    echo "Product not found.";
    exit;
}

$product_id = $_GET['product_id'];

$stmt = $pdo->prepare("
  SELECT p.*, u.first_name, u.last_name 
  FROM Products p
  LEFT JOIN Users u ON p.seller_id = u.user_id
  WHERE p.product_id = :product_id
");

$stmt->execute([':product_id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit;
}

$stmt = $pdo->prepare("
    SELECT * FROM Orders 
    WHERE user_id = :user_id 
    AND order_status = 'delivered' 
    AND order_id IN (SELECT order_id FROM Order_Items WHERE product_id = :product_id)
");
$stmt->execute([
    ':user_id' => $user_id,
    ':product_id' => $product_id
]);
$order = $stmt->fetch();
$can_review = $order ? true : false; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating']) && $can_review) {
    $rating = (int)$_POST['rating'];
    $comment = $_POST['comment'];

    if ($rating < 1 || $rating > 5) {
        echo "Rating must be between 1 and 5.";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO Reviews (product_id, user_id, rating, comment, review_date) 
                           VALUES (:product_id, :user_id, :rating, :comment, NOW())");
    $stmt->execute([
        ':product_id' => $product_id,
        ':user_id' => $user_id,
        ':rating' => $rating,
        ':comment' => $comment
    ]);

    echo "Thank you for your review!";
}

$stmt = $pdo->prepare("SELECT * FROM Reviews WHERE product_id = :product_id ORDER BY review_date DESC");
$stmt->execute([':product_id' => $product_id]);
$reviews = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($product['name']); ?> | ShopInk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styling/styles/styles.css">
</head>
<body>
     <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-1">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="index.php">ShopInk</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    
      <form class="d-flex mx-auto" style="max-width: 800px; width: 100%;">
        <input class="form-control me-2" type="search" placeholder="Search for products" aria-label="Search">
        <button class="btn btn-outline-primary" type="submit">Search</button>
      </form>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
              <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .49.598l-1.5 7A.5.5 0 0 1 13 13H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zM3.102 6l1.313 6h8.17l1.313-6H3.102z"/>
              <path d="M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7-1a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container product-detail-container">
  <div class="product-image">
    <?php
    $imageSrc = filter_var($product['image_url'], FILTER_VALIDATE_URL)
      ? $product['image_url']
      : '../styling/images/' . ($product['image_url'] ?? 'default.png');
    ?>
<img src="<?= htmlspecialchars($imageSrc) ?>" alt="<?= htmlspecialchars($product['name']) ?>"
     onerror="this.onerror=null;this.src='../styling/images/default.png';">
  </div>

  <div class="product-info">
    <h1 class="text-dark"><strong><?php echo htmlspecialchars($product['name']); ?></strong></h1>
    <p class="price">R<?php echo number_format($product['price'], 2); ?></p>
    <p class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    <form method="POST" action="cart.php">
      <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="quantity" class="form-label">Quantity</label>
          <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="99" required>
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
        </div>
        <div>
        <p class="text-muted">Sold by: <?= htmlspecialchars($product['first_name'] . ' ' . $product['last_name']) ?></p>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="container reviews-section">
  <h3 class="text-dark"><strong>Customer Reviews</strong></h3>

  <?php if (count($reviews) > 0): ?>
    <?php foreach ($reviews as $review): ?>
      <div class="review">
        <div class="rating"><strong>Rating:</strong> <?php echo $review['rating']; ?> / 5</div>
        <div class="comment"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></div>
        <div class="date"><small>Reviewed on <?php echo $review['review_date']; ?></small></div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No reviews yet for this product. Be the first to leave a review!</p>
  <?php endif; ?>

  <?php if ($can_review): ?>
    <form method="POST" class="leave-review-form">
      <h4>Leave a Review</h4>
      <div class="mb-3">
        <label for="rating" class="form-label">Rating</label>
        <select name="rating" id="rating" class="form-control" required>
          <option value="1">1 - Very Bad</option>
          <option value="2">2 - Bad</option>
          <option value="3">3 - Average</option>
          <option value="4">4 - Good</option>
          <option value="5">5 - Excellent</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="comment" class="form-label">Your Review</label>
        <textarea name="comment" id="comment" class="form-control" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
  <?php else: ?>
    <p class="text-muted">You can only review products you’ve purchased.</p>
  <?php endif; ?>
</div>

</body>
</html>


