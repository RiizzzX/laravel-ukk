@extends('layouts.guest')

@section('content')
  <div class="text-center mb-8">
    <div class="mx-auto w-16 h-16 rounded-full bg-purple-600 flex items-center justify-center shadow-lg">
      <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M12 11c0 1.66-1.34 3-3 3s-3-1.34-3-3 
             1.34-3 3-3 3 1.34 3 3zm7 0c0 1.66-1.34 3-3 3s-3-1.34-3-3 
             1.34-3 3-3 3 1.34 3 3z"/>
      </svg>
    </div>
    <h2 class="mt-4 text-2xl font-bold text-gray-800">Masuk Akun</h2>
    <p class="text-gray-500 text-sm">Gunakan username dan password Anda</p>
  </div>

  {{-- Notifikasi error --}}
  @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-lg">
      {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    {{-- Username --}}
    <div>
      <input type="text" name="username" value="{{ old('username') }}" required autofocus
        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
        placeholder="Username">
      @error('username')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Password --}}
    <div>
      <input type="password" name="password" required
        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
        placeholder="Kata sandi">
      @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Remember + Forgot --}}
    <div class="flex items-center justify-between text-sm">
      <label class="flex items-center gap-2 text-gray-600">
        <input type="checkbox" name="remember" class="rounded border-gray-300">
        Ingat saya
      </label>
      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-purple-600 hover:underline">Lupa password?</a>
      @endif
    </div>

    {{-- Submit --}}
    <button type="submit"
      class="w-full py-3 rounded-xl font-semibold text-white bg-purple-600 shadow-md hover:bg-purple-700 transition">
      Masuk
    </button>
  </form>

  {{-- Link register --}}
  <div class="mt-6 text-center text-sm text-gray-500">
    Belum punya akun?
    <a href="{{ route('register') }}" class="text-purple-600 font-semibold hover:underline">Daftar</a>
  </div>
@endsection
