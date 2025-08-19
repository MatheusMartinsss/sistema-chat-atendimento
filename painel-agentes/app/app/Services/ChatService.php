<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatService
{
    private string $baseUrl;


    public function __construct()
    {
        $this->baseUrl = config('services.api.url');

    }

    public function getChats(): array
    {

        $resp = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->get('/chats');

        if ($resp->failed()) {
            $msg = 'Falha ao autenticar.';
            if ($resp->status() === 401)
                $msg = 'Credenciais inválidas.';
            if ($resp->status() === 422)
                $msg = 'Dados inválidos.';
            throw new \Exception($msg, $resp->status());
        }

        return $resp->json();
    }
    public function getChatById(int $id): array
    {
        $resp = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->get("/chat/$id")
            ->throw();

        return $resp->json();
    }

}