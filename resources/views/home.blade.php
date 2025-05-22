<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Warung Ajus</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  {{-- <link rel="stylesheet" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.gstatic.com" crossorigin> --}}
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

  <!--animasi AOS-->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>

<body>
  <header class="navbar">
    <div class="navbar-left">
      <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung" class="logo-warung">
    </div>
    <nav>
      <a href="#beranda">Beranda</a>
      <a href="{{ route('tentang') }}">Tentang Kami</a>
      <a href="#menu">Menu</a>
      <a href="#kontak">Kontak</a>
    </nav>
    <a href="#pesan" class="btn-pesan">Pesan Sekarang</a>
    <div class="navbar-right">
      <div class="icon-group">
        <i class="fas fa-shopping-cart icon"></i>
        <i class="fas fa-user icon"></i>
      </div>
    </div>
  </header>




  <section class="hero">
    <div class="hero-bg-top"></div>
    <div class="hero-bg-bottom"></div>

    <div class="hero-content">
      <div class="hero-text">
        <!-- Bagian Merah -->
        <div class="hero-title" data-aos="fade-right">
          <h1>Indonesia <br><span class="bold">Food</span> <span class="italic">Delicious</span></h1>
        </div>

        <!-- Bagian Kuning -->
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





  <section class="info-umkm" id="tentangkami">
    <div class="info-right" data-aos="fade-right">
      <h3>INFORMASI UMKM</h3>
      <p>Warung Ajus merupakan salah satu Usaha
        Mikro, Kecil, dan Menengah (UMKM)
        yang telah beroperasi sejak tahun 2012.
        Warung Ajus memiliki target pasar yang luas,
        mencakup semua kalangan yang ingin berbelanja,
        namun lebih berfokus pada pekerja dan mahasiswa.
        Oleh karena itu, Warung Ajus menerapkan harga
        yang terjangkau bagi pelanggannya.
      </p>
      <a href="{{ route('tentang') }}" class="btn-selengkapnya">Selengkapnya</a>
    </div>
    <div class="info-left" data-aos="zoom-in-up">
      <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung" class="logo-warung">
    </div>
  </section>

  <footer id="kontak">
    <div class="footer-item">📍 Alamat <br> Jl. Goa gong No.3 Jimbaran</div>
    <div class="footer-item">⏰ Jam Operasional <br> Senin - Jumat: 08.00 - 19.00</div>
    <div class="footer-item">📞 No Telp <br> 087887920415 </div>
  </footer>

  <!--AOS-->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>

</body>

</html>