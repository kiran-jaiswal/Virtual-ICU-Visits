<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];
    
    try {
        // Get current status
        $stmt = $pdo->prepare("SELECT status FROM patients WHERE id = ?");
        $stmt->execute([$patient_id]);
        $current_status = $stmt->fetchColumn();
        
        // Toggle status and update timestamp
        $new_status = $current_status === 'active' ? 'discharged' : 'active';
        
        $stmt = $pdo->prepare("UPDATE patients SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$new_status, $patient_id]);
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>