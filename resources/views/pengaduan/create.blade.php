@extends('layouts.app')

@section('content')
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

      {{-- Pilih Lokasi --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Pilih Lokasi</label>
        <select name="id_lokasi" id="lokasi" required
          class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500"
          onchange="filterItems()">
          <option value="">Pilih Lokasi Terlebih Dahulu</option>
          @foreach($lokasi as $l)
            <option value="{{ $l->id_lokasi }}">{{ $l->nama_lokasi }}</option>
          @endforeach
        </select>
      </div>

      {{-- Pilih Item --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Pilih Item</label>
        <select name="id_item" id="item"
          class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500"
          onchange="toggleTemporaryItem()">
          <option value="">Pilih lokasi terlebih dahulu</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">Item akan muncul setelah memilih lokasi</p>
      </div>

      {{-- Input Temporary Item --}}
      <div id="temporaryItemDiv" class="hidden">
        <label class="block text-sm font-medium text-gray-700">
          Nama Item Temporary <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nama_item_temporary" id="namaItemTemporary"
          class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500"
          placeholder="Masukkan nama item yang tidak ada di list...">
        <p class="text-xs text-gray-500 mt-1">Item ini akan disimpan sementara dan perlu persetujuan admin</p>
      </div>

      {{-- Deskripsi --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi Pengaduan</label>
        <textarea name="deskripsi" rows="4" required
          class="mt-1 w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500"
          placeholder="Tuliskan keluhan atau kebutuhan sarpras dengan jelas..."></textarea>
      </div>

      {{-- Upload Foto Bukti --}}
      <div>
        <label class="block text-sm font-medium text-gray-700">
          Upload Foto Bukti <span class="text-red-500">*</span>
        </label>
        <input type="file" name="foto" required
          accept="image/jpeg,image/png"
          class="mt-1 w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-purple-500">
        <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, maksimal 5MB (Wajib)</p>
        @error('foto')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <div class="flex justify-end">
        <a href="{{ route('pengaduan.index') }}" class="px-4 py-2 rounded-xl border mr-3">Batal</a>
        <button type="submit"
          class="px-6 py-3 rounded-xl text-white font-semibold bg-purple-600 hover:bg-purple-700 shadow">
          Kirim Pengaduan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Data items dengan lokasi via list_lokasi
  const itemsData = @json($items);
  
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
      // Cek apakah item ini ada di lokasi yang dipilih
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
    
    // Add "Item Lainnya" option
    const otherOption = document.createElement('option');
    otherOption.value = 'temporary';
    otherOption.textContent = '➕ Item Lainnya (Temporary)';
    otherOption.className = 'font-semibold text-purple-600';
    itemSelect.appendChild(otherOption);
    
    if (availableItems.length === 0) {
      itemSelect.innerHTML = '<option value="">Tidak ada item di lokasi ini</option>';
      // Still add temporary option
      const tempOption = document.createElement('option');
      tempOption.value = 'temporary';
      tempOption.textContent = '➕ Item Lainnya (Temporary)';
      tempOption.className = 'font-semibold text-purple-600';
      itemSelect.appendChild(tempOption);
    }
    
    itemSelect.disabled = false;
  }
  
  function toggleTemporaryItem() {
    const itemSelect = document.getElementById('item');
    const temporaryDiv = document.getElementById('temporaryItemDiv');
    const temporaryInput = document.getElementById('namaItemTemporary');
    
    if (itemSelect.value === 'temporary') {
      temporaryDiv.classList.remove('hidden');
      temporaryInput.required = true;
      itemSelect.removeAttribute('required');
    } else {
      temporaryDiv.classList.add('hidden');
      temporaryInput.required = false;
      temporaryInput.value = '';
      itemSelect.setAttribute('required', 'required');
    }
  }
  
  // Disable item select on load
  document.getElementById('item').disabled = true;
</script>
@endsection
