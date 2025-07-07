<?php
session_start();
require_once('../../config/database.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'family') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['content']) || empty($data['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    $stmt = $pdo->prepare("
        INSERT INTO messages (user_id, sender, content) 
        VALUES (?, ?, ?)
    ");
    
    $stmt->execute([
        $data['user_id'],
        $_SESSION['full_name'],
        $data['content']
    ]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error sending message: ' . $e->getMessage()]);
}