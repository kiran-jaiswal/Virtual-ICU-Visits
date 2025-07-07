// Show progress modal with animation
function showProgressModal() {
    const modal = document.getElementById('progressModal');
    const progressBar = modal.querySelector('.progress-bar');
    const progressText = document.getElementById('progressText');
    
    modal.classList.remove('hidden');
    
    // Simulate progress
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        progressBar.style.width = `${progress}%`;
        
        if (progress === 30) {
            progressText.textContent = 'Analyzing data...';
        } else if (progress === 60) {
            progressText.textContent = 'Generating charts...';
        } else if (progress === 90) {
            progressText.textContent = 'Finalizing report...';
        }
        
        if (progress >= 100) {
            clearInterval(interval);
            setTimeout(() => {
                modal.classList.add('hidden');
                window.location.href = 'download_report.php';
            }, 500);
        }
    }, 500);
}

// Report generation modal handlers
function showVisitReportModal() {
    document.getElementById('visitReportModal').classList.remove('hidden');
}

function showProgressReportModal() {
    document.getElementById('progressReportModal').classList.remove('hidden');
}

function showCustomReportModal() {
    document.getElementById('customReportModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function generateVisitReport(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    showProgressModal();
    
    fetch('generate_report.php', {
        method: 'POST',
        body: JSON.stringify({
            type: 'visit',
            parameters: Object.fromEntries(formData)
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('visitReportModal');
            updateProgressBar(100);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to generate report');
    });
}

function showProgressModal() {
    const progressModal = document.getElementById('progressModal');
    progressModal.classList.remove('hidden');
    updateProgressBar(0);
    simulateProgress();
}

function updateProgressBar(percentage) {
    const progressBar = document.querySelector('.progress-bar');
    progressBar.style.width = percentage + '%';
}

function simulateProgress() {
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        if (progress <= 90) {
            updateProgressBar(progress);
        } else {
            clearInterval(interval);
        }
    }, 500);
}

// Add similar functions for progress and custom reports
function showProgressReportModal() {
    // Implementation for progress report modal
    showProgressModal();
}

function showCustomReportModal() {
    // Implementation for custom report modal
    showProgressModal();
}

// Add scroll animations
document.addEventListener('scroll', () => {
    const elements = document.querySelectorAll('.animate-on-scroll');
    elements.forEach(element => {
        const position = element.getBoundingClientRect();
        if (position.top < window.innerHeight) {
            element.classList.add('animate__fadeInUp');
        }
    });
});

// Initialize tooltips and other UI elements
document.addEventListener('DOMContentLoaded', () => {
    // Add any additional initialization code here
});