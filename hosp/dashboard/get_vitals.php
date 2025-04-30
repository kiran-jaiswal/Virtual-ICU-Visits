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
        // Get latest vitals
        $stmt = $pdo->prepare("
            SELECT * FROM vitals 
            WHERE patient_id = ? 
            ORDER BY recorded_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$_GET['patient_id']]);
        $vitals = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get history for charts
        $stmt = $pdo->prepare("
            SELECT heart_rate, recorded_at 
            FROM vitals 
            WHERE patient_id = ? 
            ORDER BY recorded_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$_GET['patient_id']]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'heart_rate' => $vitals['heart_rate'] ?? '--',
            'systolic' => $vitals['systolic'] ?? '--',
            'diastolic' => $vitals['diastolic'] ?? '--',
            'spo2' => $vitals['spo2'] ?? '--',
            'temperature' => $vitals['temperature'] ?? '--',
            'heart_rate_history' => array_column(array_reverse($history), 'heart_rate')
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
    }
}
?>