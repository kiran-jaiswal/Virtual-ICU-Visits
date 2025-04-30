// Schedule visit
function scheduleVisit(patientId) {
    showLoading();
    fetch('../api/visits/schedule.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ patient_id: patientId })
    })
    .then(response => response.json())
    .then(data => {
        // Handle response
    })
    .finally(() => {
        hideLoading();
    });
}