@extends('layout')

@section('content')
<div class="d-flex" style="height: calc(100vh - 120px);">
  <x-chat.sidebar :chats="$chats" :active="$chat['id'] ?? null" />


  <div id="chatArea" class="flex-grow-1 p-3">
    @isset($chat)
      <x-chat.show :chat="$chat" />
    @else
      <div class="alert alert-info">Selecione um chat na barra lateral.</div>
    @endisset
  </div>
</div>
@endsection