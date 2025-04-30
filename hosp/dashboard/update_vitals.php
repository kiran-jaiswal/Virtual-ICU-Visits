<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Location: ../login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $heart_rate = $_POST['heart_rate'];
    $systolic = $_POST['systolic'];
    $diastolic = $_POST['diastolic'];
    $spo2 = $_POST['spo2'];
    $temperature = $_POST['temperature'];

    try {
        $stmt = $pdo->prepare("INSERT INTO vitals (patient_id, heart_rate, systolic, diastolic, spo2, temperature) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$patient_id, $heart_rate, $systolic, $diastolic, $spo2, $temperature]);
        $_SESSION['success'] = "Vitals updated successfully";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating vitals";
    }
    header('Location: patient_monitor.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Vitals - Virtual ICU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50" style="font-family: Alkatra, system-ui;">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-blue-600">Virtual ICU Visits</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="staff.php" class="text-gray-600 hover:text-gray-800">Dashboard</a>
                    <a href="patient_monitor.php" class="text-gray-600 hover:text-gray-800">Patient Monitor</a>
                    <span class="text-gray-700">Dr. <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    <a href="../auth/logout.php" class="text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Update Patient Vitals</h2>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="patient">
                        Select Patient
                    </label>
                    <select name="patient_id" id="patient" required class="w-full border rounded-md p-2">
                        <?php
                        $stmt = $pdo->query("SELECT id, full_name, bed_number FROM patients WHERE status = 'active' ORDER BY bed_number");
                        while ($patient = $stmt->fetch()) {
                            echo "<option value='" . $patient['id'] . "'>Bed " . htmlspecialchars($patient['bed_number']) . 
                                 " - " . htmlspecialchars($patient['full_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="heart_rate">
                            Heart Rate (BPM)
                        </label>
                        <input type="number" name="heart_rate" id="heart_rate" required
                               class="w-full border rounded-md p-2" min="0" max="300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Blood Pressure (mmHg)
                        </label>
                        <div class="flex space-x-2">
                            <input type="number" name="systolic" placeholder="Systolic" required
                                   class="w-1/2 border rounded-md p-2" min="0" max="300">
                            <input type="number" name="diastolic" placeholder="Diastolic" required
                                   class="w-1/2 border rounded-md p-2" min="0" max="300">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="spo2">
                            SpO2 (%)
                        </label>
                        <input type="number" name="spo2" id="spo2" required
                               class="w-full border rounded-md p-2" min="0" max="100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="temperature">
                            Temperature (°C)
                        </label>
                        <input type="number" name="temperature" id="temperature" required
                               class="w-full border rounded-md p-2" step="0.1" min="30" max="45">
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="patient_monitor.php" 
                       class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Update Vitals
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>