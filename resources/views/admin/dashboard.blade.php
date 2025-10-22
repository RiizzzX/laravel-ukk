@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-500">Halo, {{ auth()->user()->username }}</p>
      </div>
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

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 mb-10">
      {{-- card total user --}}
      <div class="bg-gradient-to-br from-purple-500 via-purple-400 to-purple-300 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center gap-4">
          <div class="bg-white/25 p-3 rounded-xl backdrop-blur-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium opacity-90">Total User</h3>
            <p class="text-3xl font-bold">{{ $countUsers ?? 0 }}</p>
          </div>
        </div>
      </div>

      {{-- card total petugas --}}
      <div class="bg-gradient-to-br from-pink-500 via-pink-400 to-rose-300 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center gap-4">
          <div class="bg-white/25 p-3 rounded-xl backdrop-blur-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium opacity-90">Total Petugas</h3>
            <p class="text-3xl font-bold">{{ $countPetugas ?? 0 }}</p>
          </div>
        </div>
      </div>

      {{-- card total item --}}
      <div class="bg-gradient-to-br from-cyan-400 via-blue-400 to-sky-300 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center gap-4">
          <div class="bg-white/25 p-3 rounded-xl backdrop-blur-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium opacity-90">Total Item</h3>
            <p class="text-3xl font-bold">{{ $countItems ?? 0 }}</p>
          </div>
        </div>
      </div>

      {{-- card total lokasi --}}
      <div class="bg-gradient-to-br from-orange-400 via-amber-400 to-yellow-300 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center gap-4">
          <div class="bg-white/25 p-3 rounded-xl backdrop-blur-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium opacity-90">Total Lokasi</h3>
            <p class="text-3xl font-bold">{{ $countLokasi ?? 0 }}</p>
          </div>
        </div>
      </div>

      {{-- card total pengaduan --}}
      <div class="bg-gradient-to-br from-green-400 via-emerald-400 to-teal-300 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center gap-4">
          <div class="bg-white/25 p-3 rounded-xl backdrop-blur-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium opacity-90">Total Pengaduan</h3>
            <p class="text-3xl font-bold">{{ $countPengaduan ?? 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Tombol Generate Laporan --}}
    <div class="mb-8 flex justify-end">
      <a href="{{ route('admin.laporan') }}" class="bg-gradient-to-r from-purple-600 to-pink-500 text-white px-6 py-3 rounded-lg shadow flex items-center gap-2 font-semibold hover:from-purple-700 hover:to-pink-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h6m-6 0V7a4 4 0 00-4-4H5a4 4 0 00-4 4v10a4 4 0 004 4h6a4 4 0 004-4v-2"/>
        </svg>
        Generate Laporan
      </a>
    </div>

    {{-- Tabel Pengaduan Aktif --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8 border border-gray-100">
      <div class="px-6 py-4 bg-purple-100 border-b border-purple-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-800 text-lg">Pengaduan Aktif</h3>
            <p class="text-xs text-gray-500">Pending & Diproses ({{ count($pengaduanAktif) }} pengaduan)</p>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-purple-100">
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">ID</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Username</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Item</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Deskripsi</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Status (Klik untuk ubah)</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Tanggal</th>
              <th class="text-left px-6 py-4 text-sm font-semibold text-purple-900">Bukti</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($pengaduanAktif as $p)
              <tr class="hover:bg-purple-50/30 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $p->id_pengaduan }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->user->username ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->item->nama_item ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $p->deskripsi }}</td>
                <td class="px-6 py-4">
                  @if($p->status == 'pending')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 cursor-pointer hover:bg-yellow-200 transition-all"
                          onclick="openStatusModal({{ $p->id_pengaduan }}, 'pending')"
                          title="Klik untuk ubah status">
                      ⏳ Pending
                    </span>
                  @elseif($p->status == 'diproses')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 cursor-pointer hover:bg-blue-200 transition-all"
                          onclick="openStatusModal({{ $p->id_pengaduan }}, 'diproses')"
                          title="Klik untuk ubah status">
                      🔄 Diproses
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
                <td class="px-6 py-4">
                  @if($p->foto)
                    <img src="{{ asset('storage/'.$p->foto) }}" 
                         alt="Foto Bukti" 
                         class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 hover:scale-105 transition-all shadow-md border-2 border-purple-200"
                         onclick="showImageModal('{{ asset('storage/'.$p->foto) }}')"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EGambar%3C/text%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2265%25%22 text-anchor=%22middle%22%3ETidak Ada%3C/text%3E%3C/svg%3E'; this.classList.remove('cursor-pointer','hover:scale-105');"
                         title="Klik untuk memperbesar">
                  @else
                    <span class="text-xs text-gray-400 italic">Tidak ada bukti</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                  <p class="font-medium">Tidak ada pengaduan aktif</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
     {{-- Tabel Riwayat Pengaduan (Selesai) --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
      <div class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-green-50 border-b border-emerald-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center shadow">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Pengaduan</h3>
            <p class="text-xs text-gray-500">Selesai & Ditolak ({{ count($pengaduanSelesai) }} pengaduan)</p>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-emerald-50 border-b border-emerald-100">
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">ID</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">User</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Item</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Deskripsi</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Status</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Tanggal</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Bukti</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($pengaduanSelesai as $p)
              <tr class="hover:bg-emerald-50/30 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $p->id_pengaduan }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->user->username ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ $p->item->nama_item ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $p->deskripsi }}</td>
                <td class="px-6 py-4">
                  @if($p->status == 'selesai')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                      ✅ Selesai
                    </span>
                  @elseif($p->status == 'ditolak')
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                      ❌ Ditolak
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
                <td class="px-6 py-4">
                  @if($p->foto)
                    <img src="{{ asset('storage/'.$p->foto) }}" 
                         alt="Foto Bukti" 
                         class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 hover:scale-105 transition-all shadow-md border-2 border-emerald-200"
                         onclick="showImageModal('{{ asset('storage/'.$p->foto) }}')"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22100%22 height=%22100%22/%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EGambar%3C/text%3E%3Ctext fill=%22%23999%22 font-size=%2212%22 x=%2250%25%22 y=%2265%25%22 text-anchor=%22middle%22%3ETidak Ada%3C/text%3E%3C/svg%3E'; this.classList.remove('cursor-pointer','hover:scale-105');"
                         title="Klik untuk memperbesar">
                  @else
                    <span class="text-xs text-gray-400 italic">Tidak ada bukti</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center px-6 py-12 text-gray-400">
                  <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  <p class="font-medium">Belum ada pengaduan selesai</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Manajemen Pengguna --}}
    <div class="mb-6">
      <h2 class="text-xl font-bold text-gray-800">Manajemen Pengguna</h2>
    </div>

    {{-- Grid 2 Kolom untuk Petugas & User --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      {{-- Tabel Petugas --}}
      <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-4 font-semibold text-gray-700 border-b flex items-center justify-between">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.657 0 3-1.343 3-3S17.657 5 16 5s-3 1.343-3 3 1.343 3 3 3zM8 11c1.657 0 3-1.343 3-3S9.657 5 8 5 5 6.343 5 8s1.343 3 3 3z"/>
            </svg>
            <span>Petugas ({{ count($petugas) }})</span>
          </div>
          <a href="{{ route('admin.petugas.index') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition shadow">
            Kelola
          </a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead class="bg-purple-100">
              <tr>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">ID</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Username</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Email</th>
              </tr>
            </thead>
            <tbody>
              @forelse($petugas as $ptg)
                <tr class="border-t hover:bg-gray-50">
                  <td class="p-3 text-sm">{{ $ptg->id_petugas }}</td>
                  <td class="p-3 text-sm font-medium">{{ $ptg->nama_petugas }}</td>
                  <td class="p-3 text-sm">{{ $ptg->jabatan ?? '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center p-6 text-gray-500 text-sm">Belum ada petugas</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Tabel User --}}
      <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-4 font-semibold text-gray-700 border-b flex items-center justify-between">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>User ({{ count($users) }})</span>
          </div>
          <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition shadow">
            Kelola
          </a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead class="bg-purple-100">
              <tr>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">ID</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Username</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Role</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr class="border-t hover:bg-gray-50">
                  <td class="p-3 text-sm">{{ $user->id }}</td>
                  <td class="p-3 text-sm font-medium">{{ $user->username }}</td>
                  <td class="p-3 text-sm">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold
                      @if($user->role=='admin') bg-purple-100 text-purple-700
                      @elseif($user->role=='petugas') bg-blue-100 text-blue-700
                      @else bg-green-100 text-green-700 @endif">
                      {{ ucfirst($user->role) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center p-6 text-gray-500 text-sm">Belum ada user</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Daftar Lokasi & Sarana Prasarana --}}
    <div class="mb-6">
      <h2 class="text-xl font-bold text-gray-800">Daftar Lokasi & Sarana Prasarana</h2>
    </div>

    {{-- Grid 2 Kolom untuk Item & Lokasi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      {{-- Tabel Lokasi --}}
      <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-4 font-semibold text-gray-700 border-b flex items-center justify-between">
          <span>Daftar Lokasi</span>
          <a href="{{ route('admin.lokasi.index') }}" class="px-4 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition">
            Kelola
          </a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead class="bg-purple-100">
              <tr>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Nama Lokasi</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Keterangan</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Tindakan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($lokasi as $lok)
                <tr class="border-t hover:bg-gray-50">
                  <td class="p-3 text-sm font-medium">{{ $lok->nama_lokasi }}</td>
                  <td class="p-3 text-sm">-</td>
                  <td class="p-3">
                    <a href="{{ route('admin.lokasi.edit', $lok->id_lokasi) }}" class="px-3 py-1 rounded bg-orange-100 text-orange-600 text-xs font-semibold hover:bg-orange-200 inline-block">Edit</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center p-6 text-gray-500 text-sm">Belum ada lokasi</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Tabel Sarana & Prasarana --}}
      <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-4 font-semibold text-gray-700 border-b flex items-center justify-between">
          <span>Daftar Sarana & Prasarana</span>
          <a href="{{ route('admin.items.index') }}" class="px-4 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition">
            Kelola
          </a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead class="bg-purple-100">
              <tr>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Nama Item</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Lokasi</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Kondisi</th>
                <th class="text-left p-3 text-sm font-semibold text-purple-900">Tindakan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($items as $item)
                <tr class="border-t hover:bg-gray-50">
                  <td class="p-3 text-sm font-medium">{{ $item->nama_item }}</td>
                  <td class="p-3 text-sm">{{ $item->lokasiRelation->nama_lokasi ?? '-' }}</td>
                  <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">Baik</span>
                  </td>
                  <td class="p-3">
                    <a href="{{ route('admin.items.edit', $item->id_item) }}" class="px-3 py-1 rounded bg-orange-100 text-orange-600 text-xs font-semibold hover:bg-orange-200 inline-block">Edit</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center p-6 text-gray-500 text-sm">Belum ada item</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

   

  </div>
</div>

{{-- Modal untuk menampilkan foto besar --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="relative max-w-5xl max-h-full">
    <button type="button" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-all" id="closeModalBtn">
      <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
    <img id="modalImage" src="" alt="Foto Bukti" class="max-w-full max-h-[90vh] rounded-xl shadow-2xl">
  </div>
</div>

{{-- Modal Update Status --}}
<div id="statusModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
    <div class="p-6 border-b border-gray-200">
      <h3 class="text-xl font-bold text-gray-800">Ubah Status Pengaduan</h3>
      <p class="text-sm text-gray-500 mt-1">Pilih status baru untuk pengaduan ini</p>
    </div>
    <div class="p-6">
      <div class="space-y-3" id="statusOptions">
        <!-- Status options will be inserted here -->
      </div>
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
      <button onclick="closeStatusModal()" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
        Batal
      </button>
    </div>
  </div>
</div>

<script>
  function showImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    img.src = imageSrc;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }

  // Show status menu
  let currentPengaduanId = null;
  
  function openStatusModal(idPengaduan, currentStatus) {
    currentPengaduanId = idPengaduan;
    const modal = document.getElementById('statusModal');
    const optionsContainer = document.getElementById('statusOptions');
    
    // Clear previous options
    optionsContainer.innerHTML = '';
    
    // Define options based on current status
    const options = [];
    
    if (currentStatus === 'pending') {
      options.push(
        { label: '🔄 Diproses', value: 'diproses', bgColor: 'bg-blue-500 hover:bg-blue-600', desc: 'Pengaduan sedang ditangani' },
        { label: '✅ Selesai', value: 'selesai', bgColor: 'bg-green-500 hover:bg-green-600', desc: 'Pengaduan telah diselesaikan' },
        { label: '❌ Ditolak', value: 'ditolak', bgColor: 'bg-red-500 hover:bg-red-600', desc: 'Pengaduan ditolak' }
      );
    } else if (currentStatus === 'diproses') {
      options.push(
        { label: '✅ Selesai', value: 'selesai', bgColor: 'bg-green-500 hover:bg-green-600', desc: 'Pengaduan telah diselesaikan' },
        { label: '❌ Ditolak', value: 'ditolak', bgColor: 'bg-red-500 hover:bg-red-600', desc: 'Pengaduan ditolak' },
        { label: '⏳ Pending', value: 'pending', bgColor: 'bg-yellow-500 hover:bg-yellow-600', desc: 'Kembalikan ke pending' }
      );
    }
    
    // Create option buttons
    options.forEach(option => {
      const btn = document.createElement('button');
      btn.className = `w-full text-left p-4 ${option.bgColor} text-white rounded-xl transition-all transform hover:scale-105 shadow-md`;
      btn.innerHTML = `
        <div class=\"font-bold text-lg\">${option.label}</div>
        <div class=\"text-sm opacity-90 mt-1\">${option.desc}</div>
      `;
      btn.onclick = function() {
        updateStatus(option.value);
      };
      optionsContainer.appendChild(btn);
    });
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }
  
  function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPengaduanId = null;
  }
  
  function updateStatus(newStatus) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/pengaduan/${currentPengaduanId}/status`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = newStatus;
    
    form.appendChild(csrfToken);
    form.appendChild(statusInput);
    document.body.appendChild(form);
    form.submit();
  }

  // Close button click
  document.getElementById('closeModalBtn')?.addEventListener('click', function(e) {
    e.stopPropagation();
    closeImageModal();
  });

  // Click background to close
  document.getElementById('imageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
      closeImageModal();
    }
  });

  // Close modal dengan tombol Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeImageModal();
    }
  });
</script>
@endsection
