<?php
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    try {
        // Validate passwords match
        if ($password !== $confirm_password) {
            header('Location: ../reset-password.php?token=' . $token . '&error=password_mismatch');
            exit();
        }

        // Check token validity
        $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            // Hash new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Update password and clear reset token
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
            $stmt->execute([$hashed_password, $user['id']]);
            
            // Redirect to login with success message
            header('Location: ../login.php?success=password_reset');
            exit();
        } else {
            header('Location: ../forgot-password.php?error=invalid_token');
            exit();
        }
    } catch (Exception $e) {
        header('Location: ../reset-password.php?token=' . $token . '&error=system_error');
        exit();
    }
} else {
    header('Location: ../login.php');
    exit();
}