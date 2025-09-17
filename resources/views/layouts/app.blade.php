<!-- resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>{{ config('app.name','Sarpras') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-pink-50 min-h-screen">
  @includeIf('layouts.navigation') {{-- optional --}}
  <main class="py-8">
    @yield('content')
  </main>
</body>
</html>
