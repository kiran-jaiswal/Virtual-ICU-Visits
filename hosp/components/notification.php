<?php
function showNotification($message, $type = 'success') {
    $bgColor = $type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    ?>
    <div class="notification fixed top-4 right-4 px-4 py-3 rounded border <?php echo $bgColor; ?> slide-in">
        <?php echo htmlspecialchars($message); ?>
        <button onclick="this.parentElement.remove()" class="ml-4 text-sm">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php
}
?>