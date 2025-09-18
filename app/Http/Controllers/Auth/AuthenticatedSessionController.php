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
    public function store(Request $request)
{
    $request->validate([
        'login'    => 'required|string', // bisa username atau email
        'password' => 'required|string',
    ]);

    // Tentukan apakah login pakai email atau username
    $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (!Auth::attempt([$loginType => $request->login, 'password' => $request->password], $request->filled('remember'))) {
        return back()->withErrors([
            'login' => 'Login gagal! Periksa username/email dan password.',
        ])->onlyInput('login');
    }

    $request->session()->regenerate();

    // Redirect sesuai role
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'petugas') {
        return redirect()->route('petugas.dashboard');
    }
    return redirect()->route('pengaduan.index'); // default user
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
