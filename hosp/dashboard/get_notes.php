<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (isset($_GET['patient_id'])) {
    try {
        $stmt = $pdo->prepare("
            SELECT n.*, u.full_name as staff_name
            FROM patient_notes n
            JOIN users u ON n.staff_id = u.id
            WHERE n.patient_id = ?
            ORDER BY n.created_at DESC
            LIMIT 10
        ");
        $stmt->execute([$_GET['patient_id']]);
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($notes);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
    }
}
?>