@extends('layouts.app')

@section('content')
<style>
  /* Modern smooth form styling */
  select {
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.25em 1.25em;
  }
  
  select:disabled {
    background-color: #f3f4f6;
    cursor: not-allowed;
  }
  
  /* Smooth transitions */
  input, select, textarea {
    transition: all 0.3s ease;
  }
  
  input:focus, select:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
  }
  
  /* Radio button animation */
  input[type="radio"]:checked + div {
    transform: scale(1.02);
  }
  
  /* Checkbox animation */
  input[type="checkbox"] {
    transition: all 0.2s ease;
  }
  
  input[type="checkbox"]:checked {
    transform: scale(1.1);
  }
</style>

<div class="max-w-3xl mx-auto">
  <div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Buat Pengaduan Baru</h1>
    <p class="text-gray-500 mb-6">Lengkapi form berikut untuk melaporkan kerusakan / kebutuhan sarana prasarana.</p>

    @if($errors->any())
      <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf

      {{-- PILIHAN: Pengaduan Normal atau Temporary Item --}}
      <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-xl p-5">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-800">Pilih Tipe Pengaduan</h3>
            <p class="text-xs text-gray-600">Pilih salah satu sesuai kebutuhan Anda</p>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          {{-- Option 1: Pengaduan Normal --}}
          <label class="relative cursor-pointer">
            <input type="radio" name="tipe_pengaduan" value="normal" checked class="peer sr-only" onchange="toggleTipePengaduan()">
            <div class="p-4 border-2 rounded-xl transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:shadow-lg hover:border-purple-300">
              <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="font-bold text-sm text-gray-800">Pengaduan Normal</p>
                  <p class="text-xs text-gray-600">Item & lokasi sudah ada</p>
                </div>
              </div>
            </div>
          </label>

          {{-- Option 2: Temporary Item --}}
          <label class="relative cursor-pointer">
            <input type="radio" name="tipe_pengaduan" value="temporary" class="peer sr-only" onchange="toggleTipePengaduan()">
            <div class="p-4 border-2 rounded-xl transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:shadow-lg hover:border-orange-300">
              <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="font-bold text-sm text-gray-800">Ajukan Item/Lokasi Baru</p>
                  <p class="text-xs text-gray-600">Belum ada di sistem</p>
                </div>
              </div>
            </div>
          </label>
        </div>
      </div>

      {{-- FORM NORMAL PENGADUAN --}}
      <div id="formNormal" class="space-y-5">
        {{-- Pilih Lokasi --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            📍 Pilih Lokasi <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <select name="id_lokasi" id="lokasi" 
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 appearance-none bg-white transition-all"
              onchange="filterItems()" required>
              <option value="">-- Pilih Lokasi Terlebih Dahulu --</option>
              @foreach($lokasi as $l)
                <option value="{{ $l->id_lokasi }}">{{ $l->nama_lokasi }}</option>
              @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </div>
          </div>
        </div>

        {{-- Pilih Item --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            📦 Pilih Item/Barang <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <select name="id_item" id="item"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 appearance-none bg-white transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
              required>
              <option value="">Pilih lokasi terlebih dahulu</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-1">💡 Item akan muncul setelah memilih lokasi</p>
        </div>
      </div>

      {{-- FORM TEMPORARY ITEM --}}
      <div id="formTemporary" class="hidden space-y-5">
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 border-l-4 border-orange-500 p-5 rounded-xl">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-bold text-orange-900 mb-2">📝 Cara Mengajukan Item/Lokasi Baru:</p>
              <ul class="text-sm text-orange-800 space-y-1.5">
                <li class="flex items-start gap-2">
                  <span class="text-orange-500 font-bold mt-0.5">•</span>
                  <span>Anda bisa mengajukan <strong>item baru</strong> (contoh: Proyektor Ruang 301)</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-orange-500 font-bold mt-0.5">•</span>
                  <span>Atau mengajukan <strong>lokasi baru</strong> (contoh: Gedung Baru Lantai 4)</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-orange-500 font-bold mt-0.5">•</span>
                  <span>Atau <strong>keduanya</strong> sekaligus</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-orange-500 font-bold mt-0.5">•</span>
                  <span>Admin akan <strong>review</strong> dan approve jika valid</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        {{-- Ajukan Item Baru --}}
        <div class="border-2 border-blue-300 rounded-xl p-5 bg-gradient-to-br from-blue-50 to-cyan-50 transition-all hover:shadow-md">
          <label class="flex items-center gap-3 cursor-pointer mb-4">
            <input type="checkbox" name="ajukan_item_baru" value="1" id="checkItemBaru" 
              class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer" 
              onchange="toggleInputItemBaru()">
            <span class="font-bold text-gray-800 text-base">📦 Ajukan Item/Barang Baru</span>
          </label>
          <div id="inputItemBaru" class="hidden space-y-2">
            <input type="text" name="nama_barang_baru" id="namaBarangBaru"
                   class="w-full px-4 py-3 border-2 border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                   placeholder="Contoh: Proyektor Epson EB-X05, Kursi Lipat Chitose">
            <p class="text-xs text-blue-700 flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
              </svg>
              <span>Berikan nama yang jelas dan spesifik (merk, tipe, lokasi)</span>
            </p>
          </div>
        </div>

        {{-- Ajukan Lokasi Baru --}}
        <div class="border-2 border-green-300 rounded-xl p-5 bg-gradient-to-br from-green-50 to-emerald-50 transition-all hover:shadow-md">
          <label class="flex items-center gap-3 cursor-pointer mb-4">
            <input type="checkbox" name="ajukan_lokasi_baru" value="1" id="checkLokasiBaru" 
              class="w-5 h-5 text-green-600 rounded focus:ring-2 focus:ring-green-500 cursor-pointer" 
              onchange="toggleInputLokasiBaru()">
            <span class="font-bold text-gray-800 text-base">📍 Ajukan Lokasi Baru</span>
          </label>
          <div id="inputLokasiBaru" class="hidden space-y-2">
            <input type="text" name="lokasi_barang_baru" id="lokasiBarangBaru"
                   class="w-full px-4 py-3 border-2 border-green-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                   placeholder="Contoh: Gedung Baru Lantai 4, Lab Fisika 2">
            <p class="text-xs text-green-700 flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span>Sertakan detail gedung/lantai/ruangan dengan jelas</span>
            </p>
          </div>
        </div>

        <div id="warningTemporary" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-xl">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-yellow-800 font-semibold">Centang minimal salah satu: Item Baru atau Lokasi Baru</p>
          </div>
        </div>
      </div>

      {{-- Deskripsi --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          📝 Deskripsi Pengaduan <span class="text-red-500">*</span>
        </label>
        <textarea name="deskripsi" rows="5" required
          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all resize-none"
          placeholder="Contoh: AC di kelas tidak dingin, sudah 2 hari tidak berfungsi. Perlu perbaikan segera karena cuaca panas."></textarea>
        <p class="text-xs text-gray-500 mt-1">💡 Jelaskan kondisi, kapan terjadi, dan urgensi perbaikan</p>
      </div>

      {{-- Upload Foto Bukti --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          📸 Upload Foto Bukti 
          <span id="fotoRequiredLabel" class="text-red-500">*</span>
          <span id="fotoOptionalLabel" class="hidden text-gray-400">(Opsional)</span>
        </label>
        <div class="relative">
          <input type="file" name="foto" id="fotoInput"
            accept="image/jpeg,image/png,image/jpg,image/webp"
            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all
                   file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                   file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700
                   hover:file:bg-purple-100 cursor-pointer">
        </div>
        <p class="text-xs text-gray-500 mt-2" id="fotoHint">
          <span class="inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Format: JPG/PNG/WEBP, maksimal 2MB
          </span>
        </p>
        @error('foto')
          <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $message }}
          </p>
        @enderror
      </div>

      {{-- Submit Buttons --}}
      <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
        <a href="{{ route('pengaduan.index') }}" 
          class="flex-1 sm:flex-none px-6 py-3 rounded-xl border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all text-center">
          ← Batal
        </a>
        <button type="submit"
          class="flex-1 px-8 py-3 rounded-xl text-white font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
          <span class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Kirim Pengaduan
          </span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Data items dengan lokasi via list_lokasi
  const itemsData = @json($items);
  
  // Toggle between Normal and Temporary form
  function toggleTipePengaduan() {
    const tipe = document.querySelector('input[name="tipe_pengaduan"]:checked').value;
    const formNormal = document.getElementById('formNormal');
    const formTemporary = document.getElementById('formTemporary');
    const fotoInput = document.getElementById('fotoInput');
    const fotoRequiredLabel = document.getElementById('fotoRequiredLabel');
    const fotoOptionalLabel = document.getElementById('fotoOptionalLabel');
    const fotoHint = document.getElementById('fotoHint');
    const lokasiSelect = document.getElementById('lokasi');
    const itemSelect = document.getElementById('item');
    
    if (tipe === 'normal') {
      formNormal.classList.remove('hidden');
      formTemporary.classList.add('hidden');
      lokasiSelect.required = true;
      itemSelect.required = true;
      fotoInput.required = true;
      fotoRequiredLabel.classList.remove('hidden');
      fotoOptionalLabel.classList.add('hidden');
      fotoHint.textContent = 'Format: JPG/PNG, maksimal 2MB (Wajib)';
      
      // Reset temporary inputs
      document.getElementById('checkItemBaru').checked = false;
      document.getElementById('checkLokasiBaru').checked = false;
      document.getElementById('inputItemBaru').classList.add('hidden');
      document.getElementById('inputLokasiBaru').classList.add('hidden');
      document.getElementById('namaBarangBaru').value = '';
      document.getElementById('lokasiBarangBaru').value = '';
    } else {
      formNormal.classList.add('hidden');
      formTemporary.classList.remove('hidden');
      lokasiSelect.required = false;
      itemSelect.required = false;
      fotoInput.required = false;
      fotoRequiredLabel.classList.add('hidden');
      fotoOptionalLabel.classList.remove('hidden');
      fotoHint.textContent = 'Format: JPG/PNG, maksimal 2MB (Opsional untuk temporary item)';
      
      // Reset normal inputs
      lokasiSelect.value = '';
      itemSelect.value = '';
      itemSelect.innerHTML = '<option value="">Pilih lokasi terlebih dahulu</option>';
    }
  }
  
  function toggleInputItemBaru() {
    const checkbox = document.getElementById('checkItemBaru');
    const input = document.getElementById('inputItemBaru');
    const inputField = document.getElementById('namaBarangBaru');
    
    if (checkbox.checked) {
      input.classList.remove('hidden');
      inputField.required = true;
    } else {
      input.classList.add('hidden');
      inputField.required = false;
      inputField.value = '';
    }
    updateWarningTemporary();
  }
  
  function toggleInputLokasiBaru() {
    const checkbox = document.getElementById('checkLokasiBaru');
    const input = document.getElementById('inputLokasiBaru');
    const inputField = document.getElementById('lokasiBarangBaru');
    
    if (checkbox.checked) {
      input.classList.remove('hidden');
      inputField.required = true;
    } else {
      input.classList.add('hidden');
      inputField.required = false;
      inputField.value = '';
    }
    updateWarningTemporary();
  }
  
  function updateWarningTemporary() {
    const hasItem = document.getElementById('checkItemBaru').checked;
    const hasLokasi = document.getElementById('checkLokasiBaru').checked;
    const warning = document.getElementById('warningTemporary');
    
    if (hasItem || hasLokasi) {
      warning.classList.add('hidden');
    } else {
      warning.classList.remove('hidden');
    }
  }
  
  function filterItems() {
    const lokasiId = document.getElementById('lokasi').value;
    const itemSelect = document.getElementById('item');
    
    // Clear current options
    itemSelect.innerHTML = '<option value="">Pilih Item</option>';
    
    if (!lokasiId) {
      itemSelect.innerHTML = '<option value="">Pilih lokasi terlebih dahulu</option>';
      itemSelect.disabled = true;
      return;
    }
    
    // Filter items yang ada di lokasi ini (via list_lokasi)
    const availableItems = [];
    itemsData.forEach(item => {
      const hasLocation = item.list_lokasi && item.list_lokasi.some(ll => ll.id_lokasi == lokasiId);
      if (hasLocation) {
        availableItems.push(item);
      }
    });
    
    // Add available items to select
    availableItems.forEach(item => {
      const option = document.createElement('option');
      option.value = item.id_item;
      option.textContent = item.nama_item;
      itemSelect.appendChild(option);
    });
    
    if (availableItems.length === 0) {
      itemSelect.innerHTML = '<option value="">Tidak ada item di lokasi ini</option>';
    }
    
    itemSelect.disabled = false;
  }
  
  // Disable item select on load
  document.getElementById('item').disabled = true;
</script>
@endsection
