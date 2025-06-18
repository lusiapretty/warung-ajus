{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Beranda - Warung Ajus')

@section('content')

<!-- Hero Section -->
<section class="hero">
  <div class="hero-bg-top"></div>
  <div class="hero-bg-bottom"></div>

<body>
  <header class="navbar">

  <div class="navbar-right">

      <!-- user-->
      <!-- user -->
<div class="user-dropdown-wrapper">
  <div class="user-icon" onclick="toggleUserDropdown()">
    <i class="fas fa-user icon"></i>
  </div>

  <div id="userDropdown" class="user-dropdown hidden">
    @if(Auth::check() && Auth::user()->role === 'pelanggan')
      <div class="dropdown-header">
        <p>Halo, <strong>{{ Auth::user()->name }}</strong></p>
        <small>Kelola akun & pesanan Anda</small>
      </div>

      <ul class="dropdown-list">
        <li><a href="{{ route('profil.edit') }}"><i class="fas fa-user-circle"></i> Profil Saya</a></li>
        <li><a href="#"><i class="fas fa-box-open"></i> Pesanan Saya</a></li>
      </ul>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn logout-btn">Logout</button>
      </form>

    @else
      <div class="dropdown-header">
        <p>Selamat Datang di <strong>Warung Ajus</strong></p>
        <small>Akses akun & kelola pesanan</small>
      </div>

      <div class="dropdown-actions">
        <a href="{{ route('login') }}" class="btn login-btn">Login</a>
        <a href="{{ route('register') }}" class="btn register-btn">Daftar</a>
      </div>
    @endif
  </div>
</div>

</header>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-bg-top"></div>
  <div class="hero-bg-bottom"></div>
  
  <div class="hero-content">
    <div class="hero-text">
      <div class="hero-title" data-aos="fade-right">
        <h1>Indonesia <br><span class="bold">Food</span> <span class="italic">Delicious</span></h1>
      </div>

      <div class="hero-description" data-aos="fade-right">
        <h3>Tipat Cantok</h3>
        <p>
          Salah satu menu favorit di Warung Ajus adalah Tipat Cantok. <br>
          Tipat Cantok merupakan hidangan tradisional Bali yang menyerupai gado-gado,
          terdiri dari ketupat (tipat) dan sayuran rebus yang disiram dengan bumbu kacang.
          Kata 'cantok' sendiri merujuk pada proses mengulek bumbu.
        </p>
      </div>
    </div>

    <div class="hero-images" data-aos="zoom-in-up">
      <img src="{{ asset('img/tipat-cantok.png') }}" alt="Tipat Cantok">
    </div>
  </div>
</section>

<!-- Menu Favorit -->
<section class="menu-favorit-card" id="menu">
  <div class="card-menu" data-aos="fade-up">
    <h2 class="menu-title">Menu Favorit</h2>
    <div class="menu-list">
      <div class="menu-item">
        <img src="{{ asset('img/rujak-kuah-pindang.png') }}" alt="Rujak Kuah Pindang">
        <p class="menu-desc">Rujak Kuah Pindang</p>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/es-campur.png') }}" alt="Es Campur">
        <p class="menu-desc">Es Campur</p>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/soto-ayam.png') }}" alt="Soto Ayam">
        <p class="menu-desc">Soto Ayam</p>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/nasi-campur.png') }}" alt="Nasi Campur">
        <p class="menu-desc">Nasi Campur</p>
      </div>
    </div>
  </div>
</section>

<!-- Info UMKM -->
<section class="info-umkm" id="tentangkami">
  <div class="info-right" data-aos="fade-right">
    <h3>INFORMASI UMKM</h3>
    <p>Warung Ajus merupakan salah satu Usaha Mikro, Kecil, dan Menengah (UMKM)
      yang telah beroperasi sejak tahun 2012. Warung Ajus memiliki target pasar yang luas,
      mencakup semua kalangan yang ingin berbelanja, namun lebih berfokus pada pekerja dan mahasiswa.
      Oleh karena itu, Warung Ajus menerapkan harga yang terjangkau bagi pelanggannya.
    </p>
    <a href="{{ route('tentang') }}" class="btn-selengkapnya">Selengkapnya</a>
  </div>
  <div class="info-left" data-aos="zoom-in-up">
    <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung" class="logo-warung">
  </div>
</section>

@endsection
@section('scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
  AOS.init();
</script>

<!--untuk ikon user-->
  <script>
  function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('hidden');
  }

  // Menutup dropdown 
  window.addEventListener('click', function (e) {
    const icon = document.querySelector('.user-icon');
    const dropdown = document.getElementById('userDropdown');
    if (!icon.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });
</script>
@endsection
