@props(['chat' => ['id' => null, 'client_name' => 'Cliente', 'messages' => []]])

<div class="card shadow-sm w-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Chat #{{ $chat['id'] }} — {{ $chat['client_name'] }}</strong>
        <span class="badge bg-success">Aberto</span>
    </div>

    <div class="card-body" style="height: 380px; overflow-y: auto;">
        @forelse ($chat['messages'] as $m)
            <div class="mb-2">
                <strong class="{{ $m['author'] === 'agent' ? 'text-primary' : 'text-success' }}">
                    {{ $m['author'] === 'agent' ? 'Agente' : 'Cliente' }}:
                </strong>
                <span>{{ $m['text'] }}</span>
            </div>
        @empty
            <div class="text-muted">Sem mensagens.</div>
        @endforelse
    </div>

    <div class="card-footer">
        <form class="d-flex gap-2">
            <input type="text" class="form-control" placeholder="Digite uma mensagem..." />
            <button class="btn btn-primary" type="button">Enviar</button>
        </form>
    </div>
</div>