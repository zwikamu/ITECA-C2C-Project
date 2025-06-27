<?php
include '../database/db.php';
$category_id = $_POST['category_id'] ?? null;

if (!$category_id || !is_numeric($category_id)) {
  http_response_code(400);
  echo "<div class='alert alert-danger'>Invalid category selected.</div>";
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY created_at DESC LIMIT 12");
$stmt->execute([$category_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($products)) {
  echo "<div class='alert alert-info'>No products in this category yet.</div>";
  exit;
}

foreach ($products as $product):
  $image = filter_var($product['image_url'], FILTER_VALIDATE_URL)
    ? $product['image_url']
    : '../styling/images/' . ($product['image_url'] ?? 'default.png');
?>
  <div class="col-md-3 mb-4">
    <div class="card h-100">
      <img src="<?= $image ?>" class="card-img-top" style="height: 200px; object-fit: contain;">
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
        <p class="card-text fw-bold">R<?= number_format($product['discount_price'] ?? $product['price'], 2) ?></p>
        <a href="product_detail.php?product_id=<?= $product['product_id'] ?>" class="btn btn-primary">View</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
