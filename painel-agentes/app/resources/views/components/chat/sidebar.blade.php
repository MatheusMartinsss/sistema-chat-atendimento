@php use Illuminate\Support\Str; @endphp
@props([
    'chats' => [],
    'active' => null,
    'title' => 'Chats'
])

<div class="d-flex flex-column h-100 border-end bg-white" style="width: 320px; min-width: 280px;">
  <div class="p-3 border-bottom">
    <h5 class="m-0">{{ $title }}</h5>
  </div>

  <div class="p-2 border-bottom">
    <input type="text" class="form-control form-control-sm" placeholder="Buscar cliente..." oninput="filterChats(this.value)">
  </div>

  <div class="list-group list-group-flush flex-grow-1 overflow-auto" id="chatList">
    @forelse ($chats as $c)
      @php
        $isActive = (string)($active ?? '') === (string)$c['id'];
        $badgeClass = match($c['status']) { 'open'=>'bg-success', 'waiting'=>'bg-warning text-dark', 'closed'=>'bg-secondary', default=>'bg-light text-dark' };
        $label = match($c['status']) { 'open'=>'Aberto', 'waiting'=>'Pendente', 'closed'=>'Fechado', default=>ucfirst($c['status']) };
      @endphp

      <a href="{{ route('chats.partial', $c['id']) }}"
   class="list-group-item list-group-item-action chat-link"
   onclick="return openChat(event, this);">
        <div class="me-2 mt-1">
          <span class="badge rounded-pill {{ $badgeClass }}">{{ $label }}</span>
        </div>
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between">
            <strong>{{ $c['client_name'] }}</strong>
            <small class="text-muted">#{{ $c['id'] }}</small>
          </div>
          <div class="text-muted text-truncate" style="max-width: 220px;">
            {{ $c['last_msg'] ?? '' }}
          </div>
        </div>
      </a>
    @empty
      <div class="p-3 text-muted">Nenhum chat.</div>
    @endforelse
  </div>
</div>

<script>
function openChat(ev, link) {
  ev.preventDefault();
  const url = link.getAttribute('href');
  const container = document.getElementById('chat-container');
  container.innerHTML = '<div class="p-3 text-muted">Carregando...</div>';

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.text()).then(html => container.innerHTML = html)
    .catch(() => container.innerHTML = '<div class="alert alert-danger m-3">Falha ao carregar o chat.</div>');
}

  function filterChats(q) {
    q = (q || '').toLowerCase();
    const items = document.querySelectorAll('#chatList .list-group-item');
    items.forEach(li => {
      const name = (li.getAttribute('data-client') || '').toLowerCase();
      li.style.display = name.includes(q) ? '' : 'none';
    });
  }
</script>