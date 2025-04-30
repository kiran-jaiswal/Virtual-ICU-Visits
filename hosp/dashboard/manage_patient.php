<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'] ?? null;
    $full_name = $_POST['full_name'];
    $bed_number = $_POST['bed_number'];
    $admission_date = $_POST['admission_date'];

    try {
        if ($patient_id) {
            // Update existing patient
            $stmt = $pdo->prepare("UPDATE patients SET full_name = ?, bed_number = ?, admission_date = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$full_name, $bed_number, $admission_date, $patient_id]);
        } else {
            // Add new patient
            $stmt = $pdo->prepare("INSERT INTO patients (full_name, bed_number, admission_date, status) VALUES (?, ?, ?, 'active')");
            $stmt->execute([$full_name, $bed_number, $admission_date]);
        }
        $_SESSION['success'] = $patient_id ? 'Patient updated successfully' : 'Patient added successfully';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error occurred';
    }
}

header('Location: staff.php');
exit();
?>