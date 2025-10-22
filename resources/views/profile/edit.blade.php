@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-3xl mx-auto">
    
    {{-- Header --}}
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
      <p class="text-gray-500">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
      <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200">
        <p class="font-semibold mb-2">Terjadi kesalahan:</p>
        <ul class="list-disc list-inside space-y-1">
          @foreach($errors->all() as $error)
            <li class="text-sm">{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form Profil --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-6">
      <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
          <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          Informasi Profil
        </h3>
      </div>
      
      <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-5">
        @csrf
        @method('PATCH')

        {{-- Avatar Display --}}
        <div class="flex items-center gap-4 pb-5 border-b">
          <div class="w-20 h-20 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-lg">{{ auth()->user()->username }}</p>
            <p class="text-sm text-gray-500">{{ auth()->user()->role }}</p>
          </div>
        </div>

        {{-- Username --}}
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
          <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
          @error('username')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Email --}}
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Divider --}}
        <div class="border-t pt-5">
          <h4 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Ubah Password
          </h4>
        </div>

        {{-- Password Baru --}}
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
          <input type="password" name="password"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                 placeholder="Minimal 6 karakter">
          <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                 placeholder="Ulangi password baru">
        </div>

        {{-- Submit Button --}}
        <div class="pt-4 border-t">
          <button type="submit" 
                  class="w-full px-6 py-3 rounded-lg bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold hover:from-purple-700 hover:to-pink-600 transition shadow-lg flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Perubahan
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection
