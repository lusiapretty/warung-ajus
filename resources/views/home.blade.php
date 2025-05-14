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

    <section class="hero" id="beranda">
        <div class="hero-text">
            <h1>Indonesia <br> <span class="bold">Food</span> <span class="italic">Delicious</span></h1>
            <h3>Tipat Cantok</h3>
            <p>Salah satu menu favorit di Warung Ajus adalah Tipat Cantok <br> 
               Tipat Cantok merupakan hidangan tradisional Bali yang <br>
               menyerupai gado-gado, terdiri dari ketupat (tipat) dan sayuran <br>
               rebus yang disiram dengan bumbu kacang. Kata 'cantok' <br>
               sendiri merujuk pada proses mengulek bumbu.
            </p>
            {{-- <a href="#pesan" class="btn-cta">Pesan Sekarang</a> --}}
        </div>
        <div class="hero-images">
            <img src="{{ asset('img/tipat-cantok.png') }}" alt="Tipat Cantok">
        </div>
    </section>

    <section class="menu-favorit" id="menu">
        <h2>Menu Favorit</h2>
        <div class="menu-list">
            <div class="menu-item"><img src="{{ asset('img/rujak-kuah-pindang.png') }}"><p>Rujak Kuah Pindang</p></div>
            <div class="menu-item"><img src="{{ asset('img/es-campur.png') }}"><p>Es Campur</p></div>
            <div class="menu-item"><img src="{{ asset('img/soto-ayam.png') }}"><p>Soto Ayam</p></div>
            <div class="menu-item"><img src="{{ asset('img/nasi-campur.png') }}"><p>Nasi Campur</p></div>
        </div>
    </section>

    <section class="info-umkm" id="tentangkami">
        <div class="info-right">
            <h3>INFORMASI UMKM</h3>
            <p>Warung Ajus merupakan salah satu Usaha <br>
               Mikro, Kecil, dan Menengah (UMKM) <br>
               yang telah beroperasi sejak tahun 2012. <br>
               Warung Ajus memiliki target pasar yang luas, <br>
               mencakup semua kalangan yang ingin berbelanja, <br>
               namun lebih berfokus pada pekerja dan mahasiswa. <br> 
               Oleh karena itu, Warung Ajus menerapkan harga <br> 
               yang terjangkau bagi pelanggannya.
            </p>
            <button class="btn-selengkapnya">Selengkapnya</button>
        </div>
        <div class="info-left">
            <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung" class="logo-warung">
        </div>
    </section>

    <footer id="kontak">
        <div class="footer-item">📍 Alamat <br> Jl. Goa gong No.3 Jimbaran</div>
        <div class="footer-item">⏰ Jam Operasional <br> Senin - Jumat: 08.00 - 19.00</div>
        <div class="footer-item">📞 No Telp <br> 087887920415 </div>
    </footer>
    
</body>
</html>