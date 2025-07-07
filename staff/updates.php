<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header('Location: ../login.php');
    exit();
}

// Fetch all updates with staff information
$updatesStmt = $pdo->prepare("
    SELECT du.*, u.full_name as staff_name
    FROM daily_updates du
    JOIN users u ON du.user_id = u.id
    ORDER BY du.created_at DESC
");
$updatesStmt->execute();
$updates = $updatesStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Updates</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50" style="font-family: Alkatra, system-ui;">
    <nav class="bg-white shadow-lg mb-8">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/hosp/dashboard/staff.php" class="text-xl font-bold text-blue-600">Virtual ICU</a>
                    <div class="hidden md:flex items-center ml-10 space-x-4">
                        <a href="/hosp/dashboard/staff.php" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                        <a href="updates.php" class="text-blue-600 px-3 py-2 rounded-md">
                            <i class="fas fa-notes-medical mr-2"></i>Updates
                        </a>
                        <a href="/hosp/dashboard/patient_monitor.php" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md">
                            <i class="fas fa-user-injured mr-2"></i>Patients
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700 px-3 py-2">
                        <i class="fas fa-user-md mr-2"></i><?php echo $_SESSION['full_name']; ?>
                    </span>
                    <a href="../auth/logout.php" class="text-gray-700 hover:text-blue-600 px-3 py-2 ml-4">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Post Update Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Post Update</h2>
                <form id="updateForm" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Update Content</label>
                        <textarea id="content" class="w-full border rounded-lg px-4 py-2 h-32" required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Post Update
                    </button>
                </form>
            </div>

            <!-- Updates List -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Recent Updates</h2>
                <div class="space-y-4">
                    <!-- In the updates list section, modify the update display: -->
                    <?php foreach ($updates as $update): ?>
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        By <?php echo htmlspecialchars($update['staff_name']); ?>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">
                                        <?php echo date('M d, Y h:i A', strtotime($update['created_at'])); ?>
                                    </span>
                                    <?php if ($update['user_id'] == $_SESSION['user_id']): ?>
                                        <button onclick="deleteUpdate(<?php echo $update['id']; ?>)" 
                                                class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="mt-2 text-gray-700"><?php echo htmlspecialchars($update['content']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this JavaScript function at the end of the file -->
    <script>
    function deleteUpdate(updateId) {
        if (confirm('Are you sure you want to delete this update?')) {
            fetch('../api/updates/delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    update_id: updateId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
    </script>
    <script>
    document.getElementById('updateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const content = document.getElementById('content').value.trim();
        if (!content) return;

        fetch('../api/updates/post.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                content: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('content').value = '';
                location.reload();
            }
        });
    });
    </script>
</body>
</html>