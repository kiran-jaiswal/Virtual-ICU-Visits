const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

const rooms = new Map();

wss.on('connection', (ws) => {
    ws.on('message', (message) => {
        const data = JSON.parse(message);
        const room = data.room;

        switch (data.type) {
            case 'join':
                if (!rooms.has(room)) {
                    rooms.set(room, new Set());
                }
                rooms.get(room).add(ws);
                break;

            case 'offer':
            case 'answer':
            case 'ice-candidate':
                // Broadcast to all clients in the room except sender
                if (rooms.has(room)) {
                    rooms.get(room).forEach(client => {
                        if (client !== ws && client.readyState === WebSocket.OPEN) {
                            client.send(message);
                        }
                    });
                }
                break;
        }
    });

    ws.on('close', () => {
        // Remove client from all rooms
        rooms.forEach((clients, room) => {
            clients.delete(ws);
            if (clients.size === 0) {
                rooms.delete(room);
            }
        });
    });
});