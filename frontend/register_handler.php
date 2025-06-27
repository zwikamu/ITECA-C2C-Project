<?php
session_start();
include '../database/db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Clear previous registration error
unset($_SESSION['register_error']);

// Handle POST only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Optional debug
    file_put_contents('debug_register.log', print_r($_POST, true));

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Store user inputs
    $_SESSION['old_register'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email
    ];
    $_SESSION['auth_tab'] = 'register'; // <-- important

    // Validations
    if (!$first_name || !$last_name || !$email || !$password || !$confirm_password) {
        $_SESSION['register_error'] = "All fields are required.";
        header("Location: ../index.php?auth_error=1");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Invalid email format.";
        header("Location: ../index.php?auth_error=1");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match.";
        header("Location: ../index.php?auth_error=1");
        exit;
    }

    // Check if user exists
    $checkStmt = $pdo->prepare("SELECT 1 FROM Users WHERE email_address = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        $_SESSION['register_error'] = "Email is already registered.";
        header("Location: ../index.php?auth_error=1");
        exit;
    }

    // Create account
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insertStmt = $pdo->prepare("
        INSERT INTO Users (email_address, password, first_name, last_name, role, is_verified, seller_application_status)
        VALUES (?, ?, ?, ?, 'buyer', 0, 'none')
    ");

    try {
        $insertStmt->execute([$email, $hashedPassword, $first_name, $last_name]);
    } catch (PDOException $e) {
        $_SESSION['register_error'] = "Unexpected error. Please try again.";
        $_SESSION['auth_tab'] = 'register'; // ensure it opens correct tab
        header("Location: ../index.php?auth_error=1");
        exit;
    }

    // Success
    unset($_SESSION['old_register']);
    $_SESSION['success_message'] = "Account created successfully. Please log in.";
    $_SESSION['auth_tab'] = 'login'; // <-- so login tab shows on success
    header("Location: ../index.php?auth_success=1");
    exit;
}

// Not a POST request
header("Location: ../index.php");
exit;
