<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if ($userRole !== $role) {
            // Redirect ke dashboard sesuai role user
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            } elseif ($userRole === 'petugas') {
                return redirect()->route('petugas.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            } else {
                return redirect()->route('user.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }

        return $next($request);
    }
}
