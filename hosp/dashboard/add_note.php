<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_id']) && isset($_POST['note'])) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO patient_notes (patient_id, staff_id, content)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $_POST['patient_id'],
            $_SESSION['user_id'],
            $_POST['note']
        ]);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
}
?>