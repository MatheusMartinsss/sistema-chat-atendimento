<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    private function mockChats(): array
    {
        return [
            ['id' => 101, 'client_name' => 'João Silva', 'status' => 'open', 'last_msg' => 'Preciso de ajuda...'],
            ['id' => 102, 'client_name' => 'Maria Souza', 'status' => 'pending', 'last_msg' => 'Aguardando retorno...'],
            ['id' => 103, 'client_name' => 'Carlos Pereira', 'status' => 'closed', 'last_msg' => 'Obrigado!'],
            ['id' => 104, 'client_name' => 'Ana Lima', 'status' => 'open', 'last_msg' => 'Meu boleto venceu.'],
        ];
    }
    public function index()
    {
        $chats = $this->mockChats();
        // carrega a dashboard com a sidebar e, opcionalmente, um chat inicial
        $initial = [
            'id' => 101,
            'client_name' => 'João Silva',
            'status' => 'open',
            'messages' => [
                ['author' => 'client', 'text' => 'Olá, tudo bem?'],
                ['author' => 'agent', 'text' => 'Olá! Em que posso ajudar?'],
            ],
        ];

        return view('dashboard.index', [
            'chats' => $chats,
            'chat' => $initial,   // opcional
        ]);
    }
    public function show(int $id)
    {
        // MOCK: detalhe do chat
        $chat = [
            'id' => $id,
            'client_name' => 'Cliente Exemplo',
            'status' => 'open',
            'messages' => [
                ['author' => 'client', 'text' => 'Olá, tudo bem?'],
                ['author' => 'agent', 'text' => 'Olá! Em que posso ajudar?'],
            ],
        ];

        return view('components.chat.show', compact('chat'));
    }

    public function partial(int $id)
    {
        $chat = [
            'id' => $id,
            'client_name' => 'Cliente #' . $id,
            'status' => 'open',
            'messages' => [
                ['author' => 'client', 'text' => 'Mensagem do cliente no chat ' . $id],
                ['author' => 'agent', 'text' => 'Resposta do agente.'],
            ],
        ];

        // retorna o componente como HTML
        return view('components.chat.show', ['chat' => $chat])->render();
    }
}