<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        error_log('Cheguei no controller: ' . json_encode($request->all()));
        try {
            $data = $this->authService->login($validated['email'], $validated['password']);
            $request->session()->put('agent', $data['user'] ?? []);
            $request->session()->put('token', $data['token'] ?? null);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }
    public function logout(Request $request)
    {
        $request->session()->forget('agent');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}