class EmergencyAlert {
    constructor() {
        this.socket = new WebSocket('ws://localhost:8080');
        this.setupSocketHandlers();
    }

    setupSocketHandlers() {
        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (data.type === 'emergency') {
                this.showEmergencyAlert(data);
            }
        };
    }

    showEmergencyAlert(data) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'fixed inset-0 bg-red-600 bg-opacity-90 z-50 flex items-center justify-center';
        alertDiv.innerHTML = `
            <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
                <h2 class="text-2xl font-bold text-red-600 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Emergency Alert
                </h2>
                <p class="text-gray-700 mb-4">${data.message}</p>
                <div class="flex justify-end">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Acknowledge
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(alertDiv);
    }

    triggerEmergency(message) {
        this.socket.send(JSON.stringify({
            type: 'emergency',
            message: message
        }));
    }
}