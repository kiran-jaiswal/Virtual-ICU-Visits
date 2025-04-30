function showLoading() {
    document.getElementById('loadingAnimation').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingAnimation').classList.add('hidden');
}

// Example usage in AJAX calls
function someAjaxFunction() {
    showLoading();
    fetch('/api/endpoint')
        .then(response => response.json())
        .then(data => {
            // Handle data
        })
        .finally(() => {
            hideLoading();
        });
}