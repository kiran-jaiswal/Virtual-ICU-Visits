<?php
session_start();
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate reset token (in a real app, use a proper token generation method)
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
        $stmt->execute([$token, $expires, $user['id']]);
        
        // In a real app, send email with reset link here
        $_SESSION['reset_message'] = "Password reset link has been sent to your email";
    } else {
        $_SESSION['reset_message'] = "If this email exists, a reset link will be sent";
    }
    header('Location: forgot_password.php');
    exit();
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
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Forgot Password</h2>
            
            <?php if (isset($_SESSION['reset_message'])): ?>
                <div class="mb-4 p-4 bg-blue-100 text-blue-700 rounded">
                    <?php echo htmlspecialchars($_SESSION['reset_message']); ?>
                    <?php unset($_SESSION['reset_message']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                    Send Reset Link
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <a href="login.php" class="text-blue-600 hover:underline">Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>