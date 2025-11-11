@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-3xl mx-auto">
    
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Edit Lokasi</h1>
      <p class="text-gray-500">Perbarui informasi lokasi</p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
      <form action="{{ route('admin.lokasi.update', $lokasi->id_lokasi) }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lokasi</label>
          <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}"
                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                 placeholder="Masukkan nama lokasi" required>
          @error('nama_lokasi')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Items</label>
          
          <!-- Search Item -->
          <div class="mb-3">
            <div class="relative mb-2">
              <input type="text" id="search-item" 
                     class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500" 
                     placeholder="Cari item berdasarkan inisial (misal: 'ku' untuk Kursi)..."
                     autocomplete="off">
              <div id="search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto">
                <!-- Results akan muncul di sini -->
              </div>
            </div>
            
            <!-- Selected Item Preview (hidden by default) -->
            <div id="selected-item-preview" class="hidden p-3 bg-purple-50 border border-purple-200 rounded-lg">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-purple-700">Item Terpilih:</span>
                <button type="button" onclick="cancelSelection()" class="text-xs text-red-600 hover:text-red-800">Batal</button>
              </div>
              <div class="flex items-center gap-2">
                <span id="selected-item-name" class="text-sm text-gray-800 font-medium"></span>
                <button type="button" onclick="confirmAddItem()" 
                        class="ml-auto px-4 py-1.5 bg-purple-600 text-white rounded-lg text-xs font-semibold hover:bg-purple-700 transition">
                  + Tambah Item Ini
                </button>
              </div>
            </div>
          </div>

          <div id="items-container" class="space-y-2">
            @php
              $existingItems = is_string($lokasi->items) ? json_decode($lokasi->items, true) : ($lokasi->items ?? []);
            @endphp
            @if(is_array($existingItems) && count($existingItems) > 0)
              @foreach($existingItems as $index => $item)
                <div class="flex gap-2" id="item-{{ $index }}">
                  <input type="text" name="items[{{ $index }}][nama_item]" 
                         value="{{ $item['nama_item'] ?? '' }}"
                         class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500" 
                         placeholder="Nama item" required>
                  <input type="number" name="items[{{ $index }}][jumlah]" 
                         value="{{ $item['jumlah'] ?? 1 }}"
                         class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500" 
                         placeholder="Jumlah" min="1">
                  <select name="items[{{ $index }}][kondisi]" 
                          class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
                    <option value="Baik" {{ ($item['kondisi'] ?? 'Baik') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ ($item['kondisi'] ?? '') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ ($item['kondisi'] ?? '') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                  </select>
                  <button type="button" onclick="removeItem({{ $index }})" 
                          class="px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200">
                    Hapus
                  </button>
                </div>
              @endforeach
            @endif
          </div>
          <button type="button" onclick="addItem()" class="mt-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-200 transition">
            + Tambah Item Manual
          </button>
        </div>

        <div class="flex gap-3 pt-4">
          <button type="submit" class="flex-1 px-6 py-3 rounded-lg bg-gradient-to-r from-orange-600 to-orange-500 text-white font-semibold hover:from-orange-700 hover:to-orange-600 transition shadow">
            Update Lokasi
          </button>
          <a href="{{ route('admin.lokasi.index') }}" class="flex-1 px-6 py-3 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition text-center">
            Batal
          </a>
        </div>
      </form>
    </div>

  </div>
</div>

<script>
let itemCount = {{ count($existingItems ?? []) }};
let selectedItemName = '';

// Daftar item yang tersedia (bisa disesuaikan dengan data dari database)
const availableItems = [
  'Kursi', 'Meja', 'Komputer', 'Printer', 'Scanner', 'Proyektor', 'Whiteboard',
  'AC', 'Kipas Angin', 'Lampu', 'Kabel', 'Kunci', 'Lemari', 'Rak Buku',
  'Papan Tulis', 'Spidol', 'Penghapus', 'Telepon', 'Laptop', 'Mouse',
  'Keyboard', 'Monitor', 'Kamera CCTV', 'Speaker', 'Microphone'
];

// Search functionality
const searchInput = document.getElementById('search-item');
const searchResults = document.getElementById('search-results');
const selectedPreview = document.getElementById('selected-item-preview');
const selectedNameDisplay = document.getElementById('selected-item-name');

searchInput.addEventListener('input', function() {
  const query = this.value.toLowerCase().trim();
  
  if (query === '') {
    searchResults.classList.add('hidden');
    searchResults.innerHTML = '';
    return;
  }

  // Filter items berdasarkan inisial
  const filtered = availableItems.filter(item => 
    item.toLowerCase().includes(query)
  );

  if (filtered.length === 0) {
    searchResults.innerHTML = '<div class="px-4 py-2 text-gray-500 text-sm">Tidak ada hasil</div>';
    searchResults.classList.remove('hidden');
  } else {
    searchResults.innerHTML = filtered.map(item => 
      `<div class="px-4 py-2 hover:bg-purple-50 cursor-pointer text-sm search-result-item" data-item="${item}">
        ${item}
      </div>`
    ).join('');
    searchResults.classList.remove('hidden');
  }

  // Add click handler to results
  document.querySelectorAll('.search-result-item').forEach(el => {
    el.addEventListener('click', function() {
      const itemName = this.getAttribute('data-item');
      selectItem(itemName);
    });
  });
});

// Close search results when clicking outside
document.addEventListener('click', function(e) {
  if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
    searchResults.classList.add('hidden');
  }
});

// Select item (show preview, not add yet)
function selectItem(itemName) {
  selectedItemName = itemName;
  selectedNameDisplay.textContent = itemName;
  selectedPreview.classList.remove('hidden');
  searchInput.value = '';
  searchResults.classList.add('hidden');
}

// Cancel selection
function cancelSelection() {
  selectedItemName = '';
  selectedPreview.classList.add('hidden');
}

// Confirm add item (when user clicks "Tambah Item Ini" button)
function confirmAddItem() {
  if (!selectedItemName) return;
  
  itemCount++;
  const container = document.getElementById('items-container');
  const itemDiv = document.createElement('div');
  itemDiv.className = 'flex gap-2';
  itemDiv.id = 'item-' + itemCount;
  itemDiv.innerHTML = `
    <input type="text" name="items[${itemCount}][nama_item]" 
           value="${selectedItemName}"
           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500" 
           placeholder="Nama item" required>
    <input type="number" name="items[${itemCount}][jumlah]" 
           class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500" 
           placeholder="Jumlah" min="1" value="1">
    <select name="items[${itemCount}][kondisi]" 
            class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
      <option value="Baik">Baik</option>
      <option value="Rusak Ringan">Rusak Ringan</option>
      <option value="Rusak Berat">Rusak Berat</option>
    </select>
    <button type="button" onclick="removeItem(${itemCount})" 
            class="px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200">
      Hapus
    </button>
  `;
  container.appendChild(itemDiv);
  
  // Reset selection
  cancelSelection();
}

function addItem() {
  itemCount++;
  const container = document.getElementById('items-container');
  const itemDiv = document.createElement('div');
  itemDiv.className = 'flex gap-2';
  itemDiv.id = 'item-' + itemCount;
  itemDiv.innerHTML = `
    <input type="text" name="items[${itemCount}][nama_item]" 
           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500" 
           placeholder="Nama item" required>
    <input type="number" name="items[${itemCount}][jumlah]" 
           class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500" 
           placeholder="Jumlah" min="1" value="1">
    <select name="items[${itemCount}][kondisi]" 
            class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
      <option value="Baik">Baik</option>
      <option value="Rusak Ringan">Rusak Ringan</option>
      <option value="Rusak Berat">Rusak Berat</option>
    </select>
    <button type="button" onclick="removeItem(${itemCount})" 
            class="px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200">
      Hapus
    </button>
  `;
  container.appendChild(itemDiv);
}

function removeItem(id) {
  const item = document.getElementById('item-' + id);
  if (item) {
    item.remove();
  }
}
</script>
@endsection
