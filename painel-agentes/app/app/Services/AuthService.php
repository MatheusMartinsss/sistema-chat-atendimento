<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthService
{
    private string $baseUrl;
    private string $loginPath;
    private string $logoutPath;

    public function __construct()
    {
        $this->baseUrl = config('services.auth_api.base_uri');
        $this->loginPath = config('services.auth_api.login_path', '/auth/login');
        $this->logoutPath = config('services.auth_api.logout_path', '/auth/logout');
    }

    public function login(string $email, string $password): array
    {
       
        $resp = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->post($this->loginPath, [
                'email' => $email,
                'password' => $password,
            ]);

        if ($resp->failed()) {
            $msg = 'Falha ao autenticar.';
            if ($resp->status() === 401)
                $msg = 'Credenciais inválidas.';
            if ($resp->status() === 422)
                $msg = 'Dados inválidos.';
            throw new \Exception($msg, $resp->status()); 
        }

        return $resp->json(); // retorna o payload da API*/
    }

    public function logout(?string $token): void
    {
        if (!$token)
            return;

        Http::baseUrl($this->baseUrl)
            ->withToken($token)
            ->post($this->logoutPath);
    }
}