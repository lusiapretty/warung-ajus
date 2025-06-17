<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warung Ajus')</title> {{-- Default title jika belum didefinisikan --}}
    
    <!-- Link CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    @include('partials.pelanggan.navbar') {{-- Menyertakan file navbar.blade.php dari folder partials --}}

    {{-- Konten Halaman --}}
    <main>
        @yield('content') {{-- Konten halaman yang diisi oleh @section('content') di halaman tertentu --}}
    </main>

    {{-- Footer --}}
    @include('partials.pelanggan.footer') {{-- Menyertakan file footer.blade.php dari folder partials --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
