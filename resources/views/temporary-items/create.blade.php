@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  {{-- Header dengan Penjelasan --}}
  <div class="mb-6">
    <div class="flex items-center gap-3 mb-3">
      <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
      </div>
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Ajukan Item/Lokasi Baru</h1>
        <p class="text-gray-500">Tambahkan barang atau lokasi yang belum tersedia di sistem</p>
      </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-xl p-5 mb-6">
      <div class="flex gap-4">
        <div class="flex-shrink-0">
          <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
          </svg>
        </div>
        <div class="flex-1">
          <h3 class="font-bold text-blue-900 mb-2">ℹ Apa itu Temporary Item?</h3>
          <p class="text-sm text-blue-800 leading-relaxed mb-3">
            <strong>Temporary Item</strong> adalah fitur yang memungkinkan Anda mengajukan <strong>barang/item baru</strong> atau <strong>lokasi baru</strong> yang belum ada dalam sistem. 
          </p>
          <div class="bg-white/50 rounded-lg p-3 mb-3">
            <p class="text-sm text-blue-900 font-semibold mb-2"> Kapan digunakan?</p>
            <ul class="text-sm text-blue-800 space-y-1 ml-4">
              <li class="flex items-start gap-2">
                <span class="text-blue-500 mt-0.5"></span>
                <span>Ketika barang yang rusak <strong>belum ada</strong> dalam daftar item sistem (contoh: "Proyektor Ruang 301")</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-blue-500 mt-0.5"></span>
                <span>Ketika lokasi kerusakan <strong>belum terdaftar</strong> dalam sistem (contoh: "Gedung Baru Lantai 4")</span>
              </li>
            </ul>
          </div>
          <div class="bg-white/50 rounded-lg p-3">
            <p class="text-sm text-blue-900 font-semibold mb-2"> Bagaimana prosesnya?</p>
            <ol class="text-sm text-blue-800 space-y-1 ml-4 list-decimal">
              <li>Anda mengisi form pengajuan item/lokasi baru</li>
              <li>Pengajuan akan <strong>direview oleh Admin</strong></li>
              <li>Jika disetujui, item/lokasi akan <strong>ditambahkan ke sistem</strong></li>
              <li>Setelah itu, Anda bisa <strong>membuat pengaduan</strong> menggunakan item/lokasi tersebut</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
      </div>
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
      <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <div class="flex-1">
          <p class="text-red-700 font-semibold mb-1">Terdapat kesalahan:</p>
          <ul class="text-sm text-red-600 list-disc ml-4">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  {{-- Form --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-purple-600 to-pink-500 px-6 py-4">
      <h2 class="text-xl font-bold text-white"> Form Pengajuan</h2>
      <p class="text-purple-100 text-sm">Pilih salah satu atau keduanya</p>
    </div>

    <form action="{{ route('temporary-items.store') }}" method="POST" class="p-6">
      @csrf

      {{-- Pilihan: Item Baru atau Lokasi Baru --}}
      <div class="grid md:grid-cols-2 gap-6 mb-6">
        {{-- Card Item Baru --}}
        <div class="border-2 border-gray-200 rounded-xl p-5 hover:border-purple-300 transition-all">
          <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-blue-100 rounded-lg">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-gray-800"> Item/Barang Baru</h3>
              <p class="text-xs text-gray-500">Ajukan barang yang belum ada</p>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" id="addItemCheckbox" class="w-4 h-4 text-purple-600 rounded">
              <span class="text-sm font-medium text-gray-700">Ajukan Item Baru</span>
            </label>
          </div>

          <div id="itemBaruDiv" class="hidden space-y-3">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Item/Barang Baru <span class="text-red-500">*</span></label>
              <input type="text" name="nama_barang_baru" id="nama_barang_baru"
                     class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                     placeholder="Contoh: Proyektor Ruang 301">
              <p class="mt-1 text-xs text-gray-500"> Berikan nama yang spesifik dan jelas</p>
            </div>
          </div>
        </div>

        {{-- Card Lokasi Baru --}}
        <div class="border-2 border-gray-200 rounded-xl p-5 hover:border-purple-300 transition-all">
          <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-gray-800"> Lokasi Baru</h3>
              <p class="text-xs text-gray-500">Ajukan lokasi yang belum ada</p>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" id="addLokasiCheckbox" class="w-4 h-4 text-purple-600 rounded">
              <span class="text-sm font-medium text-gray-700">Ajukan Lokasi Baru</span>
            </label>
          </div>

          <div id="lokasiBaruDiv" class="hidden space-y-3">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lokasi Baru <span class="text-red-500">*</span></label>
              <input type="text" name="lokasi_barang_baru" id="lokasi_barang_baru"
                     class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                     placeholder="Contoh: Gedung Baru Lantai 4">
              <p class="mt-1 text-xs text-gray-500"> Sertakan detail seperti gedung, lantai, atau ruangan</p>
            </div>
          </div>
        </div>
      </div>

      {{-- Warning jika tidak ada yang dipilih --}}
      <div id="warningNotSelected" class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          <p class="text-sm text-yellow-800 font-medium"> Pilih minimal satu: <strong>Item Baru</strong> atau <strong>Lokasi Baru</strong></p>
        </div>
      </div>

      {{-- Buttons --}}
      <div class="flex items-center gap-3">
        <button type="submit" id="submitBtn" disabled
                class="flex-1 bg-gradient-to-r from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 text-white px-6 py-3 rounded-xl font-semibold shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Kirim Pengajuan
        </button>
        <a href="{{ route('temporary-items.index') }}"
           class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
          Batal
        </a>
      </div>
    </form>
  </div>

  {{-- Additional Info --}}
  <div class="mt-6 bg-purple-50 border border-purple-200 rounded-xl p-5">
    <div class="flex items-start gap-3">
      <svg class="w-6 h-6 text-purple-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
      </svg>
      <div>
        <h4 class="font-bold text-purple-900 mb-2"> Catatan Penting:</h4>
        <ul class="text-sm text-purple-800 space-y-1 list-disc ml-4">
          <li>Pengajuan akan diproses maksimal <strong>2 x 24 jam</strong></li>
          <li>Anda akan menerima <strong>notifikasi</strong> setelah pengajuan disetujui/ditolak</li>
          <li>Setelah disetujui, item/lokasi baru bisa langsung digunakan untuk pengaduan</li>
          <li>Jika ditolak, Anda akan diberikan alasan penolakan</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const addItemCheckbox = document.getElementById('addItemCheckbox');
  const addLokasiCheckbox = document.getElementById('addLokasiCheckbox');
  const itemBaruDiv = document.getElementById('itemBaruDiv');
  const lokasiBaruDiv = document.getElementById('lokasiBaruDiv');
  const warningNotSelected = document.getElementById('warningNotSelected');
  const submitBtn = document.getElementById('submitBtn');
  const namaBarangInput = document.getElementById('nama_barang_baru');
  const lokasiBarangInput = document.getElementById('lokasi_barang_baru');

  function updateFormState() {
    const itemChecked = addItemCheckbox.checked;
    const lokasiChecked = addLokasiCheckbox.checked;
    
    // Show/hide divs
    itemBaruDiv.classList.toggle('hidden', !itemChecked);
    lokasiBaruDiv.classList.toggle('hidden', !lokasiChecked);
    warningNotSelected.classList.toggle('hidden', itemChecked || lokasiChecked);
    
    // Enable/disable inputs
    namaBarangInput.disabled = !itemChecked;
    lokasiBarangInput.disabled = !lokasiChecked;
    
    // Clear values if unchecked
    if (!itemChecked) namaBarangInput.value = '';
    if (!lokasiChecked) lokasiBarangInput.value = '';
    
    // Enable submit if at least one is checked
    submitBtn.disabled = !itemChecked && !lokasiChecked;
  }

  addItemCheckbox.addEventListener('change', updateFormState);
  addLokasiCheckbox.addEventListener('change', updateFormState);
  
  // Initial state
  updateFormState();
});
</script>
@endsection
