<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get visit ID from URL
$visitId = isset($_GET['visit_id']) ? (int)$_GET['visit_id'] : 0;

// Fetch visit details
$stmt = $pdo->prepare("SELECT v.*, p.full_name as patient_name, f.full_name as family_name 
                       FROM visits v 
                       JOIN patients p ON v.patient_id = p.id 
                       JOIN users f ON v.visitor_id = f.id 
                       WHERE v.id = ?");
$stmt->execute([$visitId]);
$visit = $stmt->fetch();

if (!$visit) {
    $_SESSION['error'] = "Invalid visit ID";
    header('Location: ' . ($_SESSION['user_type'] === 'staff' ? 'staff.php' : 'family.php'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">

    <title>Virtual Visit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://meet.jit.si/external_api.js"></script>
</head>
<body class="bg-gray-100" style="font-family: Alkatra, system-ui;">
    <div class="max-w-7xl mx-auto p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Virtual Visit with <?php echo htmlspecialchars($visit['patient_name']); ?></h1>
                <button onclick="window.location.href='<?php echo $_SESSION['user_type'] === 'staff' ? 'staff.php' : 'family.php'; ?>'" 
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Exit Call
                </button>
            </div>
            <div id="meet" class="w-full" style="height: 600px;"></div>
        </div>
    </div>

    <script>
        const domain = 'meet.jit.si';
        const options = {
            roomName: 'virtualicu_visit_<?php echo $visitId; ?>',
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#meet'),
            userInfo: {
                displayName: '<?php echo htmlspecialchars($_SESSION['full_name']); ?>'
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    </script>
</body>
</html>