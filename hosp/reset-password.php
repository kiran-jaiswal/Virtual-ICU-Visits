<?php
require_once('config/database.php');
session_start();

// Add time limit (15 minutes)
if (!isset($_SESSION['reset_email']) || (isset($_SESSION['reset_time']) && time() - $_SESSION['reset_time'] > 900)) {
    unset($_SESSION['reset_email']);
    unset($_SESSION['reset_time']);
    header('Location: forgot-password.php?error=session_expired');
    exit();
}

if (!isset($_SESSION['reset_email'])) {
    header('Location: forgot-password.php?error=invalid_request');
    exit();
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed_password, $email]);
        
        unset($_SESSION['reset_email']);
        header('Location: login.php?reset=success');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Virtual ICU Visits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .bg-gradient {
            background: linear-gradient(to bottom right, #1e40af, #60a5fa);
        }
        .input-glow:focus {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            border-color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gradient min-h-screen flex items-center justify-center p-4" style="font-family: Alkatra, system-ui;">
    <div class="w-full max-w-md">
        <div class="bg-white p-8 rounded-2xl shadow-2xl glass-effect">
            <?php if (isset($error)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Reset Your Password</h2>
                <p class="text-gray-600 mt-2">Enter your new password below</p>
            </div>

            <form method="POST">
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">New Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none input-glow">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           class="w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none input-glow">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</body>
</html>