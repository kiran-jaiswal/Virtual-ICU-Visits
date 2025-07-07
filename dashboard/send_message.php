<?php
session_start();
require_once('../config/database.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $_SESSION['user_id'];
    $messageContent = $data['message'];

    if ($messageContent) {
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, sender, content) VALUES (?, ?, ?)");
        $stmt->execute([$userId, 'Family Member', $messageContent]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Message content is empty']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}