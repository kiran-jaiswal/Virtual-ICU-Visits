<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold" id="modalTitle"></h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="modalBody"></div>
            </div>
        </div>
    </div>
</div>

<script>
function showModal(title, content) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalBody').innerHTML = content;
    document.getElementById('modal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('modalContent').classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modalContent = document.getElementById('modalContent');
    modalContent.classList.remove('scale-100', 'opacity-100');
    setTimeout(() => {
        document.getElementById('modal').classList.add('hidden');
    }, 300);
}
</script>