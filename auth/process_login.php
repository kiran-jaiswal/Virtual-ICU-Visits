<?php
session_start();
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['full_name'] = $user['full_name'];

        // Redirect based on user type
        if ($user['user_type'] === 'family') {
            header('Location: ../dashboard/family.php');
        } else {
            header('Location: ../dashboard/staff.php');
        }
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header('Location: ../login.php');
        exit();
    }
}