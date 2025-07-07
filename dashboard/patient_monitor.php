<?php
session_start();
require_once('../config/database.php');

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
    <title>Patient Monitor - Virtual ICU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Card Hover Effect */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        /* Button Hover Effect */
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Fade-In Animation */
        .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }

        /* Chart Container */
        .chart-container {
            height: 120px;
        }
    </style>
</head>
<body class="bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen font-sans" style="font-family: Alkatra, system-ui;">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600">Virtual ICU Visits</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="staff.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Dashboard</a>
                    <a href="patient_monitor.php" class="text-blue-600 font-semibold border-b-2 border-blue-600">Patient Monitor</a>
                    <span class="text-blue-700 font-semibold">Dr. <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    <a href="../auth/logout.php" class="text-red-600 hover:text-red-800 font-medium transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-700 to-blue-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 text-center fade-in-section">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Patient Monitor</h2>
            <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                Real-time vital signs monitoring and patient notes for comprehensive ICU care.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Patient Selection -->
        <div class="flex justify-between items-center mb-8 fade-in-section">
            <div class="relative w-full md:w-64">
                <select id="patientSelect" class="w-full border rounded-lg px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                    <?php
                    $stmt = $pdo->query("SELECT id, full_name, bed_number FROM patients WHERE status = 'active' ORDER BY bed_number");
                    while ($patient = $stmt->fetch()) {
                        echo "<option value='" . $patient['id'] . "'>Bed " . htmlspecialchars($patient['bed_number']) . 
                             " - " . htmlspecialchars($patient['full_name']) . "</option>";
                    }
                    ?>
                </select>
                <i class="fas fa-user-md absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <a href="update_vitals.php" class="btn bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i> Update Vitals
            </a>
        </div>

        <!-- Vital Signs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Heart Rate -->
            <div class="card bg-white p-6 rounded-2xl shadow-lg glass-effect fade-in-section">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Heart Rate</h3>
                    <i class="fas fa-heartbeat text-3xl text-red-500"></i>
                </div>
                <div id="heartRate" class="text-4xl font-bold text-gray-800">-- BPM</div>
                <div class="chart-container mt-4">
                    <canvas id="heartRateChart"></canvas>
                </div>
            </div>

            <!-- Blood Pressure -->
            <div class="card bg-white p-6 rounded-2xl shadow-lg glass-effect fade-in-section" style="transition-delay: 0.2s">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Blood Pressure</h3>
                    <i class="fas fa-stethoscope text-3xl text-blue-500"></i>
                </div>
                <div id="bloodPressure" class="text-4xl font-bold text-gray-800">--/-- mmHg</div>
                <div class="chart-container mt-4">
                    <canvas id="bpChart"></canvas>
                </div>
            </div>

            <!-- Oxygen Saturation -->
            <div class="card bg-white p-6 rounded-2xl shadow-lg glass-effect fade-in-section" style="transition-delay: 0.4s">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">SpO2</h3>
                    <i class="fas fa-lungs text-3xl text-green-500"></i>
                </div>
                <div id="spo2" class="text-4xl font-bold text-gray-800">--%</div>
                <div class="chart-container mt-4">
                    <canvas id="spo2Chart"></canvas>
                </div>
            </div>

            <!-- Temperature -->
            <div class="card bg-white p-6 rounded-2xl shadow-lg glass-effect fade-in-section" style="transition-delay: 0.6s">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Temperature</h3>
                    <i class="fas fa-thermometer-half text-3xl text-yellow-500"></i>
                </div>
                <div id="temperature" class="text-4xl font-bold text-gray-800">--°C</div>
                <div class="chart-container mt-4">
                    <canvas id="tempChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Patient Notes -->
        <div class="bg-white p-8 rounded-2xl shadow-lg glass-effect fade-in-section">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Patient Notes</h3>
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                     alt="Patient" class="w-12 h-12 rounded-full object-cover">
            </div>
            <div id="patientNotes" class="space-y-4 mb-6">
                <!-- Notes will be populated here -->
            </div>
            <div class="flex items-center space-x-4">
                <textarea id="newNote" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add a new note..."></textarea>
                <button onclick="addNote()" class="btn bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Add Note
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="fade-in-section">
                    <h3 class="text-2xl font-bold mb-4">Virtual ICU Visits</h3>
                    <p class="text-gray-400 leading-relaxed">Empowering healthcare with real-time monitoring and connectivity.</p>
                </div>
                <div class="fade-in-section">
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="staff.php" class="text-gray-400 hover:text-blue-400 transition-colors">Dashboard</a></li>
                        <li><a href="patient_monitor.php" class="text-gray-400 hover:text-blue-400 transition-colors">Patient Monitor</a></li>
                        <li><a href="../auth/logout.php" class="text-gray-400 hover:text-blue-400 transition-colors">Logout</a></li>
                    </ul>
                </div>
                <div class="fade-in-section">
                    <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                    <p class="text-gray-400">Email: support@virtualicu.com</p>
                    <p class="text-gray-400">Phone: (123) 456-7890</p>
                </div>
            </div>
            <div class="mt-8 text-center text-gray-400 fade-in-section">
                <p>© 2025 Virtual ICU Visits. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        let charts = {};

        function initializeCharts() {
            const config = {
                type: 'line',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        y: { 
                            beginAtZero: false,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: { 
                            display: false,
                            grid: { display: false }
                        }
                    },
                    elements: {
                        point: { radius: 3 }
                    }
                }
            };

            charts.heartRate = new Chart(document.getElementById('heartRateChart'), {
                ...config,
                data: {
                    labels: Array(10).fill(''),
                    datasets: [{
                        data: [],
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                }
            });

            charts.bloodPressure = new Chart(document.getElementById('bpChart'), {
                ...config,
                data: {
                    labels: Array(10).fill(''),
                    datasets: [
                        {
                            data: [],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            label: 'Systolic'
                        },
                        {
                            data: [],
                            borderColor: 'rgb(100, 181, 246)',
                            backgroundColor: 'rgba(100, 181, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            label: 'Diastolic'
                        }
                    ]
                }
            });

            charts.spo2 = new Chart(document.getElementById('spo2Chart'), {
                ...config,
                data: {
                    labels: Array(10).fill(''),
                    datasets: [{
                        data: [],
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                }
            });

            charts.temperature = new Chart(document.getElementById('tempChart'), {
                ...config,
                data: {
                    labels: Array(10).fill(''),
                    datasets: [{
                        data: [],
                        borderColor: 'rgb(234, 179, 8)',
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                }
            });
        }

        function updateVitals() {
            const patientId = document.getElementById('patientSelect').value;
            
            fetch(`get_vitals.php?patient_id=${patientId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('heartRate').textContent = `${data.heart_rate || '--'} BPM`;
                    document.getElementById('bloodPressure').textContent = `${data.systolic || '--'}/${data.diastolic || '--'} mmHg`;
                    document.getElementById('spo2').textContent = `${data.spo2 || '--'}%`;
                    document.getElementById('temperature').textContent = `${data.temperature || '--'}°C`;

                    updateChart(charts.heartRate, data.heart_rate_history || []);
                    updateChart(charts.bloodPressure, [
                        data.systolic_history || [],
                        data.diastolic_history || []
                    ]);
                    updateChart(charts.spo2, data.spo2_history || []);
                    updateChart(charts.temperature, data.temperature_history || []);
                });
        }

        function updateChart(chart, newData) {
            if (Array.isArray(newData[0])) {
                chart.data.datasets[0].data = newData[0];
                chart.data.datasets[1].data = newData[1];
            } else {
                chart.data.datasets[0].data = newData;
            }
            chart.update();
        }

        function addNote() {
            const note = document.getElementById('newNote').value;
            const patientId = document.getElementById('patientSelect').value;

            fetch('add_note.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `patient_id=${patientId}&note=${encodeURIComponent(note)}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    document.getElementById('newNote').value = '';
                    loadNotes();
                }
            });
        }

        function loadNotes() {
            const patientId = document.getElementById('patientSelect').value;
            
            fetch(`get_notes.php?patient_id=${patientId}`)
                .then(response => response.json())
                .then(notes => {
                    const notesContainer = document.getElementById('patientNotes');
                    notesContainer.innerHTML = notes.map(note => `
                        <div class="bg-gray-50 p-4 rounded-lg glass-effect animate__animated animate__fadeIn">
                            <p class="text-gray-800">${note.content}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                By ${note.staff_name} - ${new Date(note.created_at).toLocaleString()}
                            </p>
                        </div>
                    `).join('');
                });
        }

        document.getElementById('patientSelect').addEventListener('change', () => {
            updateVitals();
            loadNotes();
        });

        // Initialize everything and add scroll animations
        document.addEventListener('DOMContentLoaded', () => {
            initializeCharts();
            updateVitals();
            loadNotes();
            setInterval(updateVitals, 5000);

            // Fade-in on scroll
            const sections = document.querySelectorAll('.fade-in-section');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            sections.forEach(section => observer.observe(section));
        });
    </script>
</body>
</html>