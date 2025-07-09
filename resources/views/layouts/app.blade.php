<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warung Ajus')</title>

    <!-- Link CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- CSS Khusus Proyek -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tentang-kami.css') }}">

    
    <!-- Preload untuk gambar hero -->
    <link rel="preload" as="image" href="{{ asset('img/hero-bg.jpg') }}">
    <link rel="preload" as="image" href="{{ asset('img/tipat-cantok.png') }}">
    <link rel="preload" as="image" href="{{ asset('hero-banner-bg.png') }}">
  

 
    


    @stack('styles') {{-- Untuk inject CSS tambahan dari halaman tertentu --}}
</head>
<body>

      @if (session('success'))
      <div class="custom-alert">
        {{ session('success') }}
      </div>
    @endif

    {{-- Navbar --}}
    @include('partials.pelanggan.navbar')

    {{-- Konten Halaman --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.pelanggan.footer')

    {{-- Script JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    @stack('scripts') {{-- Untuk script tambahan dari halaman tertentu --}}

   {{-- scroll navbar --}}
 <script>
  window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 20) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
</script>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    const navbar = document.querySelector('.navbar');
    const infoUmkm = document.querySelector('.info-umkm');

    if (navbar && infoUmkm) {
      const navbarHeight = navbar.offsetHeight;
      infoUmkm.style.paddingTop = `${navbarHeight + 30}px`; // +30 agar aman
    }
  });
</script>

<script>
  function toggleMenu() {
    document.getElementById('mobileMenu').classList.toggle('show');
  }
</script>


    <script>
        AOS.init();
    </script>
</body>
</html>
