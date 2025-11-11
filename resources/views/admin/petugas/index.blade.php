@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Petugas</h1>
        <p class="text-gray-500">Kelola data petugas</p>
      </div>
      <button onclick="openModal('addPetugasModal')" 
              class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white px-6 py-3 rounded-lg shadow-sm flex items-center gap-2 font-semibold transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Petugas
      </button>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
      <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
        <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-green-700 hover:text-green-900">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          closeModal('addPetugasModal');
          closeModal('editPetugasModal');
        });
      </script>
    @endif
    @if(session('error'))
      <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
      </div>
    @endif

{{-- Statistik Kinerja Petugas --}}
<div class="mb-10">
  <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
    <div class="p-1.5 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-md shadow-sm">
      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
      </svg>
    </div>
    Statistik Kinerja Petugas
  </h2>

  @php
    $jumlahPetugas = count($petugas);
    $cols = match(true) {
      $jumlahPetugas <= 1 => 'grid-cols-1',
      $jumlahPetugas === 2 => 'md:grid-cols-2',
      $jumlahPetugas === 3 => 'md:grid-cols-2 lg:grid-cols-3',
      default => 'sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'
    };
  @endphp

  <div class="grid {{ $cols }} gap-4">
    @foreach($petugas as $p)
      @php
        $selesai = $p->pengaduan->where('status', 'selesai')->count();
        $diproses = $p->pengaduan->where('status', 'diproses')->count();
        $total = $selesai + $diproses;
        $percentage = $total > 0 ? round(($selesai / $total) * 100) : 0;
        $radius = 42;
        $circumference = 2 * pi() * $radius;
        $offset = $circumference * (1 - $percentage / 100);
      @endphp

      <div class="bg-white rounded-lg border border-gray-200 transition-all duration-200 hover:border-purple-300">
        <div class="p-4">
          {{-- Header: Avatar + Nama --}}
          <div class="flex items-center gap-3 mb-4">
            <div class="relative">
              <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-base">
                {{ strtoupper(substr($p->nama_petugas, 0, 1)) }}
              </div>
              @if($percentage == 100)
                <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-green-500 border-2 border-white"></div>
              @endif
            </div>
            <div>
              <h3 class="font-medium text-gray-800 text-sm">{{ $p->nama_petugas }}</h3>
              <p class="text-xs text-gray-500">{{ $p->jabatan ?? 'Petugas' }}</p>
            </div>
          </div>

          {{-- Progress Circle - Ringkas --}}
          <div class="flex justify-center mb-3">
            <div class="relative w-24 h-24">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="{{ $radius }}" stroke="#E5E7EB" stroke-width="5" fill="none"/>
                <circle cx="50" cy="50" r="{{ $radius }}"
                        stroke="url(#grad-{{ $p->id_petugas }})"
                        stroke-width="5"
                        fill="none"
                        stroke-linecap="round"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $offset }}"
                        class="transition-all duration-1000 ease-out"/>
                <defs>
                  <linearGradient id="grad-{{ $p->id_petugas }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#8B5CF6"/>
                    <stop offset="100%" stop-color="#4F46E5"/>
                  </linearGradient>
                </defs>
              </svg>
              <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-lg font-bold text-gray-800">{{ $percentage }}%</span>
                <span class="text-xs text-gray-500 mt-0.5">Selesai</span>
              </div>
            </div>
          </div>

          {{-- Stats - Ringkas --}}
          <div class="space-y-2">
            <div class="flex justify-between text-xs">
              <span class="text-gray-600 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                Selesai
              </span>
              <span class="font-medium text-purple-700">{{ $selesai }}</span>
            </div>
            <div class="flex justify-between text-xs">
              <span class="text-gray-600 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Diproses
              </span>
              <span class="font-medium text-indigo-700">{{ $diproses }}</span>
            </div>
            <div class="pt-2 border-t border-gray-100 flex justify-between">
              <span class="text-xs text-gray-700">Total</span>
              <span class="font-bold text-gray-800">{{ $total }}</span>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

    {{-- Tabel Petugas --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead class="bg-gradient-to-r from-purple-50 to-indigo-50">
            <tr>
              <th class="text-left p-5 text-sm font-bold text-purple-800 uppercase tracking-wider border-b border-gray-200">ID</th>
              <th class="text-left p-5 text-sm font-bold text-purple-800 uppercase tracking-wider border-b border-gray-200">Nama Petugas</th>
              <th class="text-left p-5 text-sm font-bold text-purple-800 uppercase tracking-wider border-b border-gray-200">Username</th>
              <th class="text-left p-5 text-sm font-bold text-purple-800 uppercase tracking-wider border-b border-gray-200">Email</th>
              <th class="text-left p-5 text-sm font-bold text-purple-800 uppercase tracking-wider border-b border-gray-200">Jabatan</th>
              <th class="text-left p-5 text-sm font-bold text-purple-800 uppercase tracking-wider border-b border-gray-200">Tanggal Dibuat</th>
              <th class="text-left p-5 text-sm font-bold text-purple-800 uppercase tracking-wider border-b border-gray-200">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($petugas as $p)
              <tr class="border-b border-gray-100 hover:bg-purple-50/50 transition-colors duration-200">
                <td class="p-5 font-semibold text-gray-700">{{ $p->id_petugas }}</td>
                <td class="p-5 font-medium text-gray-800">{{ $p->nama_petugas }}</td>
                <td class="p-5 text-gray-600">{{ $p->user->username ?? '-' }}</td>
                <td class="p-5 text-gray-600">{{ $p->user->email ?? '-' }}</td>
                <td class="p-5">
                  <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                    {{ $p->jabatan ?? '-' }}
                  </span>
                </td>
                <td class="p-5 text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
                <td class="p-5">
                  <div class="flex gap-2">
                    <button onclick="editPetugas({{ $p->id_petugas }}, '{{ $p->nama_petugas }}', '{{ $p->jabatan }}')"
                            class="px-4 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-sm font-semibold hover:from-purple-600 hover:to-indigo-600 transition shadow-sm">
                      Edit
                    </button>
                    <form action="{{ route('admin.petugas.destroy', $p->id_petugas) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus petugas ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="px-4 py-2 rounded-lg bg-gradient-to-r from-red-500 to-red-400 text-white text-sm font-semibold hover:from-red-600 hover:to-red-500 transition shadow-sm">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center p-12 text-gray-500">
                  <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-lg font-medium">Belum ada petugas</p>
                    <p class="text-sm text-gray-500">Tambahkan petugas pertama Anda</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

{{-- Modal Tambah Petugas --}}
<div id="addPetugasModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
    <div class="p-6 border-b flex items-center justify-between">
      <h2 class="text-xl font-bold text-gray-800">Tambah Petugas Baru</h2>
      <button onclick="closeModal('addPetugasModal')" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form action="{{ route('admin.petugas.store') }}" method="POST" class="p-6 space-y-4">
      @csrf
      @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Petugas</label>
        <input type="text" name="nama_petugas" required 
               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none border-gray-300 transition">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
        <input type="text" name="username" required 
               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none border-gray-300 transition">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" required 
               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none border-gray-300 transition"
               placeholder="contoh@email.com">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
        <input type="text" name="jabatan" 
               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none border-gray-300 transition"
               placeholder="Contoh: Teknisi, Staff IT">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" name="password" required 
               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none border-gray-300 transition">
      </div>
      <div class="flex gap-3 pt-4">
        <button type="submit" 
                class="flex-1 bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white py-3 rounded-lg font-semibold transition shadow-sm">
          Simpan
        </button>
        <button type="button" onclick="closeModal('addPetugasModal')" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit Petugas --}}
<div id="editPetugasModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
    <div class="p-6 border-b flex items-center justify-between">
      <h2 class="text-xl font-bold text-gray-800">Edit Petugas</h2>
      <button onclick="closeModal('editPetugasModal')" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="editPetugasForm" method="POST" class="p-6 space-y-4">
      @csrf
      @method('PUT')
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Petugas</label>
        <input type="text" id="edit_nama_petugas" name="nama_petugas" required 
               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none border-gray-300 transition">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
        <input type="text" id="edit_jabatan" name="jabatan" 
               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none border-gray-300 transition">
      </div>
      <div class="flex gap-3 pt-4">
        <button type="submit" 
                class="flex-1 bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white py-3 rounded-lg font-semibold transition shadow-sm">
          Update
        </button>
        <button type="button" onclick="closeModal('editPetugasModal')" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
  }

  function editPetugas(id, nama, jabatan) {
    document.getElementById('edit_nama_petugas').value = nama;
    document.getElementById('edit_jabatan').value = jabatan || '';
    document.getElementById('editPetugasForm').action = '/admin/petugas/' + id;
    openModal('editPetugasModal');
  }
</script>
@endsection