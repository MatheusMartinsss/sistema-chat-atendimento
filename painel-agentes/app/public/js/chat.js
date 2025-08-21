
(function (w) {
    const socket = w.ws || io("http://localhost:8081", { transports: ['websocket'] });
    w.ws = socket;

    let currentChatId = null;
    let onMessageHandler = null;

    function join(chatId) {
        if (!chatId) return;
        currentChatId = chatId;
        socket.emit('take_chat', chatId);
    }

    function leave(chatId) {
        currentChatId = null;
        socket.emit('leave_chat', chatId); 
    }

    function onMessage(cb) {
        if (onMessageHandler) {
            socket.off('client_message', onMessageHandler);
        }

        onMessageHandler = function (payload) {
            if (typeof cb === 'function') {
                console.log(payload)
                cb(payload);
            }
        };

        socket.on('client_message', onMessageHandler);
    }

    function send(chatId, message) {
        console.log('Enviando via WS:', { chatId, message });
        socket.emit('agent_message', { chatId, message });
    }

    w.ChatWS = { join, leave, onMessage, send, socket };
})(window);