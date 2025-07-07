<?php
session_start();
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $user_type = $_POST['user_type'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header('Location: ../register.php');
        exit();
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email already registered";
        header('Location: ../register.php');
        exit();
    }

    // Hash password and insert user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, user_type) VALUES (?, ?, ?, ?)");
    
    if ($stmt->execute([$full_name, $email, $hashed_password, $user_type])) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header('Location: ../login.php');
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header('Location: ../register.php');
    }
    exit();
}