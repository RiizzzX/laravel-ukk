<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngasar – Ngadu Sarana Prasarana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in-left { animation: slideInLeft 0.8s ease-out forwards; }
        .slide-in-right { animation: slideInRight 0.8s ease-out forwards; }
        .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .gradient-text {
            background: linear-gradient(135deg, #7e22ce 0%, #8b5cf6 100%); /* Ungu lebih gelap untuk kontras */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-purple-50 overflow-x-hidden"> <!-- Latar belakang gradien lembut -->
    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-lg shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-indigo-500 rounded-xl flex items-center justify-center"> <!-- Gradien ungu-indigo -->
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Ngasar</h1>
                        <p class="text-xs text-gray-500">Ngadu Sarana Prasarana</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="#tentang" class="text-gray-600 hover:text-purple-700 font-medium transition">Tentang</a> <!-- Warna hover ungu -->
                    <a href="#fitur" class="text-gray-600 hover:text-purple-700 font-medium transition">Fitur</a>
                    <a href="#cara-kerja" class="text-gray-600 hover:text-purple-700 font-medium transition">Cara Kerja</a>
                    <a href="{{ route('login') }}" class="px-5 py-2 bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-lg font-semibold hover:shadow-lg transition-all"> <!-- Gradien tombol -->
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2 border-2 border-purple-600 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center justify-center pt-16 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Efek latar belakang lebih lembut -->
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse"></div>
            <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="slide-in-left">
                    <div class="inline-block px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold mb-6"> <!-- Warna teks lebih gelap -->
                        ✨ Sistem Terpadu Sarana Prasarana
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Laporkan <span class="gradient-text">Barang Rusak</span> di Sekolah
                    </h1>
                    <p class="text-xl text-gray-700 mb-8 leading-relaxed"> <!-- Warna teks deskripsi lebih gelap -->
                        Platform digital untuk melaporkan kerusakan sarana prasarana di sekolah. Dilanjutkan dengan verifikasi admin dan ditangani oleh petugas terlatih hingga selesai.
                    </p>
                    <div class="flex gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center gap-2"> <!-- Gradien tombol -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            Mulai Sekarang
                        </a>
                        <a href="#cara-kerja" class="px-8 py-4 bg-white border-2 border-purple-300 text-gray-700 rounded-xl font-bold text-lg hover:border-purple-500 hover:text-purple-700 transition-all"> <!-- Warna border dan teks lebih ungu -->
                            Pelajari Alurnya
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-6 mt-12">
                        <div>
                            <p class="text-3xl font-bold text-purple-700">Verifikasi</p> <!-- Warna teks ungu -->
                            <p class="text-gray-600 text-sm">Diproses Admin</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-purple-700">Petugas</p> <!-- Warna teks ungu -->
                            <p class="text-gray-600 text-sm">Langsung Bertindak</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-purple-700">Selesai</p> <!-- Warna teks ungu -->
                            <p class="text-gray-600 text-sm">Notifikasi Real-Time</p>
                        </div>
                    </div>
                </div>

                <div class="slide-in-right hidden md:block">
                    <div class="relative float-animation">
                        <div class="bg-gradient-to-br from-purple-500 to-indigo-500 rounded-3xl p-8 shadow-2xl"> <!-- Gradien card -->
                            <div class="bg-white rounded-2xl p-6 space-y-4">
                                <div class="flex items-center gap-3 border-b pb-4">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">Kipas Mati</p>
                                        <p class="text-sm text-gray-500">Ruang Kelas 11B</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Status</span>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Diterima Admin</span> <!-- Warna status -->
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Petugas</span>
                                        <span class="text-sm font-semibold text-gray-800">Rudi (Belum Ambil)</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Waktu</span>
                                        <span class="text-sm text-gray-800">10 menit lalu</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tentang Section --}}
    <section id="tentang" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Tentang Ngasar</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Ngasar adalah sistem digital untuk melaporkan kerusakan sarana prasarana sekolah. Setiap laporan diverifikasi oleh admin dan ditangani oleh petugas hingga selesai.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl p-8 hover:shadow-xl transition-all fade-in-up"> <!-- Gradien card -->
                    <div class="w-14 h-14 bg-gradient-to-r from-purple-600 to-indigo-500 rounded-xl flex items-center justify-center mb-6"> <!-- Gradien ikon -->
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Mudah & Cepat</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Laporkan kerusakan hanya dalam hitungan menit. Sistem langsung meneruskan laporan ke admin untuk verifikasi.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-8 hover:shadow-xl transition-all fade-in-up" style="animation-delay: 0.2s;"> <!-- Gradien card -->
                    <div class="w-14 h-14 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl flex items-center justify-center mb-6"> <!-- Gradien ikon -->
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Dikelola Admin</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Setiap laporan diverifikasi oleh admin sekolah sebelum diteruskan ke petugas teknis untuk penanganan.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 hover:shadow-xl transition-all fade-in-up" style="animation-delay: 0.4s;"> <!-- Gradien card -->
                    <div class="w-14 h-14 bg-gradient-to-r from-green-600 to-emerald-500 rounded-xl flex items-center justify-center mb-6"> <!-- Gradien ikon -->
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Ditangani Petugas</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Petugas teknis mengambil laporan yang sudah diverifikasi, memperbaiki, lalu menandai sebagai selesai.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Fitur Section --}}
    <section id="fitur" class="py-20 bg-gray-50"> <!-- Latar belakang abu muda -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Dukungan lengkap untuk pelaporan, verifikasi admin, dan penanganan oleh petugas teknis
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-width="2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Laporan Detail</h3>
                            <p class="text-gray-600">Isi nama barang, lokasi, deskripsi kerusakan, dan unggah foto. Dapatkan nomor tiket otomatis.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Verifikasi Admin</h3>
                            <p class="text-gray-600">Admin menerima dan memverifikasi laporan sebelum diteruskan ke petugas teknis.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Ambil & Proses oleh Petugas</h3>
                            <p class="text-gray-600">Petugas dapat mengambil laporan yang sudah diverifikasi, lalu menindaklanjuti di lapangan.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-width="2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Notifikasi Selesai</h3>
                            <p class="text-gray-600">Pelapor langsung mendapat notifikasi ketika petugas menyelesaikan perbaikan.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Histori Laporan</h3>
                            <p class="text-gray-600">Lihat semua laporan Anda: menunggu verifikasi, diproses petugas, atau sudah selesai.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-all">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Privasi & Keamanan</h3>
                            <p class="text-gray-600">Data laporan hanya bisa diakses oleh pelapor, admin, dan petugas yang menangani.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Cara Kerja Section --}}
    <section id="cara-kerja" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Cara Kerja Sistem</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Dari laporan hingga penyelesaian — semua tercatat dan transparan
                </p>
            </div>

            <div class="relative">
                <!-- Garis timeline -->
                <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-purple-500 to-indigo-500"></div>

                <div class="relative mb-12">
                    <div class="md:flex items-center justify-between">
                        <div class="md:w-5/12 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl p-8 shadow-md"> <!-- Gradien card -->
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xl"> <!-- Gradien nomor -->
                                    1
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Buat Laporan</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Siswa/staf melaporkan kerusakan sarana prasarana melalui form digital lengkap dengan foto dan lokasi.
                            </p>
                        </div>
                        <div class="hidden md:block w-2/12 text-center">
                            <div class="w-16 h-16 mx-auto bg-white rounded-full border-4 border-purple-500 flex items-center justify-center"> <!-- Border ungu -->
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="md:w-5/12"></div>
                    </div>
                </div>

                <div class="relative mb-12">
                    <div class="md:flex items-center justify-between">
                        <div class="md:w-5/12"></div>
                        <div class="hidden md:block w-2/12 text-center">
                            <div class="w-16 h-16 mx-auto bg-white rounded-full border-4 border-purple-500 flex items-center justify-center"> <!-- Border ungu -->
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="md:w-5/12 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-8 shadow-md"> <!-- Gradien card -->
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-xl"> <!-- Gradien nomor -->
                                    2
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Verifikasi oleh Admin</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Admin sekolah menerima laporan, memverifikasi keabsahan, lalu menyetujui agar bisa diambil petugas.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="relative mb-12">
                    <div class="md:flex items-center justify-between">
                        <div class="md:w-5/12 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 shadow-md"> <!-- Gradien card -->
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-600 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xl"> <!-- Gradien nomor -->
                                    3
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Petugas Ambil Laporan</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Petugas teknis melihat daftar laporan yang sudah diterima admin, lalu mengambil untuk ditindaklanjuti.
                            </p>
                        </div>
                        <div class="hidden md:block w-2/12 text-center">
                            <div class="w-16 h-16 mx-auto bg-white rounded-full border-4 border-purple-500 flex items-center justify-center"> <!-- Border ungu -->
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="md:w-5/12"></div>
                    </div>
                </div>

                <div class="relative mb-12">
                    <div class="md:flex items-center justify-between">
                        <div class="md:w-5/12"></div>
                        <div class="hidden md:block w-2/12 text-center">
                            <div class="w-16 h-16 mx-auto bg-white rounded-full border-4 border-purple-500 flex items-center justify-center"> <!-- Border ungu -->
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="md:w-5/12 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-8 shadow-md"> <!-- Gradien card -->
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xl"> <!-- Gradien nomor -->
                                    4
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Proses Perbaikan</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Petugas melakukan pengecekan dan perbaikan di lokasi sesuai laporan yang diterima.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="md:flex items-center justify-between">
                        <div class="md:w-5/12 bg-gradient-to-br from-pink-50 to-rose-50 rounded-2xl p-8 shadow-md"> <!-- Gradien card -->
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-pink-600 to-rose-500 rounded-full flex items-center justify-center text-white font-bold text-xl"> <!-- Gradien nomor -->
                                    5
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Selesai & Notifikasi</h3>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Setelah perbaikan selesai, petugas menandai laporan sebagai “Selesai”. Pelapor langsung menerima notifikasi.
                            </p>
                        </div>
                        <div class="hidden md:block w-2/12 text-center">
                            <div class="w-16 h-16 mx-auto bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center"> <!-- Gradien lingkaran akhir -->
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="md:w-5/12"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700"> <!-- Gradien tombol CTA -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Laporkan Sekarang!
            </h2>
            <p class="text-xl text-purple-100 mb-10 leading-relaxed">
                Bantu sekolah menjaga fasilitas tetap baik. Daftar sekarang dan mulai laporkan kerusakan!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-10 py-4 bg-white text-purple-700 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl hover:scale-105 transition-all">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="px-10 py-4 bg-purple-800 text-white rounded-xl font-bold text-lg border-2 border-white hover:bg-purple-900 transition-all">
                    Masuk ke Akun
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-indigo-500 rounded-xl flex items-center justify-center"> <!-- Gradien logo footer -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Ngasar</h3>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Platform digital untuk melaporkan, memverifikasi, dan menangani kerusakan sarana prasarana sekolah secara terpadu.
                    </p>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Menu</h4>
                    <ul class="space-y-2">
                        <li><a href="#tentang" class="hover:text-purple-400 transition">Tentang</a></li>
                        <li><a href="#fitur" class="hover:text-purple-400 transition">Fitur</a></li>
                        <li><a href="#cara-kerja" class="hover:text-purple-400 transition">Cara Kerja</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-purple-400 transition">Masuk</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: admin@sekolah.id</li>
                        <li>Telepon: (021) 1234-5678</li>
                        <li>Alamat: Jl. Sekolah No. 123</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-gray-500">
                <p>&copy; {{ date('Y') }} Ngasar. Sistem Pengaduan Sarana Prasarana Sekolah.</p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>