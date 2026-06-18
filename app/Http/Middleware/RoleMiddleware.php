<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        if (!$user || $user->role->name !== $role) {
            return redirect()->route('error.access_denied')->with('error', 'Akses Ditolak: Anda tidak memiliki hak akses (' . $role . ').');
        }

        return $next($request);
    }
}
