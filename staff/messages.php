<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Location: ../login.php');
    exit();
}

// Fetch all messages
$stmt = $pdo->prepare("
    SELECT m.*, u.full_name 
    FROM messages m
    JOIN users u ON m.user_id = u.id
    ORDER BY m.created_at DESC
");
$stmt->execute();
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50" style="font-family: Alkatra, system-ui;">
    <nav class="bg-white shadow-lg mb-8">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="dashboard.php" class="text-xl font-bold text-blue-600">Virtual ICU</a>
                    <div class="hidden md:flex items-center ml-10 space-x-4">
                        <a href="dashboard.php" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                        <a href="messages.php" class="text-blue-600 px-3 py-2 rounded-md">
                            <i class="fas fa-envelope mr-2"></i>Messages
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4"><?php echo $_SESSION['full_name']; ?></span>
                    <a href="../auth/logout.php" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Messages</h2>
            
            <!-- Message List -->
            <div class="space-y-4 mb-6">
                <?php foreach ($messages as $message): ?>
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="font-semibold"><?php echo htmlspecialchars($message['sender']); ?></span>
                                <span class="text-sm text-gray-500 ml-2">
                                    <?php echo date('M d, Y h:i A', strtotime($message['created_at'])); ?>
                                </span>
                            </div>
                            <button onclick="showReplyForm(<?php echo $message['id']; ?>)" 
                                    class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-reply mr-1"></i>Reply
                            </button>
                        </div>
                        <p class="text-gray-700"><?php echo htmlspecialchars($message['content']); ?></p>
                        
                        <!-- Reply Form -->
                        <div id="replyForm<?php echo $message['id']; ?>" class="hidden mt-4">
                            <form onsubmit="sendReply(event, <?php echo $message['id']; ?>, <?php echo $message['user_id']; ?>)" 
                                  class="flex gap-2">
                                <input type="text" 
                                       class="flex-1 border rounded px-3 py-2" 
                                       placeholder="Type your reply..."
                                       id="replyContent<?php echo $message['id']; ?>">
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Send
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    function showReplyForm(messageId) {
        const form = document.getElementById(`replyForm${messageId}`);
        form.classList.toggle('hidden');
    }

    function sendReply(event, messageId, recipientId) {
        event.preventDefault();
        const content = document.getElementById(`replyContent${messageId}`).value;
        if (!content.trim()) return;

        fetch('../api/messages/reply.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                content: content,
                recipient_id: recipientId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
    </script>
</body>
</html>