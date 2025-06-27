<?php
session_start();
include '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $_SESSION['old_email'] = $email;

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email_address = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        unset($_SESSION['error'], $_SESSION['old_email']);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../frontend/user_welcome.php");
        exit;
    } else {
       $_SESSION['error'] = "Invalid email or password.";
        $_SESSION['auth_tab'] = 'login';
        $_SESSION['old_email'] = $email;
        header("Location: ../index.php?auth_error=1");
        exit;

    }
}

if ($validLogin) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    $redirect = $_POST['redirect_to'] ?? 'user_welcome.php';
    header("Location: $redirect");
    exit;
}

?>
