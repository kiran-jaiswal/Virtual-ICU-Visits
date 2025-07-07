<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_id = $_POST['visit_id'] ?? null;
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'approve':
                $stmt = $pdo->prepare("UPDATE visits SET status = 'approved', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$visit_id]);
                break;
            
            case 'reject':
                $stmt = $pdo->prepare("UPDATE visits SET status = 'rejected', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$visit_id]);
                break;
            
            case 'complete':
                $stmt = $pdo->prepare("UPDATE visits SET status = 'completed', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$visit_id]);
                break;
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}
?>