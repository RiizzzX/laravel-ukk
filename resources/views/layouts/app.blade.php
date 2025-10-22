<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel UKK Sarpras') }}</title>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
    
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }
        .font-display {
            font-family: 'Poppins', 'Inter', sans-serif;
        }
    </style>
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
        </header>

        {{-- Konten scrollable --}}
        <main class="flex-1 overflow-y-auto">
            <div class="min-h-full p-6">
                @yield('content')
            </div>
            
            {{-- Footer menempel di bawah setiap page --}}
            <footer class="bg-white/50 backdrop-blur-sm text-center py-3 text-sm text-gray-500 border-t border-gray-200 mt-8">
                &copy; {{ date('Y') }} Aplikasi Sarpras UKK
            </footer>
        </main>
    </div>

</body>
</html>
