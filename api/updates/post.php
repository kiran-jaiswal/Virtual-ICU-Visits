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
        INSERT INTO daily_updates (user_id, content) 
        VALUES (?, ?)
    ");
    
    $stmt->execute([$_SESSION['user_id'], $data['content']]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error saving update']);
}