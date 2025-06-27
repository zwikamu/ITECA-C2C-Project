<?php
session_start();
include('../database/db.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


if (!isset($_GET['product_id'])) {
    echo "Product not found.";
    exit;
}

$product_id = $_GET['product_id'];

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

if (!$order) {
    echo "You can only review products you have purchased.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)$_POST['rating'];
    $comment = $_POST['comment'];

    
    if ($rating < 1 || $rating > 5) {
        echo "Rating must be between 1 and 5.";
        exit;
    }


    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, comment, review_date) 
                           VALUES (:product_id, :user_id, :rating, :comment, NOW())");
    $stmt->execute([
        ':product_id' => $product_id,
        ':user_id' => $user_id,
        ':rating' => $rating,
        ':comment' => $comment
    ]);

    echo "Thank you for your review!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styling/styles/styles.css">
</head>
<body>

<div class="container">
    <h2>Review Product</h2>

   
    <form method="POST" action="review_product.php?product_id=<?php echo $product_id; ?>">
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
</div>

</body>
</html>
