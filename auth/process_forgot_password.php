<?php
require_once('../config/database.php');
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store reset token
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$token, $expiry, $email]);

        // Create reset link
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/hosp/reset-password.php?token=" . $token;

        // Send email (you'll need to configure your email settings)
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: " . $resetLink;
        $headers = "From: noreply@virtualicu.com";

        if(mail($to, $subject, $message, $headers)) {
            echo json_encode(['success' => true, 'message' => 'Reset link sent successfully']);
        } else {
            throw new Exception('Failed to send email');
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}