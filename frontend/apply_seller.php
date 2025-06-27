<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../database/db.php');

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = '../uploads/seller_docs/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    function saveFile($key, $prefix, $user_id, $uploadDir) {
        if (!isset($_FILES[$key]) || $_FILES[$key]['error'] !== UPLOAD_ERR_OK) return null;
        $ext = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
        $fileName = $user_id . '_' . $prefix . '.' . $ext;
        $dest = $uploadDir . $fileName;
        move_uploaded_file($_FILES[$key]['tmp_name'], $dest);
        return $fileName;
    }

    $seller_type = $_POST['seller_type'] ?? '';
    $tax_number = $_POST['tax_number'] ?? null;
    $product_description = $_POST['product_description'] ?? 'Auto-generated placeholder';

    if (!in_array($seller_type, ['individual', 'business'])) {
        die("Invalid seller type. Please go back and select a valid option.");
    }

    $id_document = saveFile('id_document', 'id', $user_id, $uploadDir);
    $proof_of_address = saveFile('proof_of_address', 'proof', $user_id, $uploadDir);
    $bank_details = saveFile('bank_details', 'bank', $user_id, $uploadDir);
    $selfie_with_id = ($seller_type === 'individual') ? saveFile('selfie_with_id', 'selfie', $user_id, $uploadDir) : null;
    $business_license = ($seller_type === 'business') ? saveFile('business_license', 'license', $user_id, $uploadDir) : null;

    $stmt = $pdo->prepare("INSERT INTO seller_applications
        (user_id, seller_type, id_document, proof_of_address, bank_details, selfie_with_id, business_license, tax_number, product_description, application_status, submitted_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");

    $stmt->execute([
        $user_id, $seller_type, $id_document, $proof_of_address, $bank_details,
        $selfie_with_id, $business_license, $tax_number, $product_description
    ]);

    echo "<div style='padding:2rem; text-align:center; font-family:sans-serif;'>
        <h2>Thank you!</h2>
        <p>Your seller application has been submitted successfully.</p>
        <a href='user_welcome.php' class='btn btn-primary mt-3'>Back to ShopInk</a>
        </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apply to Be a Seller</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="../styling/styles/styles.css">
  <style>
    .form-section { max-width: 720px; margin: 50px auto; background:rgb(216, 218, 221); padding: 30px; border-radius: 10px; }
  
    nav .nav-link.active {
      background-color: #4c5cc3;
      color: white;
    }
  </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-2">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold text-primary" href="../index.php">ShopInk</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
        aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>


      <div class="collapse navbar-collapse" id="mainNavbar">

      


      </div>
    </div>
  </nav>
<div class="form-section">
  <h2 class="text-center mb-4 text-dark">Apply to Become a Seller</h2>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Seller Type</label>
      <select name="seller_type" class="form-select" required>
        <option value="">-- Select --</option>
        <option value="individual">Individual</option>
        <option value="business">Business</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Seller Description</label>
      <textarea name="product_description" class="form-control" required></textarea>
    </div>

    <div class="mb-3"><label>ID Document</label><input type="file" name="id_document" class="form-control" required></div>
    <div class="mb-3"><label>Proof of Address</label><input type="file" name="proof_of_address" class="form-control" required></div>
    <div class="mb-3"><label>Bank Details</label><input type="file" name="bank_details" class="form-control" required></div>
    
    <div id="individualFields" style="display:none">
      <div class="mb-3"><label>Selfie with ID</label><input type="file" name="selfie_with_id" class="form-control"></div>
    </div>

    <div id="businessFields" style="display:none">
      <div class="mb-3"><label>Business License</label><input type="file" name="business_license" class="form-control"></div>
      <div class="mb-3"><label>Tax Number (optional)</label><input type="text" name="tax_number" class="form-control"></div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Submit Application</button>
  </form>
</div>

<script>
document.querySelector("select[name='seller_type']").addEventListener("change", function() {
    const ind = document.getElementById("individualFields");
    const bus = document.getElementById("businessFields");
    if (this.value === "individual") {
        ind.style.display = "block";
        bus.style.display = "none";
    } else if (this.value === "business") {
        ind.style.display = "none";
        bus.style.display = "block";
    } else {
        ind.style.display = "none";
        bus.style.display = "none";
    }
});
</script>

</body>
</html>
