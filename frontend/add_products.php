<?php
session_start();
include '../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = filter_var($_POST['product_name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $seller_id = $_SESSION['user_id'];

    $target_dir = "../styling/images/";
    $filename = time() . '_' . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;
    $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($image_type, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url, seller_id) 
                               VALUES (:name, :description, :price, :image_url, :seller_id)");
        $stmt->execute([
            ':name' => $product_name,
            ':description' => $description,
            ':price' => $price,
            ':image_url' => $filename,
            ':seller_id' => $seller_id
        ]);

        header('Location: add_product.php?success=1');
        exit;
    } else {
        echo "Sorry, there was an error uploading your image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - ShopInk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../styling/styles/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-2">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-primary" href="../index.php">ShopInk</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow p-4">
        <h3 class="mb-4 text-center">Add New Product</h3>
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success">Product added successfully!</div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
          </div>

          <div class="mb-3">
            <label for="price" class="form-label">Price (R)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Submit Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<footer class="text-center mt-5 py-4 bg-light">© 2025 ShopInk</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>