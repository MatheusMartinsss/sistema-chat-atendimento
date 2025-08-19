@extends('layout')

@section('content')
    <div class="d-flex flex-column" style="height: calc(100vh - 120px);">

        <div class="d-flex justify-content-between align-items-center bg-light border-bottom px-4 py-2">
            <div>
                @auth
                    <div>
                        <strong>{{ Auth::user()->name }}</strong><br>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                @endauth
            </div>
            <div>
                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

 
        <div class="d-flex flex-grow-1">
            <x-chat.sidebar :chats="$chats" :active="$chat['id'] ?? null" />

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
    </div>
@endsection