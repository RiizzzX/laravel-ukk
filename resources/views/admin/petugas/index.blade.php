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
      <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
      </div>
    @endif



    {{-- Tabel Petugas --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead class="bg-purple-50/50">
            <tr>
              <th class="text-left p-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">ID</th>
              <th class="text-left p-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Nama Petugas</th>
              <th class="text-left p-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Jabatan</th>
              <th class="text-left p-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Tanggal Dibuat</th>
              <th class="text-left p-4 text-xs font-semibold text-purple-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($petugas as $p)
              <tr class="border-t hover:bg-gray-50">
                <td class="p-4">{{ $p->id_petugas }}</td>
                <td class="p-4 font-medium">{{ $p->nama_petugas }}</td>
                <td class="p-4">{{ $p->jabatan ?? '-' }}</td>
                <td class="p-4 text-sm text-gray-500">{{ $p->created_at->format('d M Y H:i') }}</td>
                <td class="p-4">
                  <div class="flex gap-2">
                    <button onclick="editPetugas({{ $p->id_petugas }}, '{{ $p->nama_petugas }}', '{{ $p->jabatan }}')"
                            class="px-3 py-1.5 rounded-lg bg-purple-100 text-purple-700 text-xs font-semibold hover:bg-purple-200 transition">
                      Edit
                    </button>
                    <form action="{{ route('admin.petugas.destroy', $p->id_petugas) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus petugas ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-semibold hover:bg-red-200 transition">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center p-6 text-gray-500">Belum ada petugas</td>
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
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Petugas</label>
        <input type="text" name="nama_petugas" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
        <input type="text" name="jabatan" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
        <input type="text" name="username" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" name="password" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
      </div>
      <div class="flex gap-3 pt-4">
        <button type="submit" 
                class="flex-1 bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white py-2.5 rounded-lg font-semibold transition shadow-sm">
          Simpan
        </button>
        <button type="button" onclick="closeModal('addPetugasModal')" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 rounded-lg font-semibold transition">
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
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
        <input type="text" id="edit_jabatan" name="jabatan" 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
      </div>
      <div class="flex gap-3 pt-4">
        <button type="submit" 
                class="flex-1 bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white py-2.5 rounded-lg font-semibold transition shadow-sm">
          Update
        </button>
        <button type="button" onclick="closeModal('editPetugasModal')" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 rounded-lg font-semibold transition">
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
