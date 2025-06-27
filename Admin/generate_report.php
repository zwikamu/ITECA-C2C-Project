<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
include '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = $_POST['month']; // e.g., 2025-06
    $type = $_POST['type'];
    $formattedMonth = date("F Y", strtotime($month));

    $html = "<h1 style='text-align:center;'>ShopInk " . ucfirst($type) . " Report</h1>";
    $html .= "<h4 style='text-align:center;'>Generated for: $formattedMonth</h4><br>";

    // USERS REPORT
    if ($type === 'users' || $type === 'custom') {
        $stmt = $pdo->query("SELECT user_id, email_address, role, is_verified, seller_application_status, created_at FROM Users");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $html .= "<h3>All Users</h3>";
        if (count($rows) === 0) {
            $html .= "<p>No users found.</p>";
        } else {
            $html .= "<table border='1' width='100%' cellspacing='0' cellpadding='6'>
                      <thead><tr><th>ID</th><th>Email</th><th>Role</th><th>Verified</th><th>Seller Status</th><th>Created At</th></tr></thead><tbody>";
            foreach ($rows as $r) {
                $html .= "<tr>
                            <td>{$r['user_id']}</td>
                            <td>{$r['email_address']}</td>
                            <td>{$r['role']}</td>
                            <td>" . ($r['is_verified'] ? 'Yes' : 'No') . "</td>
                            <td>" . ucfirst($r['seller_application_status']) . "</td>
                            <td>{$r['created_at']}</td>
                          </tr>";
            }
            $html .= "</tbody></table><br>";
        }
    }

    // PRODUCTS REPORT
    if ($type === 'products' || $type === 'custom') {
        $stmt = $pdo->query("SELECT product_id, name, description, price, stock_quantity, seller_id, created_at FROM Products");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $html .= "<h3>All Products</h3>";
        if (count($rows) === 0) {
            $html .= "<p>No products found.</p>";
        } else {
            $html .= "<table border='1' width='100%' cellspacing='0' cellpadding='6'>
                      <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Seller ID</th><th>Created At</th></tr></thead><tbody>";
            foreach ($rows as $r) {
                $html .= "<tr>
                            <td>{$r['product_id']}</td>
                            <td>{$r['name']}</td>
                            <td>{$r['description']}</td>
                            <td>R{$r['price']}</td>
                            <td>{$r['stock_quantity']}</td>
                            <td>{$r['seller_id']}</td>
                            <td>{$r['created_at']}</td>
                          </tr>";
            }
            $html .= "</tbody></table><br>";
        }
    }

    // ORDERS REPORT
    if ($type === 'orders' || $type === 'custom') {
        $stmt = $pdo->query("SELECT order_id, user_id, order_date, total_amount, order_status FROM Orders");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $html .= "<h3>All Orders</h3>";
        if (count($rows) === 0) {
            $html .= "<p>No orders found.</p>";
        } else {
            $html .= "<table border='1' width='100%' cellspacing='0' cellpadding='6'>
                      <thead><tr><th>Order ID</th><th>User ID</th><th>Date</th><th>Total</th><th>Status</th></tr></thead><tbody>";
            foreach ($rows as $r) {
                $html .= "<tr>
                            <td>{$r['order_id']}</td>
                            <td>{$r['user_id']}</td>
                            <td>{$r['order_date']}</td>
                            <td>R{$r['total_amount']}</td>
                            <td>{$r['order_status']}</td>
                          </tr>";
            }
            $html .= "</tbody></table><br>";
        }
    }

    // FINANCIALS REPORT
    if ($type === 'financial' || $type === 'custom') {
        $stmt = $pdo->query("SELECT transaction_id, user_id, product_id, quantity, total_amount, payment_status, payment_method, transaction_date FROM Financials");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $html .= "<h3>All Financial Transactions</h3>";
        if (count($rows) === 0) {
            $html .= "<p>No financial records found.</p>";
        } else {
            $html .= "<table border='1' width='100%' cellspacing='0' cellpadding='6'>
                      <thead>
                        <tr>
                          <th>ID</th><th>User ID</th><th>Product ID</th><th>Qty</th>
                          <th>Amount</th><th>Status</th><th>Method</th><th>Date</th>
                        </tr>
                      </thead><tbody>";
            foreach ($rows as $r) {
                $html .= "<tr>
                            <td>{$r['transaction_id']}</td>
                            <td>{$r['user_id']}</td>
                            <td>{$r['product_id']}</td>
                            <td>{$r['quantity']}</td>
                            <td>R{$r['total_amount']}</td>
                            <td>{$r['payment_status']}</td>
                            <td>{$r['payment_method']}</td>
                            <td>{$r['transaction_date']}</td>
                          </tr>";
            }
            $html .= "</tbody></table><br>";
        }
    }

    // GENERATE PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("shopink_{$type}_report_{$month}.pdf", ['Attachment' => false]);
    exit;
}
