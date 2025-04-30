const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

const rooms = new Map();

wss.on('connection', (ws) => {
    ws.on('message', (message) => {
        const data = JSON.parse(message);
        const roomId = data.roomId;

        if (!rooms.has(roomId)) {
            rooms.set(roomId, new Set());
        }

        rooms.get(roomId).add(ws);

        // Broadcast to all clients in the room except sender
        rooms.get(roomId).forEach(client => {
            if (client !== ws && client.readyState === WebSocket.OPEN) {
                client.send(message);
            }
        });
    });

    ws.on('close', () => {
        rooms.forEach((clients, roomId) => {
            clients.delete(ws);
            if (clients.size === 0) {
                rooms.delete(roomId);
            }
        });
    });
});