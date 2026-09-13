<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Tafadhali ingia kwanza kuendelea.');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'vendor') {
                return redirect()->route('vendor.dashboard');
            } else {
                return redirect()->route('home')->with('error', 'Huna ruhusa ya kufikia ukurasa huu.');
            }
        }

        return $next($request);
    }
}
