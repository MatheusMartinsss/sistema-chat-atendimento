@php
    $hasChat = !empty($chat);
    $chatId = data_get($chat, 'id');
    $client = data_get($chat, 'client_name', 'Cliente');
    $messages = data_get($chat, 'messages', []);
@endphp

@if(!$hasChat || !$chatId)
    <div class="card shadow-sm w-100">
        <div class="card-body text-center text-muted py-5">
            <h5>Nenhum chat selecionado</h5>
            <p>Selecione um chat na lista para iniciar o atendimento.</p>
        </div>
    </div>
@else
    <div class="card shadow-sm w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Chat #{{ $chatId }} — {{ $client }}</strong>

        </div>

        <div class="card-body" style="height:380px;overflow-y:auto;">
            @forelse($messages as $m)
                @php $author = data_get($m, 'author', 'client');
                $text = data_get($m, 'text', ''); @endphp
                <div class="mb-2">
                    <strong class="{{ $author === 'agent' ? 'text-primary' : 'text-success' }}">
                        {{ $author === 'agent' ? 'Agente' : 'Cliente' }}:
                    </strong>
                    <span>{{ $text }}</span>
                </div>
            @empty
                <div class="text-muted">Sem mensagens.</div>
            @endforelse
        </div>
        <div class="card-footer">
            <form id="chatForm-{{ $chatId }}" class="d-flex gap-2" method="POST"
                onsubmit="return sendMsg(event, {{ $chatId }})">
                @csrf
                <input name="message" id="chatMessage-{{ $chatId }}" class="form-control"
                    placeholder="Digite uma mensagem..." required>
                <button class="btn btn-primary" type="submit">Enviar</button>
            </form>
        </div>
    </div>

@endif
<script>
    function sendMsg(e, chatId) {
        console.log(chatId, e)
        e.preventDefault();
        const form = e.target;
        const fd = new FormData(form);

        fetch(`{{ route('chat.sendMessage', ':id') }}`.replace(':id', chatId), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: fd
        })
            .then(r => r.text())
            .then(html => {
                document.getElementById('chat-container').innerHTML = html;
            });
        form.reset();
        return false;
    }

    function closeChat(e) {
        e.preventDefault();
        fetch(`{{ route('chat.close') }}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
            .then(r => r.text())
            .then(html => {
                document.getElementById('chat-container').innerHTML = html; // estado vazio
            });
        return false;
    }
</script>
</script>