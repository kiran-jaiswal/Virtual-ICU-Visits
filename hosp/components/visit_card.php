<?php
function renderVisitCard($visit, $isStaff = false) {
    ?>
    <div class="border rounded-lg p-6 bg-white shadow-md hover-scale animate-card">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-user-md text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($visit['patient_name']); ?></h3>
                <p class="text-gray-600">
                    <?php echo date('F j, Y g:i A', strtotime($visit['visit_date'])); ?>
                </p>
            </div>
        </div>
        
        <?php if ($isStaff): ?>
            <p class="text-gray-600 mb-4">
                Visitor: <?php echo htmlspecialchars($visit['visitor_name']); ?>
            </p>
        <?php endif; ?>

        <div class="flex space-x-2 mt-4">
            <a href="<?php echo htmlspecialchars($visit['meeting_link']); ?>" 
               class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center">
                <i class="fas fa-video mr-2"></i> Join Visit
            </a>
            <?php if (strtotime($visit['visit_date']) > time()): ?>
                <button onclick="rescheduleVisit(<?php echo $visit['id']; ?>)"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    <i class="fas fa-calendar-alt"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>