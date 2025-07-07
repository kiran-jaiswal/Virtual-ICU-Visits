<?php
session_start();
require_once('../../config/database.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("
        INSERT INTO messages (user_id, sender, content) 
        VALUES (?, ?, ?)
    ");
    
    $stmt->execute([
        $data['recipient_id'],
        $_SESSION['full_name'],
        $data['content']
    ]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error sending reply']);
}