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
    <title>Medical Staff Dashboard - Virtual ICU Visits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
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

        /* Table Row Hover */
        .table-row {
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background-color: rgba(243, 244, 246, 0.8);
        }

        /* Modal Animation */
        .modal-content {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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
                    <a href="../staff/updates.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Give&nbsp;Updates</a>
                    <a href="../staff/messages.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Reply&nbsp;Family</a>
                    <a href="../dashboard/patient_monitor.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Patient Monitor</a>
                    <span class="text-blue-700 font-semibold">Dr. <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    <a href="../auth/logout.php" class="text-red-600 hover:text-red-800 font-medium transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-700 to-blue-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 text-center fade-in-section">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Medical Staff Dashboard</h2>
            <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                Manage patients and virtual visits with ease, ensuring seamless care and connectivity.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Patient Management -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-12 glass-effect fade-in-section">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Manage Patients</h2>
                <button onclick="showAddPatientModal()" class="btn bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Add New Patient
                </button>
            </div>
            <div class="overflow-x-auto rounded-lg">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Name</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Bed Number</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Admission Date</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $stmt = $pdo->query("SELECT * FROM patients ORDER BY admission_date DESC");
                        while ($patient = $stmt->fetch()) {
                            ?>
                            <tr class="table-row">
                                <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($patient['full_name']); ?></td>
                                <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($patient['bed_number']); ?></td>
                                <td class="px-6 py-4 text-gray-600"><?php echo date('M d, Y', strtotime($patient['admission_date'])); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                                        <?php echo $patient['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo ucfirst($patient['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex space-x-3">
                                    <button onclick="editPatient(<?php echo $patient['id']; ?>)" 
                                            class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="togglePatientStatus(<?php echo $patient['id']; ?>)" 
                                            class="text-<?php echo $patient['status'] === 'active' ? 'red' : 'green'; ?>-600 hover:text-<?php echo $patient['status'] === 'active' ? 'red' : 'green'; ?>-800 transition-colors">
                                        <i class="fas fa-<?php echo $patient['status'] === 'active' ? 'times' : 'check'; ?>"></i>
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

        <!-- Upcoming Visits -->
        <div class="bg-white rounded-2xl shadow-lg p-8 glass-effect fade-in-section">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Virtual Visits</h2>
            <?php
            $stmt = $pdo->prepare("SELECT v.*, p.full_name as patient_name, u.full_name as visitor_name 
                                 FROM visits v 
                                 JOIN patients p ON v.patient_id = p.id 
                                 JOIN users u ON v.visitor_id = u.id
                                 WHERE v.status = 'scheduled' AND v.visit_date >= CURRENT_TIMESTAMP
                                 ORDER BY v.visit_date");
            $stmt->execute();
            $visits = $stmt->fetchAll();

            if (empty($visits)) {
                echo '<p class="text-center text-gray-600">No scheduled visits available.</p>';
            } else {
                ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($visits as $visit): ?>
                        <div class="card border rounded-2xl p-6 bg-white glass-effect">
                            <div class="flex items-center mb-4">
                                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                                     alt="Patient" class="w-12 h-12 rounded-full mr-3 object-cover">
                                <h3 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($visit['patient_name']); ?></h3>
                            </div>
                            <p class="text-gray-600 mb-2">
                                <i class="fas fa-user-friends text-blue-600 mr-2"></i>
                                Family: <?php echo htmlspecialchars($visit['visitor_name']); ?>
                            </p>
                            <p class="text-gray-600 mb-4">
                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                <?php echo date('F j, Y g:i A', strtotime($visit['visit_date'])); ?>
                            </p>
                            <a href="call.php?visit_id=<?php echo $visit['id']; ?>" 
                               class="btn inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 w-full text-center">
                                Join Visit
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <!-- Add/Edit Patient Modal -->
    <div id="patientModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 glass-effect modal-content">
            <h3 class="text-2xl font-bold text-gray-800 mb-6" id="modalTitle">Add New Patient</h3>
            <form id="patientForm" method="POST" action="manage_patient.php">
                <input type="hidden" name="patient_id" id="patientId">
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fullName">Full Name</label>
                    <div class="relative">
                        <input type="text" name="full_name" id="fullName" required
                               class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-user absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="bedNumber">Bed Number</label>
                    <div class="relative">
                        <input type="text" name="bed_number" id="bedNumber" required
                               class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-bed absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="admissionDate">Admission Date</label>
                    <div class="relative">
                        <input type="date" name="admission_date" id="admissionDate" required
                               class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()"
                            class="btn px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Patient Modal Functions
        function showAddPatientModal() {
            document.getElementById('modalTitle').textContent = 'Add New Patient';
            document.getElementById('patientId').value = '';
            document.getElementById('patientForm').reset();
            document.getElementById('patientModal').classList.remove('hidden');
        }

        function editPatient(patientId) {
            fetch(`get_patient.php?id=${patientId}`)
                .then(response => response.json())
                .then(patient => {
                    document.getElementById('modalTitle').textContent = 'Edit Patient';
                    document.getElementById('patientId').value = patient.id;
                    document.getElementById('fullName').value = patient.full_name;
                    document.getElementById('bedNumber').value = patient.bed_number;
                    document.getElementById('admissionDate').value = patient.admission_date;
                    document.getElementById('patientModal').classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('patientModal').classList.add('hidden');
        }

        function togglePatientStatus(patientId) {
            fetch('manage_patient.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `patient_id=${patientId}&action=toggle_status`
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                } else {
                    alert('Failed to update patient status');
                }
            });
        }

        // Visit Management Functions
        function updateVisitsList(visits) {
            const visitsContainer = document.querySelector('.grid');
            let html = '';
            
            visits.forEach(visit => {
                html += `
                    <div class="card border rounded-2xl p-6 bg-white glass-effect">
                        <div class="flex items-center mb-4">
                            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                                 alt="Patient" class="w-12 h-12 rounded-full mr-3 object-cover">
                            <h3 class="text-xl font-semibold text-gray-800">${visit.patient_name}</h3>
                        </div>
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-user-friends text-blue-600 mr-2"></i>
                            Family: ${visit.visitor_name}
                        </p>
                        <p class="text-gray-600 mb-4">
                            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                            ${new Date(visit.visit_date).toLocaleString()}
                        </p>
                        <a href="call.php?visit_id=${visit.id}" 
                           class="btn inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 w-full text-center">
                            Join Visit
                        </a>
                    </div>
                `;
            });
            
            if (html === '') {
                html = '<p class="col-span-3 text-center text-gray-600">No scheduled visits available.</p>';
            }
            
            visitsContainer.innerHTML = html;
        }

        // Close modal when clicking outside
        document.getElementById('patientModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Initialize real-time updates and scroll animations
        document.addEventListener('DOMContentLoaded', function() {
            // Update visits every 30 seconds
            setInterval(() => {
                fetch('get_visits.php')
                    .then(response => response.json())
                    .then(visits => {
                        updateVisitsList(visits);
                    });
            }, 30000);

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