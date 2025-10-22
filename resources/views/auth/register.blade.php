@extends('layouts.guest')

@section('content')
  <div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Akun</h2>
    <p class="text-gray-500 text-sm">Buat akun baru untuk melanjutkan</p>
  </div>

  <form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <input type="text" name="username" value="{{ old('username') }}" required
      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500"
      placeholder="Username">

    <input type="text" name="name" value="{{ old('name') }}" required
      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500"
      placeholder="Nama Lengkap">

    <input type="password" name="password" required
      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500"
      placeholder="Password">

    <input type="password" name="password_confirmation" required
      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500"
      placeholder="Konfirmasi Password">

    <button type="submit"
      class="w-full py-3 rounded-xl font-semibold text-white bg-purple-600 shadow-md hover:bg-purple-700">
      Daftar
    </button>
  </form>

  <div class="mt-6 text-center text-sm text-gray-500">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="text-purple-600 font-semibold hover:underline">Masuk</a>
  </div>
@endsection
