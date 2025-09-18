@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">

  <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Profil</h2>

  @if(session('status'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
      {{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
    @csrf
    @method('PATCH')

    {{-- Username --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Username</label>
      <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}"
             class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" required>
    </div>

    {{-- Email (opsional kalau pakai email) --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Email</label>
      <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
             class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
    </div>

    {{-- Password Baru --}}
    <div>
      <label class="block text-sm font-medium text-gray-700">Password Baru</label>
      <input type="password" name="password"
             class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
      <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin ganti password.</p>
    </div>

    {{-- Tombol --}}
    <div class="flex justify-between items-center pt-4 border-t">
      <button type="submit" 
              class="px-6 py-2 rounded-lg bg-purple-600 text-white font-semibold hover:bg-purple-700">
        Simpan Perubahan
      </button>

      {{-- Logout --}}
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" 
                class="px-6 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">
          Keluar
        </button>
      </form>
    </div>

  </form>
</div>
@endsection
