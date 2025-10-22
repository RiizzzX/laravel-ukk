<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register (username,email optional, we require username+password)
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // expect password_confirmation
        ]);

        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'pengguna',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Register berhasil',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    // Login (username or email)
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'nullable|string',
            'email'    => 'nullable|email',
            'password' => 'required|string'
        ]);

        // try username first, then email
        $user = null;
        if ($request->username) {
            $user = User::where('username', $request->username)->first();
        } elseif ($request->email) {
            $user = User::where('email', $request->email)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['credentials' => ['Login failed: invalid credentials']]);
        }

        // delete old tokens optionally: $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user'    => $user,
            'token'   => $token
        ]);
    }

    // get authenticated user
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // update profile (username/email/password)
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'username' => 'nullable|string|unique:users,username,' . $user->id_user . ',id_user',
            'email'    => 'nullable|email|unique:users,email,' . $user->id_user . ',id_user',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (isset($data['username'])) $user->username = $data['username'];
        if (array_key_exists('email', $data)) $user->email = $data['email'];
        if (!empty($data['password'])) $user->password = Hash::make($data['password']);

        $user->save();

        return response()->json(['message' => 'Profil diperbarui', 'user' => $user]);
    }
}
