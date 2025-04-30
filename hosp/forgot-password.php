<?php
require_once('config/database.php');
session_start(); // Add this line at the top

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_time'] = time(); // Store current time
        header('Location: forgot-password.php?sent=1');
        exit();
    } else {
        header('Location: forgot-password.php?error=email_not_found');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <?php if (isset($_GET['error'])): ?>
            <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
                <?php if ($_GET['error'] == 'invalid_token'): ?>
                    Invalid or expired reset link. Please request a new one.
                <?php elseif ($_GET['error'] == 'email_not_found'): ?>
                    Email not found. Please try again.
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['sent'])): ?>
            <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded">
                <a href="reset-password.php" class="text-blue-600 underline">
                    Click here to reset your password
                </a>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" required 
                       class="w-full p-2 border rounded">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">
                Request Reset
            </button>
        </form>
        
        <div class="mt-4 text-center">
            <a href="login.php" class="text-blue-500">Back to Login</a>
        </div>
    </div>
</body>
</html>