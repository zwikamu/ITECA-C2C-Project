<?php
session_start();
include('../database/db.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Initialize cart
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    $qty = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

    $stmt = $pdo->prepare("SELECT * FROM Products WHERE product_id = ?");
    $stmt->execute([$pid]);
    $product = $stmt->fetch();

    if ($product) {
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$pid] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'quantity' => $qty
            ];
        }
    }

    header("Location: cart.php");
    exit;
}

// Remove item
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int)$_GET['remove']]);
    header("Location: cart.php");
    exit;
}

// Update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = (int)$_POST['update_id'];
    $qty = max(1, (int)$_POST['new_quantity']);
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] = $qty;
    }
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Your Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2 class="mb-4">Your Shopping Cart</h2>

  <?php if (!empty($_SESSION['cart'])): ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $grand_total = 0; ?>
        <?php foreach ($_SESSION['cart'] as $id => $item): 
          $total = $item['price'] * $item['quantity'];
          $grand_total += $total;
        ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>R<?= number_format($item['price'], 2) ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="update_id" value="<?= $id ?>">
                <input type="number" name="new_quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width:80px; display:inline;">
                <button class="btn btn-sm btn-outline-primary">Update</button>
              </form>
            </td>
            <td>R<?= number_format($total, 2) ?></td>
            <td><a href="?remove=<?= $id ?>" class="btn btn-sm btn-outline-danger">Remove</a></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" class="text-end fw-bold">Total:</td>
          <td colspan="2"><strong>R<?= number_format($grand_total, 2) ?></strong></td>
        </tr>
      </tbody>
    </table>
    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
  <?php else: ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>
</div>
</body>
</html>

