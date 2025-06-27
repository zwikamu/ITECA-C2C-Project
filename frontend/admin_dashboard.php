<?php
session_start();
include('../database/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_type'])) {
    $reportType = $_POST['report_type'];
    switch ($reportType) {
        case 'sales_report':
            $report = generateSalesReport($pdo);
            break;
        case 'user_activity':
            $report = generateUserActivityReport($pdo);
            break;
        case 'product_performance':
            $report = generateProductPerformanceReport($pdo);
            break;
        default:
            $report = "Invalid report type selected.";
            break;
    }
} else {
    $report = "Please select a report.";
}

function generateSalesReport($pdo) {
    $stmt = $pdo->query("SELECT SUM(amount) AS total_sales, COUNT(order_id) AS total_orders FROM Orders WHERE order_date > DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $result = $stmt->fetch();
    return "Total Sales: R" . number_format($result['total_sales'], 2) . "<br>Total Orders: " . $result['total_orders'];
}

function generateUserActivityReport($pdo) {
    $stmt = $pdo->query("SELECT COUNT(user_id) AS active_users FROM Users WHERE last_login > DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $result = $stmt->fetch();
    return "Active Users in Last 30 Days: " . $result['active_users'];
}

function generateProductPerformanceReport($pdo) {
    $stmt = $pdo->query("SELECT p.name, SUM(oi.quantity) AS total_sold FROM Products p JOIN Order_Items oi ON p.product_id = oi.product_id GROUP BY p.product_id ORDER BY total_sold DESC");
    $products = $stmt->fetchAll();
    $output = "<table class='table'><thead><tr><th>Product</th><th>Sold</th></tr></thead><tbody>";
    foreach ($products as $product) {
        $output .= "<tr><td>" . htmlspecialchars($product['name']) . "</td><td>" . $product['total_sold'] . "</td></tr>";
    }
    return $output . "</tbody></table>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styling/styles/styles.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>
    <a href="logout.php" class="btn btn-danger">Logout</a>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Select Report</label>
            <select name="report_type" class="form-control">
                <option value="sales_report">Sales Report</option>
                <option value="user_activity">User Activity Report</option>
                <option value="product_performance">Product Performance Report</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>

    <div class="mt-4">
        <h3>Report Output</h3>
        <div class="border p-3"><?php echo $report; ?></div>
    </div>
</div>

</body>
</
