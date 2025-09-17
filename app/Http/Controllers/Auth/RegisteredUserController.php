<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form register
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'name'     => $request->name,
            'password' => Hash::make($request->password),
            'role'     => 'pengguna', // default role
        ]);

        event(new Registered($user));

        // langsung login
        Auth::login($user);

        // redirect sesuai role
        return redirect('/redirect-by-role');
    }
}
