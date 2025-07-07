const configuration = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' }
    ]
};

let peerConnection;
let localStream;
let remoteStream;
let isAudioMuted = false;
let isVideoMuted = false;

// Get visit ID from URL
const urlParams = new URLSearchParams(window.location.search);
const visitId = urlParams.get('visit_id');

async function initializeCall() {
    try {
        // Get local media stream
        localStream = await navigator.mediaDevices.getUserMedia({
            audio: true,
            video: true
        });
        document.getElementById('localVideo').srcObject = localStream;

        // Initialize WebSocket connection
        const ws = new WebSocket(`ws://${window.location.hostname}:8080`);
        
        ws.onopen = () => {
            // Join room with visit ID
            ws.send(JSON.stringify({
                type: 'join',
                room: visitId
            }));
        };

        ws.onmessage = async (event) => {
            const message = JSON.parse(event.data);
            
            switch(message.type) {
                case 'offer':
                    await handleOffer(message.offer);
                    break;
                case 'answer':
                    await handleAnswer(message.answer);
                    break;
                case 'ice-candidate':
                    await handleIceCandidate(message.candidate);
                    break;
            }
        };

        // Initialize peer connection
        peerConnection = new RTCPeerConnection(configuration);

        // Add local stream to peer connection
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });

        // Handle incoming stream
        peerConnection.ontrack = (event) => {
            remoteStream = event.streams[0];
            document.getElementById('remoteVideo').srcObject = remoteStream;
        };

        // Handle ICE candidates
        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                ws.send(JSON.stringify({
                    type: 'ice-candidate',
                    candidate: event.candidate,
                    room: visitId
                }));
            }
        };

    } catch (error) {
        console.error('Error initializing call:', error);
        alert('Failed to access camera/microphone. Please check permissions.');
    }
}

async function handleOffer(offer) {
    try {
        await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);
        
        ws.send(JSON.stringify({
            type: 'answer',
            answer: answer,
            room: visitId
        }));
    } catch (error) {
        console.error('Error handling offer:', error);
    }
}

async function handleAnswer(answer) {
    try {
        await peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
    } catch (error) {
        console.error('Error handling answer:', error);
    }
}

async function handleIceCandidate(candidate) {
    try {
        await peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
    } catch (error) {
        console.error('Error handling ICE candidate:', error);
    }
}

// Button event handlers
document.getElementById('muteAudio').addEventListener('click', () => {
    isAudioMuted = !isAudioMuted;
    localStream.getAudioTracks().forEach(track => {
        track.enabled = !isAudioMuted;
    });
    document.getElementById('muteAudio').innerHTML = 
        `<i class="fas fa-microphone${isAudioMuted ? '-slash' : ''}"></i> ${isAudioMuted ? 'Unmute' : 'Mute'}`;
});

document.getElementById('muteVideo').addEventListener('click', () => {
    isVideoMuted = !isVideoMuted;
    localStream.getVideoTracks().forEach(track => {
        track.enabled = !isVideoMuted;
    });
    document.getElementById('muteVideo').innerHTML = 
        `<i class="fas fa-video${isVideoMuted ? '-slash' : ''}"></i> ${isVideoMuted ? 'Start Video' : 'Stop Video'}`;
});

document.getElementById('endCall').addEventListener('click', () => {
    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
    }
    if (peerConnection) {
        peerConnection.close();
    }
    window.location.href = '../dashboard/family.php';
});

// Initialize call when page loads
initializeCall();