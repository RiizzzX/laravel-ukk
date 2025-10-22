@extends('layouts.app')

@section('title', 'Saran Item Sarpras')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Saran Item Sarana & Prasarana</h1>
        <p class="text-gray-600">Ajukan saran item baru untuk ditambahkan ke sistem</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-8">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Form Saran Item</h2>
            <p class="text-sm text-gray-600">Isi formulir di bawah untuk mengajukan saran penambahan item baru</p>
        </div>

        <form action="{{ route('pengaduan.storeSaran') }}" method="POST">
            @csrf

            <!-- Nama Item -->
            <div class="mb-6">
                <label for="nama_item" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Item <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nama_item" 
                    id="nama_item"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('nama_item') border-red-500 @enderror"
                    placeholder="Contoh: Proyektor LCD"
                    value="{{ old('nama_item') }}"
                    required>
                @error('nama_item')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lokasi -->
            <div class="mb-6">
                <label for="id_lokasi" class="block text-sm font-semibold text-gray-700 mb-2">
                    Lokasi <span class="text-red-500">*</span>
                </label>
                <select 
                    name="id_lokasi" 
                    id="id_lokasi"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('id_lokasi') border-red-500 @enderror"
                    required>
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($lokasi as $lok)
                        <option value="{{ $lok->id_lokasi }}" {{ old('id_lokasi') == $lok->id_lokasi ? 'selected' : '' }}>
                            {{ $lok->nama_lokasi }}
                        </option>
                    @endforeach
                </select>
                @error('id_lokasi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                    Deskripsi / Alasan Pengajuan <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="deskripsi" 
                    id="deskripsi"
                    rows="5"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none @error('deskripsi') border-red-500 @enderror"
                    placeholder="Jelaskan mengapa item ini perlu ditambahkan dan manfaatnya..."
                    required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2">Jelaskan secara detail alasan pengajuan dan manfaat item yang diusulkan</p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-4 pt-4">
                <button 
                    type="submit"
                    class="flex items-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Saran
                </button>
                <a 
                    href="{{ route('pengaduan.index') }}"
                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg mt-8">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-blue-800 font-semibold mb-2">Informasi</h3>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>Saran Anda akan ditinjau oleh admin sebelum ditambahkan ke sistem</li>
                    <li>Pastikan nama item yang diusulkan belum ada dalam daftar item yang tersedia</li>
                    <li>Berikan alasan yang jelas mengapa item tersebut diperlukan</li>
                    <li>Status saran Anda dapat dilihat di halaman Tabel Pengaduan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
