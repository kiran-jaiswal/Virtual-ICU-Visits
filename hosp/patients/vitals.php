<?php
session_start();
require_once('../config/database.php');
require_once('../components/navbar.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Vitals - Staff Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-gray-50" style="font-family: Sedgwick Ave Display, cursive;">
    <?php ?>
    
    <div class="pt-16 max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Heart Rate -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Heart Rate</h3>
                    <i class="fas fa-heartbeat text-red-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800">75 <span class="text-sm text-gray-500">bpm</span></div>
                <canvas id="heartRateChart" class="mt-4"></canvas>
            </div>

            <!-- Blood Pressure -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Blood Pressure</h3>
                    <i class="fas fa-stethoscope text-blue-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800">120/80</div>
                <canvas id="bpChart" class="mt-4"></canvas>
            </div>

            <!-- Temperature -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Temperature</h3>
                    <i class="fas fa-thermometer-half text-orange-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800">37.2°C</div>
                <canvas id="tempChart" class="mt-4"></canvas>
            </div>

            <!-- Oxygen Saturation -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">SpO2</h3>
                    <i class="fas fa-lungs text-green-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-800">98%</div>
                <canvas id="spo2Chart" class="mt-4"></canvas>
            </div>
        </div>

        <!-- Patient List -->
        <div class="mt-8 bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Patient Monitoring</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Room
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Last Updated
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Fetch patients from database
                            $stmt = $pdo->query("SELECT * FROM patients WHERE status = 'active' ORDER BY room_number");
                            while ($patient = $stmt->fetch()) {
                                ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($patient['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($patient['room_number']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Stable
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y H:i', strtotime($patient['updated_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="viewDetails(<?php echo $patient['id']; ?>)" 
                                                class="text-blue-600 hover:text-blue-900">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize charts
        function initializeCharts() {
            const chartConfig = {
                type: 'line',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };

            // Heart Rate Chart
            new Chart(document.getElementById('heartRateChart'), {
                ...chartConfig,
                data: {
                    labels: ['1h', '2h', '3h', '4h', '5h', 'Now'],
                    datasets: [{
                        data: [72, 75, 73, 74, 75, 75],
                        borderColor: 'rgb(239, 68, 68)',
                        tension: 0.4
                    }]
                }
            });

            // Similar charts for BP, Temperature, and SpO2
            // ... (implement other charts similarly)
        }

        function viewDetails(patientId) {
            window.location.href = `patient-details.php?id=${patientId}`;
        }

        document.addEventListener('DOMContentLoaded', initializeCharts);
    </script>
</body>
</html>