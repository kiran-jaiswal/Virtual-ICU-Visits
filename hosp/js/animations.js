// Show loading animation
function showLoading() {
    document.getElementById('loadingAnimation').classList.remove('hidden');
}

// Hide loading animation
function hideLoading() {
    document.getElementById('loadingAnimation').classList.add('hidden');
}

// Add animation to elements
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on page load
    const cards = document.querySelectorAll('.animate-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });

    // Animate notifications
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        notification.classList.add('slide-in');
    });
});

// Smooth scroll to elements
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    element.scrollIntoView({ behavior: 'smooth' });
}