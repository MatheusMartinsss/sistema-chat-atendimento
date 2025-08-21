<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ChatService;
use Illuminate\Support\Facades\Http;
class ChatController extends Controller
{
    private ChatService $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(Request $request, int $id)
    {
        

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000']
        ]);

        $pendingKey = "pending_messages.$id";
        $pending = $request->session()->get($pendingKey, []);

        $message = [
            'author' => 'agent',
            'text' => $data['message'],
        ];

        $pending[] = $message;
        $request->session()->put($pendingKey, $pending);

        $chat = $this->composeChat($request, $id);

        return view('components.chat.show', ['chat' => $chat])->render();
    }
    public function index(Request $request)
    {

        $chats = $this->chatService->getChats();

        if ($request->has('selected_chat')) {
            $request->session()->put('selected_chat', $request->get('selected_chat'));
        }

        $selectedId = $request->session()->get("selected_chat");
        $chat = $selectedId ? $this->composeChat($request, $selectedId) : null;

        return view('dashboard.index', [
            'chats' => $chats,
            'chat' => $chat,
        ]);
    }

    public function close(Request $request)
    {

        if ($id = $request->session()->get('selected_chat')) {
            $request->session()->forget('pending_messages.$id');

        }
        $request->session()->forget('selected_chat');

        return view('components.chat.show', ['chat' => null])->render();
    }

    public function partial(Request $request, int $id)
    {
        $request->session()->put('selected_chat', $id);
        $chat = $this->chatService->getChatById($id);

        return view('components.chat.show', ['chat' => $chat])->render();
    }
    public function receiveMessage(Request $request, int $id)
    {

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000']
        ]);
        $pendingKey = "pending_messages.$id";
        $pending = $request->session()->get($pendingKey, []);
        $pending[] = [
            'author' => 'client',
            'text' => $data['message'],
        ];
        $request->session()->put($pendingKey, $pending);

        $chat = $this->composeChat($request, $id);

        return view('components.chat.show', ['chat' => $chat])->render();
    }

    private function composeChat(Request $request, int $id): array
    {
        $chat = $this->chatService->getChatById($id);
        $pending = $request->session()->get("pending_messages.$id", []);
        $chat['messages'] = array_values(array_merge($chat['messages'] ?? [], $pending));
        return $chat;
    }


}