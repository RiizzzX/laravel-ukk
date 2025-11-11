<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register (username + email + password, name otomatis dari email)
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'username' => 'required|string|unique:users,username',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed', // expect password_confirmation
            ]);

            $user = User::create([
                'username' => $data['username'],
                'email'    => $data['email'],
                'name'     => $data['email'], // gunakan email sebagai name
                'password' => Hash::make($data['password']),
                'role'     => 'pengguna',
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            // Return user data without password
            $userData = [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
            ];

            return response()->json([
                'message' => 'Register berhasil',
                'user'    => $userData,
                'token'   => $token
            ], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Register error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Register gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // Login (bisa pakai username ATAU email)
    public function login(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string'
            ]);

            // Deteksi field yang digunakan: username, email, atau login
            $loginValue = null;
            $loginField = null;

            if ($request->has('username')) {
                $loginValue = $request->username;
                $loginField = 'username';
            } elseif ($request->has('email')) {
                $loginValue = $request->email;
                $loginField = 'email';
            } elseif ($request->has('login')) {
                $loginValue = $request->login;
                // Auto-detect apakah email atau username
                $loginField = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            } else {
                return response()->json([
                    'message' => 'Username atau email harus diisi'
                ], 422);
            }
            
            $user = User::where($loginField, $loginValue)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'User tidak ditemukan'
                ], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Password salah'
                ], 401);
            }

            // Validasi role - hanya pengguna yang boleh login via API mobile
            if ($user->role !== 'pengguna') {
                return response()->json([
                    'message' => 'Aplikasi mobile hanya untuk pengguna biasa. Petugas dan Admin silakan gunakan versi web.'
                ], 403);
            }

            // Delete old tokens to prevent token buildup
            $user->tokens()->delete();
            
            // Create new token
            $token = $user->createToken('api-token')->plainTextToken;

            // Return user data without password
            $userData = [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
            ];

            return response()->json([
                'message' => 'Login berhasil',
                'user' => $userData,
                'token' => $token
            ]);
            
        } catch (ValidationException $e) {
            // Return validation errors as 422
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            \Log::error('Login trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Login gagal: ' . $e->getMessage()
            ], 500);
        }
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

    // get user profile with details
    public function profile(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'message' => 'Profile berhasil dimuat',
            'data' => $user
        ]);
    }

    // update password only
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Verify old password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'message' => 'Password lama tidak sesuai'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diperbarui'
        ]);
    }

    // logout - revoke token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
