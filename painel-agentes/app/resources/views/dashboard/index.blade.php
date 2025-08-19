@extends('layout')

@section('content')
    <div class="d-flex" style="height: calc(100vh - 120px);">
        {{-- Topo com usuário e botão de logout --}}
        <div class="p-2 border-end">
            @auth
                <div class="mb-3">
                    <strong>{{ Auth::user()->name }}</strong><br>
                    <small>{{ Auth::user()->email }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        Logout
                    </button>
                </form>
            @endauth
        </div>

        {{-- Sidebar de chats --}}
        <x-chat.sidebar :chats="$chats" :active="$chat['id'] ?? null" />

        {{-- Área do chat --}}
        <div id="chatArea" class="flex-grow-1 p-3">
            @isset($chat)
                <x-chat.show :chat="$chat" />
            @else
                <div class="alert alert-info">
                    Selecione um chat na barra lateral.
                </div>
            @endisset
        </div>
    </div>
@endsection