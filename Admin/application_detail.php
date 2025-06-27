<?php
include '../database/db.php';

if (!isset($_GET['id'])) {
    die("Application ID is required.");
}

$application_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT a.*, u.email_address, u.first_name, u.last_name 
    FROM seller_applications a 
    JOIN users u ON a.user_id = u.user_id 
    WHERE a.application_id = ?
");
$stmt->execute([$application_id]);
$application = $stmt->fetch();

if (!$application) {
    die("Application not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    
    $updateApp = $pdo->prepare("UPDATE seller_applications SET application_status = ? WHERE application_id = ?");
    $updateApp->execute([$status, $application_id]);
    if ($status === 'approved') {
        $updateUser = $pdo->prepare("UPDATE users SET role = 'seller' WHERE user_id = ?");
        $updateUser->execute([$application['user_id']]);
    }

    header("Location: view_applications.php");
    exit;
}


function documentRow($label, $filename) {
    $uploadPath = "../uploads/seller_docs/" . $filename;

    echo "<!-- DEBUG: Checking $uploadPath -->"; // Add this line temporarily

    if ($filename && file_exists($uploadPath)) {
        echo "<tr><th>$label</th><td><a href='$uploadPath' target='_blank'>View</a></td></tr>";
    } else {
        echo "<tr><th>$label</th><td><em>Not uploaded</em></td></tr>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Application Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .label-cell { width: 200px; }
    </style>
</head>
<body class="bg-light py-4">
<div class="container">
    <h2 class="mb-4">Application Detail: #<?= htmlspecialchars($application_id) ?></h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Seller Info</div>
        <div class="card-body">
            <table class="table">
                <tr><th class="label-cell">Name</th><td><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($application['email_address']) ?></td></tr>
                <tr><th>Seller Type</th><td><?= htmlspecialchars(ucfirst($application['seller_type'])) ?></td></tr>
                <tr><th>Tax Number</th><td><?= htmlspecialchars($application['tax_number']) ?></td></tr>
                <tr><th>Description</th><td><?= htmlspecialchars($application['product_description']) ?></td></tr>
                <tr><th>Status</th><td><?= ucfirst($application['application_status']) ?></td></tr>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Uploaded Documents</div>
        <div class="card-body">
            <table class="table table-bordered">
                <?php
                    documentRow("ID Document", $application['id_document']);
                    documentRow("Proof of Address", $application['proof_of_address']);
                    documentRow("Bank Details", $application['bank_details']);
                    documentRow("Selfie with ID", $application['selfie_with_id']);
                    if ($application['business_license']) {
                        documentRow("Business License", $application['business_license']);
                    }
                ?>
            </table>
        </div>
    </div>

    <form method="POST" class="d-flex gap-2">
        <button name="status" value="approved" class="btn btn-success">Approve</button>
        <button name="status" value="rejected" class="btn btn-danger">Reject</button>
        <a href="view_applications.php" class="btn btn-outline-secondary">Back</a>
    </form>
</div>
</body>
</html>

