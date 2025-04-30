<?php
session_start();
require_once('../config/database.php');

// Check if user is logged in and is family member
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'family') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Dashboard - Virtual ICU Visits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
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

        /* Notification Animation */
        .notification {
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
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
                    <a href="../dashboard/family_hub.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Updates & Notifications</a>
                    <span class="text-blue-700 font-semibold">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    <a href="../auth/logout.php" class="text-red-600 hover:text-red-800 font-medium transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success Notification -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="max-w-7xl mx-auto px-6 py-4 fade-in-section notification">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-md">
                <?php 
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Error Notification -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="max-w-7xl mx-auto px-6 py-4 fade-in-section notification">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-md">
                <?php 
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-700 to-blue-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 text-center fade-in-section">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Family Dashboard</h2>
            <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                Stay connected with your loved ones in the ICU. Schedule visits and manage updates with ease.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Upcoming Visits -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-12 glass-effect fade-in-section">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Upcoming Visits</h2>
            <?php
            $stmt = $pdo->prepare("SELECT v.*, p.full_name as patient_name 
                                 FROM visits v 
                                 JOIN patients p ON v.patient_id = p.id 
                                 WHERE v.visitor_id = ? AND v.status = 'scheduled'
                                 ORDER BY v.visit_date");
            $stmt->execute([$_SESSION['user_id']]);
            $visits = $stmt->fetchAll();
            
            if (empty($visits)) {
                echo '<p class="text-gray-600 text-center">No upcoming visits scheduled.</p>';
            } else {
                ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($visits as $visit): ?>
                        <div class="card border rounded-2xl p-6 bg-white glass-effect">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-user-md text-blue-600 text-2xl mr-3"></i>
                                <h3 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($visit['patient_name']); ?></h3>
                            </div>
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

        <!-- Schedule New Visit -->
        <div class="bg-white rounded-2xl shadow-lg p-8 glass-effect fade-in-section">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Schedule New Visit</h2>
            <form action="schedule_visit.php" method="POST" class="max-w-lg mx-auto">
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="patient">
                        Select Patient
                    </label>
                    <div class="relative">
                        <select name="patient_id" id="patient" class="w-full border rounded-lg px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <?php
                            $stmt = $pdo->prepare("SELECT id, full_name FROM patients WHERE status = 'active'");
                            $stmt->execute();
                            while ($patient = $stmt->fetch()) {
                                echo "<option value='" . $patient['id'] . "'>" . htmlspecialchars($patient['full_name']) . "</option>";
                            }
                            ?>
                        </select>
                        <i class="fas fa-user absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="visit_date">
                        Visit Date and Time
                    </label>
                    <div class="relative">
                        <input type="datetime-local" name="visit_date" id="visit_date" 
                               class="w-full border rounded-lg px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <i class="fas fa-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <button type="submit" class="btn bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 w-full">
                    Schedule Visit
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="fade-in-section">
                    <h3 class="text-2xl font-bold mb-4">Virtual ICU Visits</h3>
                    <p class="text-gray-400 leading-relaxed">Connecting families with care and compassion.</p>
                </div>
                <div class="fade-in-section">
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="../dashboard/family_hub.php" class="text-gray-400 hover:text-blue-400 transition-colors">Dashboard</a></li>
                        <li><a href="../auth/logout.php" class="text-gray-400 hover:text-blue-400 transition-colors">Logout</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Support</a></li>
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
    </script>
</body>
</html>