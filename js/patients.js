// Add patient form submission
document.getElementById('addPatientForm').addEventListener('submit', function(e) {
    e.preventDefault();
    showLoading();
    
    // Form submission code
    const formData = new FormData(this);
    fetch('../api/patients/add.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Handle success
        }
    })
    .finally(() => {
        hideLoading();
    });
});