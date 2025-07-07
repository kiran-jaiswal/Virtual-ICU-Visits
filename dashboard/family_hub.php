<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'family') {
    header('Location: ../login.php');
    exit();
}

// Fetch daily updates with staff information
$updatesStmt = $pdo->prepare("
    SELECT du.*, u.full_name as staff_name
    FROM daily_updates du
    JOIN users u ON du.user_id = u.id
    ORDER BY du.created_at DESC
");
$updatesStmt->execute();
$updates = $updatesStmt->fetchAll();

// Fetch messages
$messagesStmt = $pdo->prepare("
    SELECT m.*, u.full_name as sender_name 
    FROM messages m
    JOIN users u ON m.user_id = u.id
    ORDER BY m.created_at DESC
");
$messagesStmt->execute();
$messages = $messagesStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Communication Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-50 to-blue-100 min-h-screen" style="font-family: Alkatra, system-ui;">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Daily Updates -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-3xl font-bold mb-4 text-blue-800">Daily Updates</h2>
            <div class="space-y-4" id="updates">
                <?php foreach ($updates as $update): ?>
                    <div class="p-4 border-l-4 border-blue-500 bg-blue-50 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="text-sm text-gray-600">Updated by <?php echo htmlspecialchars($update['staff_name']); ?></p>
                            </div>
                            <span class="text-sm text-gray-500">
                                <?php echo date('M d, Y h:i A', strtotime($update['created_at'])); ?>
                            </span>
                        </div>
                        <p class="text-gray-700"><?php echo htmlspecialchars($update['content']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Message Center -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-3xl font-bold mb-4 text-blue-800">Message Center</h2>
                    <div class="h-96 overflow-y-auto border rounded p-4 mb-4 bg-blue-50" id="messageThread">
                        <?php foreach ($messages as $message): ?>
                            <div class="mb-2 p-2 rounded bg-white">
                                <div class="flex justify-between items-start">
                                    <span class="font-bold text-blue-700"><?php echo htmlspecialchars($message['sender']); ?></span>
                                    <span class="text-xs text-gray-500"><?php echo date('M d, Y h:i A', strtotime($message['created_at'])); ?></span>
                                </div>
                                <p class="mt-1"><?php echo htmlspecialchars($message['content']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Message Form -->
                    <form id="messageForm" class="flex gap-2">
                        <select id="staffSelect" class="border rounded px-3 py-2" required>
                            <option value="">Select Staff Member</option>
                            <?php
                            $staffStmt = $pdo->prepare("SELECT id, full_name FROM users WHERE user_type = 'staff'");
                            $staffStmt->execute();
                            while ($staff = $staffStmt->fetch()) {
                                echo "<option value='" . $staff['id'] . "'>" . htmlspecialchars($staff['full_name']) . "</option>";
                            }
                            ?>
                        </select>
                        <input type="text" id="messageInput" class="flex-1 border rounded px-3 py-2" placeholder="Type your message...">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            Send
                        </button>
                    </form>
                    
                    <!-- Update JavaScript -->
                    <script>
                    document.getElementById('messageForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        const messageInput = document.getElementById('messageInput');
                        const staffSelect = document.getElementById('staffSelect');
                        const message = messageInput.value.trim();
                        const staffId = staffSelect.value;
                    
                        if (!message || !staffId) {
                            alert('Please select a staff member and enter a message');
                            return;
                        }
                    
                        fetch('../api/messages/send.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                user_id: staffId,
                                content: message
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                messageInput.value = '';
                                staffSelect.value = '';
                                location.reload();
                            } else {
                                alert('Failed to send message: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to send message. Please try again.');
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>