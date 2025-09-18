<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel UKK Sarpras') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden"> 

    {{-- Sidebar fix --}}
    @include('partials.sidebar')

    {{-- Wrapper konten --}}
    <div class="ml-64 flex flex-col w-full h-screen">

        {{-- Navbar fix --}}
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center flex-shrink-0">
            <h1 class="text-xl font-bold text-gray-700">
                Dashboard {{ ucfirst(auth()->user()->role) }}
            </h1>
            <div class="relative">
                <button id="userMenuBtn" class="flex items-center gap-2 focus:outline-none">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->username }}" 
                         class="w-9 h-9 rounded-full border" alt="avatar">
                    <span class="text-gray-600 hidden sm:inline">{{ auth()->user()->username }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown --}}
                <div id="userDropdown" 
                     class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                    <a href="{{ route('profile.edit') }}" 
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Konten scrollable --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

        {{-- Footer fix di bawah --}}
        <footer class="bg-gray-200 text-center py-3 text-sm text-gray-600 flex-shrink-0">
            &copy; {{ date('Y') }} Aplikasi Sarpras UKK
        </footer>
    </div>

    <script>
        const btn = document.getElementById('userMenuBtn');
        const menu = document.getElementById('userDropdown');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>
