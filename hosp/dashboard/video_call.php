<?php
session_start();
require_once('../config/database.php');

// Generate or use existing room ID
$room = isset($_GET['room']) ? $_GET['room'] : uniqid();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Call - Virtual ICU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/peerjs@1.4.7/dist/peerjs.min.js"></script>
</head>
<body style="font-family: Alkatra, system-ui;">
    <div class="max-w-6xl mx-auto p-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Connection Status -->
            <div id="connectionStatus" class="mb-4 text-center">
                <span class="text-yellow-600">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Waiting for connection...
                </span>
            </div>

            <!-- Room Link Sharing -->
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                    <!-- Update room link input -->
                    <input type="text" id="roomLink" readonly 
                        value="<?php 
                            $server_ip = gethostbyname(gethostname());
                            echo "http://" . $server_ip . "/hosp/dashboard/video_call.php?room=" . htmlspecialchars($room); 
                        ?>" 
                        class="w-full p-2 border rounded mr-2">
                    <button onclick="copyRoomLink()" class="bg-blue-600 text-white px-4 py-2 rounded">
                        <i class="fas fa-copy mr-2"></i>Copy Link
                    </button>
                        
                    <!-- Update the JavaScript room variable -->
                    <script>
                    // Initialize PeerJS with STUN servers
                    // Replace the existing peer initialization and connection code
                    // Update PeerJS configuration
                    const peer = new Peer(room, {  // Use room as the peer ID
                        host: 'localhost',
                        port: 9000,
                        path: '/myapp',
                        config: {
                            'iceServers': [
                                { urls: 'stun:stun.l.google.com:19302' },
                                {
                                    urls: 'turn:numb.viagenie.ca',
                                    credential: 'muazkh',
                                    username: 'webrtc@live.com'
                                }
                            ]
                        },
                        debug: 3
                    });
                    
                    // Update initializeCall function
                    async function initializeCall() {
                        try {
                            localStream = await navigator.mediaDevices.getUserMedia({
                                video: true,
                                audio: true
                            });
                            document.getElementById('localVideo').srcObject = localStream;
                            
                            peer.on('open', (id) => {
                                console.log('My peer ID is: ' + id);
                                updateConnectionStatus('waiting');
                            });
                            
                            peer.on('connection', (conn) => {
                                console.log('Peer connected:', conn.peer);
                                const call = peer.call(conn.peer, localStream);
                                handleCall(call);
                            });
                            
                            peer.on('call', (incomingCall) => {
                                console.log('Receiving call from:', incomingCall.peer);
                                incomingCall.answer(localStream);
                                handleCall(incomingCall);
                            });
                            
                            peer.on('error', (err) => {
                                console.error('Peer error:', err);
                                updateConnectionStatus('disconnected');
                            });
                            
                            peer.on('disconnected', () => {
                                console.log('Peer disconnected');
                                updateConnectionStatus('disconnected');
                                peer.reconnect();
                            });
                            
                        } catch (err) {
                            console.error('Failed to get local stream:', err);
                            updateConnectionStatus('disconnected');
                        }
                    }
                    
                    function handleCall(call) {
                        call.on('stream', (stream) => {
                            console.log('Received remote stream');
                            const remoteVideo = document.getElementById('remoteVideo');
                            if (remoteVideo.srcObject !== stream) {
                                remoteVideo.srcObject = stream;
                                remoteStream = stream;
                                updateConnectionStatus('connected');
                            }
                        });
                        
                        call.on('close', () => {
                            console.log('Call closed');
                            updateConnectionStatus('disconnected');
                            remoteStream = null;
                            document.getElementById('remoteVideo').srcObject = null;
                        });
                        
                        call.on('error', (err) => {
                            console.error('Call error:', err);
                            updateConnectionStatus('disconnected');
                        });
                    }
                    
                    // Add copy link button functionality
                    function copyRoomLink() {
                        const roomLink = document.getElementById('roomLink');
                        roomLink.select();
                        document.execCommand('copy');
                        alert('Link copied! Share this with the other person to join the call.');
                    }
                    
                    // Initialize the call
                    initializeCall();
                    </script>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    Share this link with the other person to join the call
                </p>
            </div>

            <!-- Video Containers -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative">
                    <video id="localVideo" autoplay muted playsinline 
                        class="w-full rounded-lg border-2 border-blue-500"></video>
                    <span class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded">
                        You
                    </span>
                </div>
                <div class="relative">
                    <video id="remoteVideo" autoplay playsinline 
                        class="w-full rounded-lg border-2 border-green-500"></video>
                    <span class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded">
                        Remote User
                    </span>
                </div>
            </div>

            <!-- Call Controls -->
            <div class="flex justify-center space-x-4 mt-4">
                <button id="toggleAudio" class="bg-gray-600 text-white p-3 rounded-full hover:bg-gray-700">
                    <i class="fas fa-microphone"></i>
                </button>
                <button id="toggleVideo" class="bg-gray-600 text-white p-3 rounded-full hover:bg-gray-700">
                    <i class="fas fa-video"></i>
                </button>
                <button id="endCall" class="bg-red-600 text-white p-3 rounded-full hover:bg-red-700">
                    <i class="fas fa-phone-slash"></i>
                </button>
            </div>
        </div>
    </div>
</body>
</html>