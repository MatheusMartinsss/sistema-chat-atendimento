@php
    $hasChat = !empty($chat);
    $chatId = data_get($chat, 'id');
    $client = data_get($chat, 'client_name', 'Cliente');
    $messages = data_get($chat, 'messages', []);
@endphp


@if (!$hasChat || !$chatId)
    <div class="card shadow-sm w-100">
        <div class="card-body text-center text-muted py-5">
            <h5>Nenhum chat selecionado</h5>
            <p>Selecione um chat na lista para iniciar o atendimento.</p>
        </div>
    </div>
@else
    <div class="card shadow-sm w-100" data-chat-id="{{ $chatId }}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Chat #{{ $chatId }} — {{ $client }}</strong>
        </div>

        <div class="card-body message-box" style="height:380px;overflow-y:auto;">

        </div>

        <div class="card-footer">
            <form onsubmit="return sendMsg(event, this)">
                <input type="text" name="message" placeholder="Digite sua mensagem..." class="form-control" required>
                <button class="btn btn-primary" type="submit">Enviar</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script>
        const token = window.TOKEN.token
        const q = new URLSearchParams(location.search);
        const chatId = q.get('selected_chat')

        const socket = io("http://localhost:8081", {
            transports: ['websocket'],
            auth: {
                token,
                chatId
            }
        });

        socket.io.on('reconnect_attempt', () => {
            socket.auth = { token };
        });


        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('[data-chat-id]');

            if (container) {
                socket.emit('take_chat');
            }
        });
        function appendMessage(author, text) {

            const box = document.querySelector('.message-box');
            if (!box) return;

            const div = document.createElement('div');
            const cls = author === 'agent' ? 'text-primary' : 'text-success';
            const label = author === 'agent' ? 'Agente' : 'Cliente';

            div.className = 'mb-2';
            div.innerHTML = `<strong class="${cls}">${label}:</strong> <span>${escapeHtml(text)}</span>`;
            box.appendChild(div);
            box.scrollTop = box.scrollHeight;
        }

        function sendMsg(event, form) {
            event.preventDefault();
            const input = form.querySelector('input[name="message"]');
            const messageText = input.value.trim();
            if (!messageText) return false;

            socket.emit('agent_message', messageText);

            appendMessage('agent', messageText);
            input.value = '';
            input.focus();
            return false;
        }

        socket.on('client_message', (message) => {
            appendMessage('client', message);
        });

        function escapeHtml(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
@endif