@extends('layout')

@auth
    <script>
        window.AGENT = {
            id: {{ Auth::id() }},
            name: @json(Auth::user()->name),
            email: @json(Auth::user()->email),
        };

        window.TOKEN = {
            token: @json(session('token')),
        };
    </script>
@endauth
@section('content')
    <div class="d-flex flex-column" style="height: calc(100vh - 80px);">
        <nav class="navbar navbar-light bg-light px-3 border-bottom">
            <span class="navbar-brand mb-0 h6">Painel de Atendimento</span>
            <div class="ms-auto">
                @auth
                    <small class="text-muted me-3">{{ Auth::user()->name }}</small>
                @endauth
                <form action="{{ route('logout') }}" method="POST" class="d-inline">@csrf
                    <button class="btn btn-sm btn-outline-danger">Sair</button>
                </form>
            </div>
        </nav>
        <div class="d-flex flex-grow-1">
            <aside class="border-end bg-white" style="width:320px;min-width:280px;">
                @include('components.chat.sidebar', ['chats' => $chats])
            </aside>
            <main class="flex-grow-1 p-3">
                <div id="chatCard" class="card shadow-sm {{ $chat ? '' : 'd-none' }}">
                    @if($chat)
                        @include('components.chat.show', ['chat' => $chat])
                    @endif
                </div>

                <div id="emptyState" class="alert alert-info">Selecione um chat na lista à esquerda.</div>
            </main>
        </div>
        <script>
            function openChat(ev, link) {
                ev.preventDefault();

                const chatId = Number(link.dataset.chatId);
                window.location.href = `/dashboard?selected_chat=${chatId}`;
            }


        </script>
@endsection