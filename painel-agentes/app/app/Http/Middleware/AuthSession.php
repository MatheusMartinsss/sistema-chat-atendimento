<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\GenericUser;
class AuthSession
{

    public function handle(Request $request, Closure $next)
    {
        $agent = $request->session()->get('agent');
        $token = $request->session()->get('token');

        if ($agent && $token) {
         
            if (!Auth::check()) {
                $attributes = [
                    'id'    => $agent['id'] ?? null,
                    'name'  => $agent['name'] ?? null,
                    'email' => $agent['email'] ?? null,
                ];
                Auth::setUser(new GenericUser($attributes));
            }

            return $next($request);
        }

        session()->forget(['agent', 'token']);
        return redirect()->route('login');

    }
}