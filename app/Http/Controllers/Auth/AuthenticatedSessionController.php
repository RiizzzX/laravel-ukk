<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate(); // pakai LoginRequest custom yang kamu buat

        $request->session()->regenerate();

        return redirect()->intended('/redirect-by-role');
    }

    /**
     * Logout
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
