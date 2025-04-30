<?php
require_once('../config/database.php');

function cleanupDischargedPatients() {
    global $pdo;
    
    try {
        // Delete patients discharged more than 5 days ago
        $stmt = $pdo->prepare("
            DELETE FROM patients 
            WHERE status = 'discharged' 
            AND DATEDIFF(CURRENT_DATE, updated_at) > 5
        ");
        
        $stmt->execute();
        
        // Log the cleanup
        $deletedCount = $stmt->rowCount();
        logCleanup($deletedCount);
        
        return true;
    } catch (PDOException $e) {
        logCleanup(0, $e->getMessage());
        return false;
    }
}

function logCleanup($count, $error = null) {
    $logFile = __DIR__ . '/../logs/cleanup.log';
    $timestamp = date('Y-m-d H:i:s');
    $message = $error 
        ? "[$timestamp] Error: $error\n"
        : "[$timestamp] Successfully cleaned up $count discharged patient(s)\n";
    
    file_put_contents($logFile, $message, FILE_APPEND);
}

// Run cleanup if script is called directly
if (php_sapi_name() === 'cli') {
    cleanupDischargedPatients();
}
?>