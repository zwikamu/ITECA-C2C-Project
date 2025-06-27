<?php
include '../../database/db.php'; // Adjust path as needed

$jsonData = file_get_contents('updated_dummy_products.json');
$products = json_decode($jsonData, true);

foreach ($products as $product) {
    $stmt = $pdo->prepare("
        INSERT INTO Products (name, description, price, stock_quantity, category_id, image_url, seller_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $name = $product['title'];
    $description = $product['description'];
    $price = $product['price'];
    $stock = $product['stock_quantity'];
    
    // Assign category IDs based on name
    $category = $product['category'];
    $category_id = match($category) {
        "electronics" => 1,
        "fashion" => 2,
        "home" => 3,
        default => 5,
    };

    $image = $product['image_url'];
    $seller_id = 29;

    $stmt->execute([$name, $description, $price, $stock, $category_id, $image, $seller_id]);
}

echo "Products imported successfully!";
?>
