<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'family') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientId = $_POST['patient_id'];
    $visitDate = $_POST['visit_date'];
    $meetingLink = $_POST['meeting_link']; // Ensure this is included
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO visits (patient_id, visitor_id, visit_date, meeting_link, status) VALUES (?, ?, ?, ?, 'scheduled')");
    $stmt->execute([$patientId, $userId, $visitDate, $meetingLink]); // Include $meetingLink

    $_SESSION['success'] = "Visit scheduled successfully!";
    header('Location: family.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Visit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50" style="font-family: Alkatra, system-ui;">
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Schedule a Visit</h2>
        <form method="POST">
            <label for="visit_date" class="block text-sm font-medium text-gray-700">Visit Date</label>
            <input type="datetime-local" name="visit_date" id="visit_date" class="mt-1 block w-full border rounded px-3 py-2" required>
            <label for="meeting_link" class="block text-sm font-medium text-gray-700 mt-4">Meeting Link</label>
            <input type="url" name="meeting_link" id="meeting_link" class="mt-1 block w-full border rounded px-3 py-2" required>
            <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Schedule</button>
        </form>
    </div>
</body>
</html>