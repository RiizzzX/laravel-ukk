@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Manajemen User</h1>
        <p class="text-gray-500">Kelola semua user sistem</p>
      </div>
      <button onclick="openModal('addUserModal')" 
              class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white px-6 py-3 rounded-lg shadow-sm flex items-center gap-2 font-semibold transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah User
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
        // Auto close modal on success
        document.addEventListener('DOMContentLoaded', function() {
          closeModal('addUserModal');
          closeModal('editUserModal');
        });
      </script>
    @endif
    @if(session('error'))
      <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
      </div>
    @endif

    {{-- Tabel User --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-purple-100">
              <th class="text-left p-4 text-sm font-semibold text-purple-900">ID</th>
              <th class="text-left p-4 text-sm font-semibold text-purple-900">Username</th>
              <th class="text-left p-4 text-sm font-semibold text-purple-900">Email</th>
              <th class="text-left p-4 text-sm font-semibold text-purple-900">Nama</th>
              <th class="text-left p-4 text-sm font-semibold text-purple-900">Role</th>
              <th class="text-left p-4 text-sm font-semibold text-purple-900">Tanggal Dibuat</th>
              <th class="text-left p-4 text-sm font-semibold text-purple-900">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              <tr class="border-t hover:bg-purple-50/30 transition">
                <td class="p-4">{{ $user->id_user }}</td>
                <td class="p-4 font-medium">{{ $user->username }}</td>
                <td class="p-4 text-sm text-gray-600">{{ $user->email }}</td>
                <td class="p-4">{{ $user->name }}</td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-sm
                    @if($user->role=='admin') bg-red-100 text-red-700
                    @elseif($user->role=='petugas') bg-blue-100 text-blue-700
                    @else bg-green-100 text-green-700 @endif">
                    {{ ucfirst($user->role) }}
                  </span>
                </td>
                <td class="p-4 text-sm text-gray-500">{{ $user->created_at->format('d M Y H:i') }}</td>
                <td class="p-4">
                  <div class="flex gap-2">
                    <button onclick="editUser({{ $user->id_user }}, '{{ $user->username }}', '{{ $user->email }}', '{{ $user->role }}')" 
                            class="px-3 py-1.5 rounded-lg bg-purple-100 text-purple-700 text-xs font-semibold hover:bg-purple-200 transition">
                      Edit
                    </button>
                    @if($user->id_user !== auth()->id())
                      <form action="{{ route('admin.users.destroy', $user->id_user) }}" 
                            method="POST" 
                            class="inline"
                            onsubmit="return confirm('Yakin hapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-xs font-semibold hover:bg-red-200 transition">
                          Hapus
                        </button>
                      </form>
                    @else
                      <span class="px-3 py-1 text-gray-400 text-sm italic">Anda</span>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center p-6 text-gray-500">Belum ada user</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      
      {{-- Pagination --}}
      @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
          {{ $users->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

{{-- Modal Tambah User --}}
<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
    <div class="p-6 border-b flex items-center justify-between">
      <h2 class="text-xl font-bold text-gray-800">Tambah User Baru</h2>
      <button onclick="closeModal('addUserModal')" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
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
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Username <span class="text-red-500">*</span>
        </label>
        <input type="text" name="username" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
               placeholder="Masukkan username">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Email <span class="text-red-500">*</span>
        </label>
        <input type="email" name="email" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
               placeholder="contoh@email.com">
        <p class="text-xs text-gray-500 mt-1">Format: nama@domain.com</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Nama Lengkap <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
               placeholder="Masukkan nama lengkap">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Password <span class="text-red-500">*</span>
        </label>
        <input type="password" name="password" required minlength="6"
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
               placeholder="Minimal 6 karakter">
        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
      </div>
      <div>
        <input type="hidden" name="role" value="pengguna">
      </div>
      <div class="flex gap-3 pt-4">
        <button type="submit" 
                class="flex-1 bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white py-2.5 rounded-lg font-semibold transition shadow-sm">
          Simpan
        </button>
        <button type="button" onclick="closeModal('addUserModal')" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 rounded-lg font-semibold transition">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit User --}}
<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
    <div class="p-6 border-b flex items-center justify-between">
      <h2 class="text-xl font-bold text-gray-800">Edit User</h2>
      <button onclick="closeModal('editUserModal')" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="editUserForm" method="POST" class="p-6 space-y-4">
      @csrf
      @method('PUT')
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Username <span class="text-red-500">*</span>
        </label>
        <input type="text" id="edit_username" name="username" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Email <span class="text-red-500">*</span>
        </label>
        <input type="email" id="edit_email" name="email" required 
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
               placeholder="contoh@email.com">
        <p class="text-xs text-gray-500 mt-1">Format: nama@domain.com</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password (Kosongkan jika tidak diubah)</label>
        <input type="password" id="edit_password" name="password" minlength="6"
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
               placeholder="Minimal 6 karakter (opsional)">
        <p class="text-xs text-gray-500 mt-1">Isi hanya jika ingin mengubah password</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
        <select id="edit_role" name="role" required
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
          <option value="pengguna">Pengguna</option>
          <option value="petugas">Petugas</option>
        </select>
      </div>
      <div class="flex gap-3 pt-4">
        <button type="submit" 
                class="flex-1 bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white py-2.5 rounded-lg font-semibold transition shadow-sm">
          Update
        </button>
        <button type="button" onclick="closeModal('editUserModal')" 
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

  function editUser(id, username, email, role) {
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    
    // Set role dropdown value
    const roleSelect = document.getElementById('edit_role');
    roleSelect.value = role;
    
    document.getElementById('edit_password').value = '';
    document.getElementById('editUserForm').action = '/admin/users/' + id;
    openModal('editUserModal');
  }
</script>
@endsection
