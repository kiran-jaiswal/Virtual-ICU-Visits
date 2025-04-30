class VisitRecorder {
    constructor(stream) {
        this.mediaRecorder = null;
        this.recordedChunks = [];
        this.stream = stream;
        this.setupRecorder();
    }

    setupRecorder() {
        this.mediaRecorder = new MediaRecorder(this.stream, {
            mimeType: 'video/webm;codecs=vp9'
        });

        this.mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                this.recordedChunks.push(event.data);
            }
        };

        this.mediaRecorder.onstop = () => {
            this.saveRecording();
        };
    }

    startRecording() {
        this.recordedChunks = [];
        this.mediaRecorder.start();
    }

    stopRecording() {
        this.mediaRecorder.stop();
    }

    async saveRecording() {
        const blob = new Blob(this.recordedChunks, {
            type: 'video/webm'
        });

        const formData = new FormData();
        formData.append('recording', blob);
        formData.append('visit_id', this.visitId);

        try {
            const response = await fetch('../api/save-recording.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Visit recording saved successfully', 'success');
            }
        } catch (error) {
            showNotification('Failed to save recording', 'error');
        }
    }
}