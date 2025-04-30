<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$meetingLink = $_GET['meeting_link'] ?? '';

if (empty($meetingLink)) {
    echo "Invalid meeting link.";
    exit();
}

// Logic to initiate the video call using the meeting link
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Video Call</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50" style="font-family: Alkatra, system-ui;">
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Join Video Call</h2>
        <p>Connecting to the meeting...</p>
        <!-- Embed or redirect to the video call platform using the meeting link -->
        <iframe src="<?php echo htmlspecialchars($meetingLink); ?>" class="w-full h-96 border rounded"></iframe>
    </div>
</body>
</html>